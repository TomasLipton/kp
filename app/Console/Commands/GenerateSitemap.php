<?php

namespace App\Console\Commands;

use App\Models\Topics;
use Illuminate\Console\Command;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'app:generate-sitemap';

    protected $description = 'Generate sitemap with multilang support for all public pages';

    public function handle(): void
    {
        $locales = LaravelLocalization::getSupportedLanguagesKeys();
        $defaultLocale = config('app.locale', 'pl');
        $baseUrl = config('app.url');

        $sitemap = Sitemap::create();

        // Static pages
        $staticPages = [
            '/',
            '/topics',
            '/login',
            '/register',
        ];

        foreach ($staticPages as $page) {
            $url = Url::create(LaravelLocalization::getLocalizedURL($defaultLocale, $page));

            foreach ($locales as $locale) {
                $localized = LaravelLocalization::getLocalizedURL($locale, $page);
                $url->addAlternate($localized, $locale);
            }

            $sitemap->add($url);
        }

        // Topics pages
        $topics = Topics::where('isVisibleToPublic', true)->get();

        foreach ($topics as $topic) {
            $topicPath = '/'.$topic->slug;
            $url = Url::create(LaravelLocalization::getLocalizedURL($defaultLocale, $topicPath))
                ->setLastModificationDate($topic->updated_at)
                ->setPriority(0.8);

            // Add image if exists
            if ($topic->picture) {
                $imageUrl = str_starts_with($topic->picture, 'http')
                    ? $topic->picture
                    : $baseUrl.'/storage/'.$topic->picture;

                $url->addImage($imageUrl, $topic->name_pl ?? $topic->slug);
            }

            // Add alternate language versions
            foreach ($locales as $locale) {
                $localized = LaravelLocalization::getLocalizedURL($locale, $topicPath);
                $url->addAlternate($localized, $locale);
            }

            $sitemap->add($url);
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('✅ Sitemap generated: '.public_path('sitemap.xml'));
        $this->info('📊 Total URLs: '.count($sitemap->getTags()));
        $this->info('🌍 Locales: '.implode(', ', $locales));
        $this->info('📝 Topics included: '.$topics->count());
    }
}
