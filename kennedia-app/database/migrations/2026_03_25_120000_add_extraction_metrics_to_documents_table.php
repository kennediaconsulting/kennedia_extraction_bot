<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->string('api_tier', 40)->nullable()->after('status');
            $table->unsignedInteger('page_start')->nullable()->after('api_tier');
            $table->unsignedInteger('page_end')->nullable()->after('page_start');
            $table->unsignedInteger('pages_requested')->nullable()->after('page_end');
            $table->unsignedInteger('pages_processed')->nullable()->after('pages_requested');
            $table->unsignedInteger('pages_with_results')->nullable()->after('pages_processed');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn([
                'api_tier',
                'page_start',
                'page_end',
                'pages_requested',
                'pages_processed',
                'pages_with_results',
            ]);
        });
    }
};
