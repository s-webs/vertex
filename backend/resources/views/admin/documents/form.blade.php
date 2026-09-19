@extends('layouts.admin')

@section('title', isset($document) ? $document->title : 'Новый документ')
@section('heading', isset($document) ? $document->title : 'Новый документ')
@section('kicker', 'Файл и превью')

@section('content')
    <form class="admin-form admin-form-grid" method="POST" action="{{ isset($document) ? route('admin.documents.update', $document) : route('admin.documents.store') }}" enctype="multipart/form-data">
        @csrf
        @if (isset($document))
            @method('PUT')
        @endif
        <label class="field span-2">Название *
            <input name="title" value="{{ old('title', $document->title ?? '') }}" required>
        </label>
        <label class="field span-2">Описание
            <textarea name="description">{{ old('description', $document->description ?? '') }}</textarea>
        </label>
        <label class="field">Порядок
            <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $document->sort_order ?? 0) }}">
        </label>
        <input type="hidden" name="is_published" value="0">
        <label class="admin-check"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $document->is_published ?? true))> Опубликован</label>
        <div class="field">
            Файл
            @if (! empty($document?->file_path))
                <p><a class="text-link" href="{{ \App\Support\Media::url($document->file_path) }}" target="_blank" rel="noopener">Текущий файл</a></p>
            @endif
            <input type="file" name="file" accept=".pdf,image/*">
        </div>
        <div class="field">
            Превью
            @if (! empty($document?->preview_path))
                <img class="admin-preview" src="{{ \App\Support\Media::url($document->preview_path) }}" alt="">
            @endif
            <input type="file" name="preview" accept="image/*">
        </div>
        <div class="span-2"><button class="button" type="submit">Сохранить</button></div>
    </form>
@endsection
