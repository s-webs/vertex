@extends('layouts.admin')

@section('title', 'Настройки')
@section('heading', 'Настройки')
@section('kicker', 'Контакты, карта и брендинг')

@section('content')
    <form class="admin-form admin-form-grid" method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <label class="field span-2">Название компании
            <input name="company_name" value="{{ old('company_name', $values['company_name'] ?? $site->companyName()) }}" required>
        </label>
        <label class="field">WhatsApp URL
            <input name="whatsapp_url" value="{{ old('whatsapp_url', $values['whatsapp_url'] ?? '') }}" placeholder="https://wa.me/7...">
            @error('whatsapp_url') <span class="field-error">{{ $message }}</span> @enderror
        </label>
        <label class="field">Instagram URL
            <input name="instagram_url" value="{{ old('instagram_url', $values['instagram_url'] ?? '') }}" placeholder="https://instagram.com/...">
            @error('instagram_url') <span class="field-error">{{ $message }}</span> @enderror
        </label>
        <label class="field span-2">Email
            <input type="email" name="email" value="{{ old('email', $values['email'] ?? '') }}">
        </label>
        <label class="field span-2">Адрес
            <input name="address" value="{{ old('address', $values['address'] ?? '') }}">
        </label>
        <div class="span-2">
            <div class="eyebrow">Телефоны</div>
            @for ($i = 0; $i < 3; $i++)
                <div class="form-grid">
                    <label class="field">Подпись
                        <input name="phones[{{ $i }}][label]" value="{{ old("phones.$i.label", $phones[$i]['label'] ?? '') }}">
                    </label>
                    <label class="field">Номер
                        <input name="phones[{{ $i }}][number]" type="tel" data-phone-mask value="{{ old("phones.$i.number", $phones[$i]['number'] ?? '') }}" placeholder="+7 (___) ___-__-__">
                        @error("phones.$i.number") <span class="field-error">{{ $message }}</span> @enderror
                    </label>
                </div>
            @endfor
        </div>
        <div class="span-2">
            <div class="eyebrow">Карта</div>
            <div class="map-fields">
                <label class="field">Широта
                    <input name="map_lat" value="{{ old('map_lat', $values['map_lat'] ?? '') }}" placeholder="43.238949">
                    @error('map_lat') <span class="field-error">{{ $message }}</span> @enderror
                </label>
                <label class="field">Долгота
                    <input name="map_lng" value="{{ old('map_lng', $values['map_lng'] ?? '') }}" placeholder="76.889709">
                </label>
                <label class="field">Масштаб
                    <input name="map_zoom" type="number" min="4" max="19" value="{{ old('map_zoom', $values['map_zoom'] ?? '14') }}">
                    <span class="field-hint">4 — далеко, 19 — близко. Для улицы обычно 16–18.</span>
                </label>
            </div>
        </div>
        <div class="span-2">
            <div class="eyebrow">Брендинг</div>
            <div class="brand-upload-grid">
                <div class="brand-upload">
                    <span>Логотип</span>
                    <div class="brand-upload-preview">
                        @if (! empty($values['logo_path']))
                            <img src="{{ \App\Support\Media::url($values['logo_path']) }}" alt="Логотип">
                        @else
                            <span class="brand-upload-empty">Нет файла</span>
                        @endif
                    </div>
                    <input type="file" name="logo" accept="image/*">
                </div>
                <div class="brand-upload">
                    <span>Favicon</span>
                    <div class="brand-upload-preview">
                        @if (! empty($values['favicon_path']))
                            <img src="{{ \App\Support\Media::url($values['favicon_path']) }}" alt="Favicon">
                        @else
                            <span class="brand-upload-empty">Нет файла</span>
                        @endif
                    </div>
                    <input type="file" name="favicon" accept="image/*">
                </div>
                <div class="brand-upload brand-upload-wide">
                    <span>Горизонтальный логотип</span>
                    <div class="brand-upload-preview brand-upload-preview-wide">
                        @if (! empty($values['logo_horizontal_path']))
                            <img src="{{ \App\Support\Media::url($values['logo_horizontal_path']) }}" alt="Горизонтальный логотип">
                        @else
                            <span class="brand-upload-empty">Нет файла</span>
                        @endif
                    </div>
                    <input type="file" name="logo_horizontal" accept="image/*">
                </div>
            </div>
        </div>
        <div class="span-2"><button class="button" type="submit">Сохранить настройки</button></div>
    </form>
@endsection
