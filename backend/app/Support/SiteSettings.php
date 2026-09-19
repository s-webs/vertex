<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SiteSettings
{
    public const CACHE_KEY = 'site.settings';

    /**
     * @return array<string, string|null>
     */
    public function all(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return Setting::query()->pluck('value', 'key')->all();
        });
    }

    public function get(string $key, ?string $default = null): ?string
    {
        $value = $this->all()[$key] ?? $default;

        return $value === '' ? $default : $value;
    }

    /**
     * @param  array<string, string|null>  $values
     */
    public function put(array $values): void
    {
        foreach ($values as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value, JSON_UNESCAPED_UNICODE);
            } elseif ($value !== null) {
                $value = (string) $value;
            }

            Setting::query()->updateOrCreate(
                ['key' => $key],
                ['value' => $value],
            );
        }

        Cache::forget(self::CACHE_KEY);
    }

    public function forgetCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public function companyName(): string
    {
        return $this->get('company_name') ?: 'Adilet-lift';
    }

    /**
     * @return list<array{label: string, number: string}>
     */
    public function phones(): array
    {
        $decoded = json_decode((string) $this->get('phones', '[]'), true);

        if (! is_array($decoded)) {
            return [];
        }

        $phones = [];

        foreach ($decoded as $phone) {
            if (! is_array($phone) || blank($phone['number'] ?? null)) {
                continue;
            }

            $phones[] = [
                'label' => (string) ($phone['label'] ?? 'Телефон'),
                'number' => (string) $phone['number'],
            ];
        }

        return $phones;
    }

    /**
     * @return array{label: string, number: string}|null
     */
    public function primaryPhone(): ?array
    {
        return $this->phones()[0] ?? null;
    }

    public function telHref(string $number): string
    {
        return 'tel:'.preg_replace('/[^\d+]/', '', $number);
    }

    public function whatsappUrl(): ?string
    {
        return $this->get('whatsapp_url');
    }

    public function instagramUrl(): ?string
    {
        return $this->get('instagram_url');
    }

    /**
     * @return list<string>
     */
    public function socialProfileUrls(): array
    {
        return array_values(array_filter([
            $this->whatsappUrl(),
            $this->instagramUrl(),
        ]));
    }

    public function email(): ?string
    {
        return $this->get('email');
    }

    public function address(): ?string
    {
        return $this->get('address');
    }

    public function logoUrl(): ?string
    {
        return Media::url($this->get('logo_path'));
    }

    public function logoHorizontalUrl(): ?string
    {
        return Media::url($this->get('logo_horizontal_path')) ?? $this->logoUrl();
    }

    public function faviconUrl(): ?string
    {
        return Media::url($this->get('favicon_path')) ?? $this->logoUrl();
    }

    public function ogImageUrl(): ?string
    {
        return Media::url($this->get('og_image_path'));
    }

    public function mapLat(): ?float
    {
        $value = $this->get('map_lat');

        return $value === null ? null : (float) $value;
    }

    public function mapLng(): ?float
    {
        $value = $this->get('map_lng');

        return $value === null ? null : (float) $value;
    }

    public function mapZoom(): int
    {
        return max(4, min(19, (int) ($this->get('map_zoom') ?: 14)));
    }

    public function hasMapPin(): bool
    {
        return $this->mapLat() !== null && $this->mapLng() !== null;
    }

    public function mapEmbedSrc(): string
    {
        if (! $this->hasMapPin()) {
            return 'https://www.openstreetmap.org/export/embed.html?bbox=46%2C40%2C88%2C56&layer=mapnik';
        }

        $lat = $this->mapLat();
        $lng = $this->mapLng();
        $zoom = $this->mapZoom();
        [$west, $south, $east, $north] = $this->mapBoundingBox($lat, $lng, $zoom);
        $bbox = implode('%2C', [$west, $south, $east, $north]);

        return "https://www.openstreetmap.org/export/embed.html?bbox={$bbox}&layer=mapnik&marker={$lat}%2C{$lng}";
    }

    public function mapExternalUrl(): string
    {
        if (! $this->hasMapPin()) {
            return 'https://www.openstreetmap.org/#map=4/48/67';
        }

        $zoom = $this->mapZoom();

        return sprintf(
            'https://www.openstreetmap.org/?mlat=%s&mlon=%s#map=%d/%s/%s',
            $this->mapLat(),
            $this->mapLng(),
            $zoom,
            $this->mapLat(),
            $this->mapLng(),
        );
    }

    /**
     * @return array{0: float, 1: float, 2: float, 3: float}
     */
    private function mapBoundingBox(float $lat, float $lng, int $zoom): array
    {
        // Each zoom step halves the visible area, matching OpenStreetMap levels.
        $latDelta = max(0.0004, 180 / (2 ** $zoom));
        $lngDelta = $latDelta / max(0.2, cos(deg2rad($lat)));

        return [
            $lng - $lngDelta,
            $lat - $latDelta,
            $lng + $lngDelta,
            $lat + $latDelta,
        ];
    }
}
