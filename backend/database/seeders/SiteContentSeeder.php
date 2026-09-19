<?php

namespace Database\Seeders;

use App\Enums\SeoOwnerType;
use App\Models\Catalog;
use App\Models\PageImage;
use App\Models\SeoMeta;
use App\Models\Setting;
use App\Models\Work;
use App\Support\SiteSettings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class SiteContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedSettings();
        $this->seedPageImages();
        $this->seedCatalogs();
        $this->seedWorks();
        $this->seedSeoPages();

        app(SiteSettings::class)->forgetCache();
    }

    private function seedSettings(): void
    {
        $logo = $this->copyPublic($this->rootPath('assets/images/logo-transparent.png'), 'branding/logo.png');
        $logoHorizontal = $this->copyPublic($this->rootPath('assets/images/logo-transparent-horizontal.png'), 'branding/logo-horizontal.png');

        $values = [
            'company_name' => 'Adilet-lift',
            'logo_path' => $logo,
            'logo_horizontal_path' => $logoHorizontal,
            'favicon_path' => $logo,
            'phones' => json_encode([], JSON_UNESCAPED_UNICODE),
        ];

        foreach ($values as $key => $value) {
            if ($value === null) {
                continue;
            }

            Setting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }

    private function seedPageImages(): void
    {
        PageImage::query()->updateOrCreate(
            ['page_key' => 'home', 'slot_key' => 'hero'],
            [
                'path' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=85',
                'alt' => 'Современный высотный комплекс со стеклянными фасадами',
            ],
        );

        $about = $this->copyPublic($this->rootPath('assets/images/logo-transparent.png'), 'pages/about-side.png');

        if ($about) {
            PageImage::query()->updateOrCreate(
                ['page_key' => 'about', 'slot_key' => 'side'],
                [
                    'path' => $about,
                    'alt' => 'Логотип Adilet-lift',
                ],
            );
        }
    }

    private function seedCatalogs(): void
    {
        $items = [
            [
                'slug' => 'lincoln-passenger',
                'brand' => 'LINCOLN',
                'year' => '2026',
                'title' => 'Пассажирские лифты',
                'description' => 'Интеллектуальное управление, энергоэффективность, безопасность, варианты кабин, дверей и панелей управления.',
                'tags' => ['Пассажирские'],
                'cover' => $this->rootPath('UI/assets/catalogs/lincoln-2026.png'),
                'pdf' => $this->docsFile(fn (string $name): bool => str_starts_with($name, '202604')),
                'page_count' => 15,
                'sort_order' => 1,
            ],
            [
                'slug' => 'sige-passenger',
                'brand' => 'SIGE',
                'year' => null,
                'title' => 'Комплексные лифтовые решения',
                'description' => 'Пассажирские, панорамные, больничные, автомобильные и грузовые лифты, эскалаторы и движущиеся дорожки.',
                'tags' => ['Полная линейка'],
                'cover' => $this->rootPath('UI/assets/catalogs/sige-passenger.png'),
                'pdf' => $this->docsFile(fn (string $name): bool => str_contains($name, 'SIGE')),
                'page_count' => 30,
                'sort_order' => 2,
            ],
            [
                'slug' => 'sword-2026',
                'brand' => 'SWORD',
                'year' => '2026',
                'title' => 'Комплексный каталог',
                'description' => 'Серии WD700P, S700P/L, S700G, WD800, S800H, SIND, S700F/S, а также эскалаторы S900E и траволаторы S900T.',
                'tags' => ['Лифты и эскалаторы'],
                'cover' => $this->rootPath('UI/assets/catalogs/sword-2026.png'),
                'pdf' => $this->docsFile(fn (string $name): bool => str_contains($name, '202605')),
                'page_count' => 50,
                'sort_order' => 3,
            ],
            [
                'slug' => 'fujiaol-bilingual',
                'brand' => 'FUJIAOL · HANGZHOU LINGAO',
                'year' => null,
                'title' => 'Лифты и варианты отделки',
                'description' => 'Пассажирские и грузовые решения, оформление кабин, двери, панели управления, стандартные функции и эскалаторы.',
                'tags' => ['EN / VI'],
                'cover' => $this->rootPath('UI/assets/catalogs/fujiaol-bilingual.png'),
                'pdf' => $this->docsFile(fn (string $name): bool => str_contains(strtolower($name), 'billingual') || str_contains(strtolower($name), 'bilingual')),
                'page_count' => 64,
                'sort_order' => 4,
            ],
        ];

        foreach ($items as $item) {
            Catalog::query()->updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'brand' => $item['brand'],
                    'year' => $item['year'],
                    'title' => $item['title'],
                    'description' => $item['description'],
                    'tags' => $item['tags'],
                    'cover_path' => $this->copyPublic($item['cover'], 'catalogs/'.$item['slug'].'.png'),
                    'pdf_path' => $item['pdf'] ? $this->copyPublic($item['pdf'], 'catalogs/'.$item['slug'].'.pdf') : null,
                    'page_count' => $item['page_count'],
                    'sort_order' => $item['sort_order'],
                    'is_published' => true,
                ],
            );
        }
    }

    private function seedWorks(): void
    {
        $works = [
            [
                'slug' => 'residential',
                'number' => 'ОБЪЕКТ 01',
                'title' => 'Жилой комплекс',
                'description' => 'Лифтовое решение для современного жилого комплекса: комфортное движение, аккуратная интеграция оборудования и внимание к общественным пространствам.',
                'meta' => ['Жилой объект', 'Пассажирский лифт'],
                'photos' => [
                    ['src' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1800&q=88', 'alt' => 'Современный жилой комплекс', 'caption' => 'Архитектура объекта'],
                    ['src' => 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=1800&q=88', 'alt' => 'Современная общественная зона', 'caption' => 'Общественная зона'],
                    ['src' => 'https://images.unsplash.com/photo-1497366811353-6870744d04b2?auto=format&fit=crop&w=1800&q=88', 'alt' => 'Детали современного интерьера', 'caption' => 'Детали интерьера'],
                ],
            ],
            [
                'slug' => 'business',
                'number' => 'ОБЪЕКТ 02',
                'title' => 'Деловой центр',
                'description' => 'Решение для делового центра с учётом интенсивного пассажиропотока, требований к надёжности и архитектуре входной группы.',
                'meta' => ['Коммерческий объект', 'Пассажирский лифт'],
                'photos' => [
                    ['src' => 'https://images.unsplash.com/photo-1487958449943-2429e8be8625?auto=format&fit=crop&w=1800&q=88', 'alt' => 'Фасад делового центра', 'caption' => 'Фасад'],
                    ['src' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1800&q=88', 'alt' => 'Входная группа делового центра', 'caption' => 'Входная группа'],
                    ['src' => 'https://images.unsplash.com/photo-1497366761666-344187b2f2e5?auto=format&fit=crop&w=1800&q=88', 'alt' => 'Интерьер делового центра', 'caption' => 'Интерьер'],
                ],
            ],
            [
                'slug' => 'residence',
                'number' => 'ОБЪЕКТ 03',
                'title' => 'Частная резиденция',
                'description' => 'Компактное решение для частного дома, объединяющее удобство перемещения между этажами и деликатную интеграцию в интерьер.',
                'meta' => ['Частный объект', 'Домашний лифт'],
                'photos' => [
                    ['src' => 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1800&q=88', 'alt' => 'Интерьер частной резиденции', 'caption' => 'Интерьер'],
                    ['src' => 'https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=1800&q=88', 'alt' => 'Холл частной резиденции', 'caption' => 'Холл'],
                    ['src' => 'https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=1800&q=88', 'alt' => 'Архитектурные детали частной резиденции', 'caption' => 'Детали'],
                ],
            ],
        ];

        foreach ($works as $index => $item) {
            $work = Work::query()->updateOrCreate(
                ['slug' => $item['slug']],
                [
                    'number' => $item['number'],
                    'title' => $item['title'],
                    'description' => $item['description'],
                    'meta' => $item['meta'],
                    'sort_order' => $index + 1,
                    'is_published' => true,
                ],
            );

            $work->photos()->delete();

            foreach ($item['photos'] as $order => $photo) {
                $work->photos()->create([
                    'path' => $photo['src'],
                    'alt' => $photo['alt'],
                    'caption' => $photo['caption'],
                    'sort_order' => $order + 1,
                ]);
            }
        }
    }

    private function seedSeoPages(): void
    {
        foreach (config('site.pages') as $key => $page) {
            SeoMeta::query()->updateOrCreate(
                [
                    'owner_type' => SeoOwnerType::Page,
                    'owner_key' => $key,
                ],
                [
                    'title' => $page['fallback_title'],
                    'description' => $page['fallback_description'],
                    'robots' => 'index,follow',
                ],
            );
        }
    }

    private function rootPath(string $relative): string
    {
        return dirname(base_path()).DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relative);
    }

    private function copyPublic(?string $source, string $destination): ?string
    {
        if ($source === null || ! is_file($source)) {
            return null;
        }

        $absolute = storage_path('app/public/'.str_replace('/', DIRECTORY_SEPARATOR, $destination));
        File::ensureDirectoryExists(dirname($absolute));
        File::copy($source, $absolute);

        return $destination;
    }

    private function docsFile(callable $matcher): ?string
    {
        $directory = $this->rootPath('docs');

        if (! is_dir($directory)) {
            return null;
        }

        foreach (File::files($directory) as $file) {
            if ($matcher($file->getFilename())) {
                return $file->getPathname();
            }
        }

        return null;
    }
}
