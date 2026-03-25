<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    protected $fillable = [
        'filename',
        'path',
        'session',
        'status',
        'api_tier',
        'page_start',
        'page_end',
        'pages_requested',
        'pages_processed',
        'pages_with_results',
        'csv_url',
        'xlsx_url',
        'docx_url',
    ];

    protected $casts = [
        'page_start' => 'integer',
        'page_end' => 'integer',
        'pages_requested' => 'integer',
        'pages_processed' => 'integer',
        'pages_with_results' => 'integer',
    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
