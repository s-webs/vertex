@extends('layouts.site')

@section('content')
    <section class="wrap">
        <div class="page-head">
            <div class="breadcrumbs"><a href="{{ route('home') }}">Главная</a><span>/</span><span>Наши работы</span></div>
            <div class="eyebrow">Портфолио</div>
            <h1>Наши работы.</h1>
            <p>Каждый объект — отдельная инженерная задача. Откройте галерею, чтобы посмотреть детали реализации.</p>
        </div>
        <div class="works-list">
            @forelse ($works as $work)
                <article class="work-object">
                    <div class="work-object-head">
                        <div>
                            <span class="catalog-maker">{{ $work->number }}</span>
                            <h2><a href="{{ route('works.show', $work) }}">{{ $work->title }}</a></h2>
                        </div>
                        <a class="text-link" href="{{ route('works.show', $work) }}">Смотреть объект <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
                    </div>
                    <div class="work-gallery" aria-label="Галерея объекта: {{ $work->title }}">
                        @foreach ($work->photos->take(3) as $photo)
                            <button type="button" data-gallery-image data-src="{{ \App\Support\Media::url($photo->path) }}" data-caption="{{ $work->title }} — {{ $photo->caption }}">
                                <img src="{{ \App\Support\Media::url($photo->path) }}" alt="{{ $photo->alt ?: $photo->caption }}" loading="lazy">
                                <span>{{ $photo->caption }}</span>
                            </button>
                        @endforeach
                    </div>
                </article>
            @empty
                <p class="notice">Объекты портфолио появятся после публикации в админке.</p>
            @endforelse
        </div>
    </section>
@endsection

@section('cta')
    <section class="wrap">
        <div class="cta">
            <div>
                <h2>Есть объект для нового проекта?</h2>
                <p>Обсудим задачу и найдём подходящее решение.</p>
            </div>
            <a class="button" href="{{ route('contacts.show') }}">Обсудить проект<i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
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
