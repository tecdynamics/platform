<?php

namespace Tec\Base\Services;

use Tec\Base\Facades\BaseHelper;
use Tec\Base\Supports\Zipper;
use Tec\Theme\Facades\Theme;
use Exception;
use GuzzleHttp\Psr7\Utils;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class DownloadLocaleService
{
    public const REPOSITORY = 'Tec/translations';

    public function handle(string $locale): void
    {
        if (! File::isWritable(lang_path())) {
            throw new Exception('The "language" directory is not writable.');
        }

        if (! File::isWritable(storage_path('app'))) {
            throw new Exception('The "storage" directory is not writable.');
        }

        if (! in_array($locale, $this->getAvailableLocales())) {
            throw new Exception('The locale is not available.');
        }

        if ($locale === 'en' || File::exists(lang_path($locale))) {
            return;
        }

        $destination = storage_path('app/translations.zip');
        $path = storage_path("app/translations-master/{$locale}");

        BaseHelper::maximumExecutionTimeAndMemoryLimit();

        Http::withoutVerifying()
            ->sink(Utils::tryFopen($destination, 'w'))
            ->get(sprintf('https://github.com/%s/archive/refs/heads/master.zip', self::REPOSITORY))
            ->throw();

        $zip = new Zipper();

        $zip->extract($destination, storage_path('app'));

        File::copyDirectory("{$path}/{$locale}", lang_path($locale));

        if (File::isDirectory("{$path}/vendor")) {
            File::copyDirectory("{$path}/vendor", lang_path('vendor'));
        }

        $parentTheme = Theme::getThemeName();

        if (Theme::hasInheritTheme()) {
            $parentTheme = Theme::getInheritTheme();
        }

        if (File::exists("{$path}/{$locale}.json") && ! File::exists(lang_path("vendor/themes/{$parentTheme}/{$locale}.json"))) {
            File::copy("{$path}/{$locale}.json", lang_path("vendor/themes/{$parentTheme}/{$locale}.json"));
        }

        File::delete($destination);
        File::deleteDirectory(storage_path('app/translations-master'));
    }

    public function getAvailableLocales(): array
    {
        $locales = [];

        try {
            $data = Http::withoutVerifying()
                ->asJson()
                ->acceptJson()
                ->get(sprintf('https://api.github.com/repos/%s/git/trees/master', self::REPOSITORY))
                ->json('tree');

            foreach ($data as $item) {
                if ($item['type'] === 'tree') {
                    $locales[] = $item['path'];
                }
            }
        } catch (Exception) {
            return [];
        }

        return $locales;
    }
}
