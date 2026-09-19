@extends('layouts.admin')

@section('title', isset($work) ? $work->title : 'Новый объект')
@section('heading', isset($work) ? $work->title : 'Новый объект')
@section('kicker', 'Карточка и галерея')

@section('content')
    <form class="admin-form admin-form-grid" method="POST" action="{{ isset($work) ? route('admin.works.update', $work) : route('admin.works.store') }}" enctype="multipart/form-data">
        @csrf
        @if (isset($work))
            @method('PUT')
        @endif
        <label class="field">Номер
            <input name="number" value="{{ old('number', $work->number ?? '') }}" placeholder="ОБЪЕКТ 01">
        </label>
        <label class="field">Slug
            <input name="slug" value="{{ old('slug', $work->slug ?? '') }}">
        </label>
        <label class="field span-2">Название *
            <input name="title" value="{{ old('title', $work->title ?? '') }}" required>
        </label>
        <label class="field span-2">Описание
            <textarea name="description">{{ old('description', $work->description ?? '') }}</textarea>
        </label>
        <label class="field">Теги через запятую
            <input name="meta" value="{{ old('meta', isset($work) ? implode(', ', $work->metaList()) : '') }}">
        </label>
        <label class="field">Порядок
            <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $work->sort_order ?? 0) }}">
        </label>
        <input type="hidden" name="is_published" value="0">
        <label class="admin-check span-2"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $work->is_published ?? true))> Опубликован</label>
        @include('admin.partials.seo-fields', [
            'meta' => $meta ?? null,
            'fallbackTitle' => ($work->title ?? 'Объект').' — '.$site->companyName(),
            'fallbackDescription' => $work->description ?? '',
        ])
        <div class="span-2"><button class="button" type="submit">Сохранить объект</button></div>
    </form>

    @if (isset($work))
        <h2 style="margin: 40px 0 16px; font-size: 28px;">Галерея</h2>
        <form class="admin-form admin-form-grid" method="POST" action="{{ route('admin.works.photos.store', $work) }}" enctype="multipart/form-data">
            @csrf
            <div class="field">
                Фото *
                <input type="file" name="photo" accept="image/*" required>
                @error('photo') <span class="field-error">{{ $message }}</span> @enderror
            </div>
            <label class="field">Alt *
                <input name="alt" value="{{ old('alt') }}" required>
            </label>
            <label class="field span-2">Подпись
                <input name="caption" value="{{ old('caption') }}">
            </label>
            <div class="span-2"><button class="button outline" type="submit">Добавить фото</button></div>
        </form>
        <div class="photo-grid">
            @foreach ($work->photos as $photo)
                <div class="photo-card">
                    <img src="{{ \App\Support\Media::url($photo->path) }}" alt="{{ $photo->alt }}">
                    <p>{{ $photo->caption }}</p>
                    <div class="row-actions">
                        <form method="POST" action="{{ route('admin.works.photos.move', [$work, $photo]) }}">
                            @csrf
                            <input type="hidden" name="direction" value="up">
                            <button class="text-link" type="submit" style="border:0;background:none;padding:0">Выше</button>
                        </form>
                        <form method="POST" action="{{ route('admin.works.photos.move', [$work, $photo]) }}">
                            @csrf
                            <input type="hidden" name="direction" value="down">
                            <button class="text-link" type="submit" style="border:0;background:none;padding:0">Ниже</button>
                        </form>
                        <form method="POST" action="{{ route('admin.works.photos.destroy', [$work, $photo]) }}" onsubmit="return confirm('Удалить фото?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-link" type="submit" style="border:0;background:none;padding:0">Удалить</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
