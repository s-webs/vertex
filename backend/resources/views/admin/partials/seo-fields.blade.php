@php
    $meta = $meta ?? null;
    $fallbackTitle = $fallbackTitle ?? '';
    $fallbackDescription = $fallbackDescription ?? '';
@endphp
<div class="span-2">
    <div class="eyebrow">SEO</div>
    <div class="seo-preview" aria-hidden="true">
        <div class="seo-preview-url">{{ url('/') }}</div>
        <div class="seo-preview-title" data-seo-preview-title>{{ old('seo_title', $meta?->title) ?: $fallbackTitle }}</div>
        <div class="seo-preview-desc" data-seo-preview-description>{{ old('seo_description', $meta?->description) ?: $fallbackDescription }}</div>
    </div>
</div>
<label class="field span-2">Title
    <input name="seo_title" data-seo-title data-fallback="{{ $fallbackTitle }}" value="{{ old('seo_title', $meta?->title) }}" maxlength="70" placeholder="{{ $fallbackTitle }}">
    @error('seo_title') <span class="field-error">{{ $message }}</span> @enderror
</label>
<label class="field span-2">Meta description
    <textarea name="seo_description" data-seo-description data-fallback="{{ $fallbackDescription }}" maxlength="180" placeholder="{{ $fallbackDescription }}">{{ old('seo_description', $meta?->description) }}</textarea>
    @error('seo_description') <span class="field-error">{{ $message }}</span> @enderror
</label>
<label class="field">OG title
    <input name="og_title" value="{{ old('og_title', $meta?->og_title) }}" maxlength="70">
</label>
<label class="field">Canonical URL
    <input name="canonical" value="{{ old('canonical', $meta?->canonical) }}" placeholder="Автоматически, если пусто">
    @error('canonical') <span class="field-error">{{ $message }}</span> @enderror
</label>
<label class="field span-2">OG description
    <textarea name="og_description" maxlength="180">{{ old('og_description', $meta?->og_description) }}</textarea>
</label>
<label class="field">Robots
    <input name="robots" value="{{ old('robots', $meta?->robots ?? 'index,follow') }}">
</label>
<div class="field">
    OG-изображение
    @if ($meta?->og_image)
        <img class="admin-preview" src="{{ \App\Support\Media::url($meta->og_image) }}" alt="">
        <label class="admin-check"><input type="checkbox" name="remove_og_image" value="1"> Удалить изображение</label>
    @endif
    <input type="file" name="og_image" accept="image/*">
    @error('og_image') <span class="field-error">{{ $message }}</span> @enderror
</div>
