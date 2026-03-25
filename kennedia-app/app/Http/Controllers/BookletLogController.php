<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;

class BookletLogController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'year' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'month' => ['nullable', 'integer', 'min:1', 'max:12'],
        ]);

        $baseQuery = Document::query();

        $filteredQuery = Document::query()
            ->when(isset($validated['year']), fn ($q) => $q->whereYear('created_at', (int) $validated['year']))
            ->when(isset($validated['month']), fn ($q) => $q->whereMonth('created_at', (int) $validated['month']));

        $rows = (clone $filteredQuery)
            ->withCount('students')
            ->latest()
            ->get()
            ->map(function (Document $doc) {
                return [
                    'id' => $doc->id,
                    'filename' => $doc->filename,
                    'session' => $doc->session,
                    'status' => $doc->status,
                    'api_tier' => $doc->api_tier,
                    'page_start' => $doc->page_start,
                    'page_end' => $doc->page_end,
                    'pages_requested' => $doc->pages_requested,
                    'pages_processed' => $doc->pages_processed,
                    'pages_with_results' => $doc->pages_with_results,
                    'students_rows' => (int) ($doc->students_count ?? 0),
                    'created_at' => optional($doc->created_at)->toISOString(),
                ];
            });

        return response()->json([
            'filters' => [
                'year' => isset($validated['year']) ? (int) $validated['year'] : null,
                'month' => isset($validated['month']) ? (int) $validated['month'] : null,
            ],
            'summary' => [
                'overall' => [
                    'uploaded_total' => (int) (clone $baseQuery)->count(),
                    'successful_total' => (int) (clone $baseQuery)->where('status', 'complete')->count(),
                    'student_rows_total' => (int) (clone $baseQuery)->withCount('students')->get()->sum('students_count'),
                ],
                'filtered' => [
                    'uploaded_total' => (int) (clone $filteredQuery)->count(),
                    'successful_total' => (int) (clone $filteredQuery)->where('status', 'complete')->count(),
                    'student_rows_total' => (int) (clone $filteredQuery)->withCount('students')->get()->sum('students_count'),
                ],
            ],
            'rows' => $rows,
        ]);
    }
}
