@extends('layouts.admin')

@section('title', isset($catalog) ? 'Каталог' : 'Новый каталог')
@section('heading', isset($catalog) ? $catalog->title : 'Новый каталог')
@section('kicker', 'PDF, обложка и SEO')

@section('content')
    <form class="admin-form admin-form-grid" method="POST" action="{{ isset($catalog) ? route('admin.catalogs.update', $catalog) : route('admin.catalogs.store') }}" enctype="multipart/form-data">
        @csrf
        @if (isset($catalog))
            @method('PUT')
        @endif
        <label class="field">Бренд *
            <input name="brand" value="{{ old('brand', $catalog->brand ?? '') }}" required>
        </label>
        <label class="field">Год
            <input name="year" value="{{ old('year', $catalog->year ?? '') }}">
        </label>
        <label class="field span-2">Название *
            <input name="title" value="{{ old('title', $catalog->title ?? '') }}" required>
        </label>
        <label class="field">Slug
            <input name="slug" value="{{ old('slug', $catalog->slug ?? '') }}" placeholder="Авто из названия">
        </label>
        <label class="field">Количество страниц
            <input type="number" min="0" name="page_count" value="{{ old('page_count', $catalog->page_count ?? 0) }}">
        </label>
        <label class="field span-2">Описание
            <textarea name="description">{{ old('description', $catalog->description ?? '') }}</textarea>
        </label>
        <label class="field">Теги через запятую
            <input name="tags" value="{{ old('tags', isset($catalog) ? implode(', ', $catalog->tagList()) : '') }}">
        </label>
        <label class="field">Порядок
            <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $catalog->sort_order ?? 0) }}">
        </label>
        <input type="hidden" name="is_published" value="0">
        <label class="admin-check span-2"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $catalog->is_published ?? true))> Опубликован</label>
        <div class="field">
            Обложка
            @if (! empty($catalog?->cover_path))
                <img class="admin-preview" src="{{ \App\Support\Media::url($catalog->cover_path) }}" alt="">
            @endif
            <input type="file" name="cover" accept="image/*">
            @error('cover') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            PDF
            @if (! empty($catalog?->pdf_path))
                <p><a class="text-link" href="{{ \App\Support\Media::url($catalog->pdf_path) }}" target="_blank" rel="noopener">Текущий файл</a></p>
            @endif
            <input type="file" name="pdf" accept="application/pdf">
            @error('pdf') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        @include('admin.partials.seo-fields', [
            'meta' => $meta ?? null,
            'fallbackTitle' => ($catalog->title ?? 'Каталог').' — '.$site->companyName(),
            'fallbackDescription' => $catalog->description ?? '',
        ])
        <div class="span-2"><button class="button" type="submit">Сохранить</button></div>
    </form>
@endsection
