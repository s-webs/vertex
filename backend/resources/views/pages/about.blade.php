@extends('layouts.site')

@section('content')
    <section class="wrap">
        <div class="page-head">
            <div class="breadcrumbs"><a href="{{ route('home') }}">Главная</a><span>/</span><span>О компании</span></div>
            <div class="eyebrow">О компании</div>
            <h1>Поднимаем стандарты.</h1>
            <p>{{ $site->companyName() }} — инженерный подход к установке, модернизации и обслуживанию лифтов.</p>
        </div>
        <div class="split pb-16">
            @if ($sideImage)
                <img class="brand-image" src="{{ $sideImage }}" alt="{{ $sideAlt }}" width="600" height="600">
            @endif
            <div>
                <div class="eyebrow">Наш подход</div>
                <h2>За каждым подъёмом —<br>внимание к деталям.</h2>
                <p>Мы помогаем сделать вертикальное движение удобной частью повседневной жизни. Подбираем решения с учётом назначения здания, нагрузки и архитектуры пространства.</p>
                <p>Сопровождаем оборудование на всём жизненном цикле: от монтажа до регулярного обслуживания и модернизации.</p>
                <div class="check-list">
                    <div><i class="ph ph-target" aria-hidden="true"></i> Задачи объекта — отправная точка проекта</div>
                    <div><i class="ph ph-users-three" aria-hidden="true"></i> Партнёрство и понятная коммуникация</div>
                    <div><i class="ph ph-shield-check" aria-hidden="true"></i> Качество, которое складывается из деталей</div>
                </div>
            </div>
        </div>
    </section>
    <section class="dark-section">
        <div class="wrap section">
            <div class="section-head">
                <div>
                    <div class="eyebrow">Как мы работаем</div>
                    <h2>Последовательно. Прозрачно. По делу.</h2>
                </div>
            </div>
            <div class="steps-grid">
                <div>
                    <span class="step-index">01 /</span>
                    <h3 class="step-title">Изучаем задачу</h3>
                    <p class="step-text">Обсуждаем объект, требования к оборудованию и пожелания к дизайну.</p>
                </div>
                <div>
                    <span class="step-index">02 /</span>
                    <h3 class="step-title">Находим решение</h3>
                    <p class="step-text">Согласовываем комплектацию, этапы работ и организацию монтажа.</p>
                </div>
                <div>
                    <span class="step-index">03 /</span>
                    <h3 class="step-title">Остаёмся рядом</h3>
                    <p class="step-text">Помогаем поддерживать исправность оборудования и планировать его обновление.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="wrap section">
        <div class="section-head">
            <div>
                <h2>Документы компании</h2>
            </div>
            <span class="notice">Официальные документы компании</span>
        </div>
        <div class="document-grid">
            @forelse ($documents as $document)
                @php
                    $href = $document->file_path ? \App\Support\Media::url($document->file_path) : null;
                @endphp
                <{{ $href ? 'a' : 'div' }} class="document document-card" @if ($href) href="{{ $href }}" target="_blank" rel="noopener" @endif>
                    <div class="document-preview">
                        @if ($document->preview_path)
                            <img src="{{ \App\Support\Media::url($document->preview_path) }}" alt="{{ $document->title }}">
                        @else
                            <i class="ph ph-certificate" aria-hidden="true"></i>
                            <span>ДОКУМЕНТ</span>
                        @endif
                    </div>
                    <h3>{{ $document->title }}</h3>
                    <p>{{ $document->description }} <i class="ph ph-arrow-up-right" aria-hidden="true"></i></p>
                </{{ $href ? 'a' : 'div' }}>
            @empty
                <div class="document">
                    <div class="document-preview"><i class="ph ph-certificate" aria-hidden="true"></i><span>МЕСТО ДЛЯ ДОКУМЕНТА</span></div>
                    <h3>Сертификаты соответствия</h3>
                    <p>Документы появятся после загрузки в админке</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection
