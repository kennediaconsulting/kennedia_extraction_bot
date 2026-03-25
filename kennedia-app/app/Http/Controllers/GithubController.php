<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GithubController extends Controller
{
    private function parseSummary(Request $req): array
    {
        $summaryRaw = $req->input('summary');
        if (!is_string($summaryRaw) || trim($summaryRaw) === '') {
            return [];
        }

        $decoded = json_decode($summaryRaw, true);
        return is_array($decoded) ? $decoded : [];
    }

    private function fallbackProcessedPages(Document $doc): int
    {
        if ($doc->page_start && $doc->page_end && $doc->page_end >= $doc->page_start) {
            return (int) ($doc->page_end - $doc->page_start + 1);
        }

        return (int) ($doc->pages_requested ?? 0);
    }

    public function callback(Request $req)
    {
        $sig = $req->header('X-Extractor-Signature');
        $secret = (string) config('services.extractor.secret');
        $body = $req->getContent();
        $expected = hash_hmac('sha256', $body, $secret);
        if (!hash_equals($expected, (string)$sig)) {
            Log::warning('extractor callback unauthorized', [
                'has_sig' => !empty($sig),
                'sig_len' => is_string($sig) ? strlen($sig) : 0,
                'secret_set' => $secret !== '',
                'body_len' => is_string($body) ? strlen($body) : 0,
                'ip' => $req->ip(),
                'ua' => substr((string) $req->userAgent(), 0, 120),
            ]);
            abort(401);
        }

        $payload = $req->json()->all();
        $docId = isset($payload['doc_id']) ? (int) $payload['doc_id'] : 0;
        $doc = $docId > 0
            ? Document::find($docId)
            : Document::where('filename', $payload['filename'] ?? '')->latest()->first();
        if (!$doc) return response()->noContent();

        $counts = is_array($payload['counts'] ?? null) ? $payload['counts'] : [];
        $processedPages = (int) ($counts['pages_processed'] ?? 0);
        if ($processedPages <= 0) {
            $processedPages = $this->fallbackProcessedPages($doc);
        }

        // Do not overwrite URLs from the upload-results step with runner-local paths like "outputs/*.csv".
        // Only mark status complete here and (optionally) set URLs if they are absolute http(s) links and current fields are empty.
        $doc->status = 'complete';
        $doc->pages_processed = $processedPages;
        $files = $payload['files'] ?? [];
        $csv = $files['csv'] ?? null;
        $xlsx = $files['xlsx'] ?? null;
        if (!$doc->csv_url && is_string($csv) && preg_match('/^https?:\/\//i', $csv)) {
            $doc->csv_url = $csv;
        }
        if (!$doc->xlsx_url && is_string($xlsx) && preg_match('/^https?:\/\//i', $xlsx)) {
            $doc->xlsx_url = $xlsx;
        }
        $doc->save();

        // Student rows are imported from uploadResults() to keep counting deterministic.
        return response()->json(['ok' => true]);
    }

    public function uploadResults(Request $req)
    {
        // Some server/proxy setups do not forward the Authorization header to PHP.
        // Accept either a Bearer token OR an explicit header.
        $auth = $req->bearerToken() ?: $req->header('X-Extractor-Token');
        $expectedToken = (string) config('services.extractor.token');
        if ((string)$auth !== $expectedToken) {
            Log::warning('extractor upload-results unauthorized', [
                'has_bearer' => !empty($req->bearerToken()),
                'has_x_token' => !empty($req->header('X-Extractor-Token')),
                'expected_set' => $expectedToken !== '',
                'ip' => $req->ip(),
                'ua' => substr((string) $req->userAgent(), 0, 120),
            ]);
            abort(401);
        }

        $docId = $req->input('doc_id');
        $doc = Document::find($docId);
        if (!$doc) abort(404);

        $summary = $this->parseSummary($req);
        $counts = is_array($summary['counts'] ?? null) ? $summary['counts'] : [];

        $csvFile = $req->file('csv');
        $xlsxFile = $req->file('xlsx');
        $docxFile = $req->file('docx');

        $rowsInserted = 0;
        // Keep per-document row counts clean by replacing previously imported rows.
        Student::where('document_id', $doc->id)->delete();

        if ($csvFile) {
            $csvPath = $csvFile->store('processed', 'public');
            $doc->csv_url = Storage::disk('public')->url($csvPath);

            if (($h = fopen(Storage::disk('public')->path($csvPath), 'r')) !== false) {
                $header = fgetcsv($h);
                while (($row = fgetcsv($h)) !== false) {
                    $data = array_combine($header, $row);
                    Student::create([
                        'document_id' => $doc->id,
                        'surname' => $data['surname'] ?? '',
                        'first_name' => $data['first_name'] ?? '',
                        'other_name' => $data['other_name'] ?? '',
                        'course_studied' => $data['course_studied'] ?? null,
                        'faculty' => $data['faculty'] ?? null,
                        'grade' => $data['grade'] ?? null,
                        'qualification_obtained' => $data['qualification_obtained'] ?? null,
                        'session' => $data['session'] ?? $doc->session,
                    ]);
                    $rowsInserted++;
                }
                fclose($h);
            }
        }
        if ($xlsxFile) { $xlsxPath = $xlsxFile->store('processed', 'public'); $doc->xlsx_url = Storage::disk('public')->url($xlsxPath); }
        if ($docxFile) { $docxPath = $docxFile->store('processed', 'public'); $doc->docx_url = Storage::disk('public')->url($docxPath); }

        $processedPages = (int) ($counts['pages_processed'] ?? 0);
        if ($processedPages <= 0) {
            $processedPages = $this->fallbackProcessedPages($doc);
        }

        $doc->status = 'complete';
        $doc->pages_processed = $processedPages;
        $doc->pages_with_results = $rowsInserted > 0 ? $processedPages : 0;
        $doc->save();
        return response()->json(['ok' => true, 'doc' => $doc]);
    }
}
