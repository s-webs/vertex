@extends('layouts.site')

@section('content')
    <section class="wrap">
        <div class="page-head">
            <div class="breadcrumbs"><a href="{{ route('home') }}">Главная</a><span>/</span><span>Каталоги</span></div>
            <div class="eyebrow">Каталоги оборудования</div>
            <h1>Решения для каждого уровня.</h1>
            <p>Для жилых домов, деловых пространств и специальных задач. Выберите направление — подберём оборудование под ваш проект.</p>
        </div>
        <section class="catalog-library" aria-labelledby="catalog-library-title">
            <div class="section-head">
                <div>
                    <div class="eyebrow">Документация производителей</div>
                    <h2 id="catalog-library-title">Каталоги лифтов</h2>
                </div>
                <span class="notice">{{ $catalogs->count() }} каталогов · {{ $catalogs->sum('page_count') }} страниц</span>
            </div>
            @if ($catalogs->isEmpty())
                <p class="notice">Каталоги появятся после публикации в админке.</p>
            @else
                <div class="catalog-doc-grid">
                    @foreach ($catalogs as $catalog)
                        <article class="catalog-doc">
                            @if ($catalog->cover_path)
                                <img src="{{ \App\Support\Media::url($catalog->cover_path) }}" alt="Обложка каталога {{ $catalog->title }}" loading="lazy">
                            @endif
                            <div class="catalog-doc-body">
                                <span class="catalog-maker">{{ $catalog->makerLabel() }}</span>
                                <h3>{{ $catalog->title }}</h3>
                                <p>{{ $catalog->description }}</p>
                                <div class="catalog-tags">
                                    @foreach ($catalog->tagList() as $tag)
                                        <span>{{ $tag }}</span>
                                    @endforeach
                                    @if ($catalog->page_count)
                                        <span>{{ $catalog->page_count }} страниц</span>
                                    @endif
                                </div>
                                @if ($catalog->pdf_path)
                                    <a class="text-link" href="{{ \App\Support\Media::url($catalog->pdf_path) }}" target="_blank" rel="noopener">Открыть PDF <i class="ph ph-file-pdf" aria-hidden="true"></i></a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    </section>
@endsection
