@extends('layouts.site')

@section('content')
    <section class="wrap work-detail-page">
        <div class="page-head work-detail-head">
            <div class="breadcrumbs">
                <a href="{{ route('home') }}">Главная</a><span>/</span>
                <a href="{{ route('works.index') }}">Наши работы</a><span>/</span>
                <span>{{ $work->title }}</span>
            </div>
            <div class="eyebrow">{{ $work->number }}</div>
            <h1>{{ $work->title }}</h1>
            <p>{{ $work->description }}</p>
            <div class="work-meta">
                @foreach ($work->metaList() as $item)
                    <span>{{ $item }}</span>
                @endforeach
                <span>{{ $work->photos->count() }} {{ $photosWord }}</span>
            </div>
        </div>
        <div class="work-detail-gallery">
            @foreach ($work->photos as $index => $photo)
                <button type="button" data-gallery-image data-src="{{ \App\Support\Media::url($photo->path) }}" data-caption="{{ $photo->caption }}" aria-label="Открыть фотографию {{ $index + 1 }}: {{ $photo->caption }}">
                    <img src="{{ \App\Support\Media::url($photo->path) }}" alt="{{ $photo->alt ?: $photo->caption }}" loading="lazy">
                    <span>{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }} / {{ $photo->caption }}</span>
                </button>
            @endforeach
        </div>
        <a class="text-link work-back" href="{{ route('works.index') }}"><i class="ph ph-arrow-left" aria-hidden="true"></i> Все объекты</a>
    </section>
@endsection

@section('cta')
    <section class="wrap">
        <div class="cta">
            <div>
                <h2>Обсудим ваш объект?</h2>
                <p>Подберём оборудование и подготовим решение.</p>
            </div>
            <a class="button" href="{{ route('contacts.show') }}">Связаться с нами<i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
        </div>
    </section>
@endsection

@push('modals')
    <dialog class="gallery-modal" id="gallery-modal">
        <button class="gallery-modal-close" type="button" aria-label="Закрыть галерею"><i class="ph ph-x" aria-hidden="true"></i></button>
        <figure>
            <img src="" alt="">
            <figcaption></figcaption>
        </figure>
    </dialog>
@endpush
