<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Artisan::call('app:generate-sitemap');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $sitemapPath = public_path('sitemap.xml');

        if (File::exists($sitemapPath)) {
            File::delete($sitemapPath);
        }
    }
};
