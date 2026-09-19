@extends('layouts.admin')

@section('title', 'Изображения страниц')
@section('heading', 'Изображения страниц')
@section('kicker', 'Слоты баннеров и иллюстраций')

@section('content')
    @foreach ($slots as $pageKey => $pageSlots)
        <h2 style="margin: 8px 0 18px; font-size: 28px;">{{ config("site.pages.$pageKey.label", $pageKey) }}</h2>
        @foreach ($pageSlots as $slotKey => $slot)
            @php $image = $images[$pageKey][$slotKey] ?? null; @endphp
            <form class="admin-form admin-form-grid" method="POST" action="{{ route('admin.page-images.update') }}" enctype="multipart/form-data" style="margin-bottom: 28px;">
                @csrf
                @method('PUT')
                <input type="hidden" name="page_key" value="{{ $pageKey }}">
                <input type="hidden" name="slot_key" value="{{ $slotKey }}">
                <div class="field">
                    {{ $slot['label'] }}
                    @if ($image?->path)
                        <img class="admin-preview tall" src="{{ \App\Support\Media::url($image->path) }}" alt="{{ $image->alt }}">
                    @endif
                    <input type="file" name="image" accept="image/*">
                </div>
                <label class="field">Alt-текст *
                    <input name="alt" value="{{ old('alt', $image?->alt ?? $slot['alt']) }}" required>
                    @error('alt') <span class="field-error">{{ $message }}</span> @enderror
                </label>
                <div class="span-2"><button class="button" type="submit">Сохранить слот</button></div>
            </form>
        @endforeach
    @endforeach
@endsection
