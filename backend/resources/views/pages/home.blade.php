@extends('layouts.site')

@section('content')
    <section class="wrap hero">
        <div class="hero-copy">
            <div class="eyebrow">Технологии, которые поднимают</div>
            <h1>Новый уровень<br>комфорта.<br><em>Высший стандарт<br>надёжности.</em></h1>
            <p>Решения для зданий и людей.<br>От первого проекта до ежедневного обслуживания — рядом на каждом этаже.</p>
            <div class="hero-actions">
                <a class="button" href="{{ route('catalog') }}">Подобрать лифт<i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
                <a class="button outline" href="{{ route('about') }}">О компании<i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
            </div>
            <div class="hero-note"><i class="ph ph-shield-check" aria-hidden="true"></i> Полный цикл: проектирование, монтаж, сервис</div>
        </div>
        <div class="hero-visual">
            <img src="{{ $heroImage }}" alt="{{ $heroAlt }}" fetchpriority="high">
            <span class="image-tag">{{ $site->companyName() }} / ВЫШЕ ОЖИДАНИЙ</span>
            <div class="photo-label">
                <div>
                    <small>Создаём движение вверх</small>
                    <strong>Инженерия вашего комфорта</strong>
                </div>
                <i class="ph ph-arrow-up-right" aria-hidden="true"></i>
            </div>
        </div>
    </section>
    <div class="wrap">
        <div class="stats">
            <div class="stat"><strong>Полный цикл</strong><span>От проекта до обслуживания</span></div>
            <div class="stat"><strong>Точный подход</strong><span>Решения под ваше здание</span></div>
            <div class="stat"><strong>Безопасность</strong><span>В центре каждого решения</span></div>
            <div class="stat"><strong>На связи</strong><span>Поддержка на всех этапах</span></div>
        </div>
    </div>
    <section class="wrap section">
        <div class="section-head">
            <div>
                <h2 class="home-services-title">Движение вверх начинается<br>с правильного партнёра.</h2>
            </div>
            <a class="text-link" href="{{ route('works.index') }}">Наши работы <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
        </div>
        <div class="service-grid">
            <article class="service">
                <i class="ph ph-elevator" aria-hidden="true"></i>
                <h3>Установка лифтов</h3>
                <p>От подбора оборудования до монтажа и ввода в эксплуатацию.</p>
            </article>
            <article class="service">
                <i class="ph ph-wrench" aria-hidden="true"></i>
                <h3>Сервисное обслуживание</h3>
                <p>Плановый уход и диагностика для надёжной работы оборудования.</p>
            </article>
            <article class="service">
                <i class="ph ph-arrows-clockwise" aria-hidden="true"></i>
                <h3>Модернизация</h3>
                <p>Новые возможности, современный комфорт и обновлённый дизайн.</p>
            </article>
        </div>
    </section>
    <section class="dark-section">
        <div class="wrap section split">
            <div>
                <div class="eyebrow">Философия {{ $site->companyName() }}</div>
                <h2>Высокие стандарты.<br>Плавное движение.</h2>
                <p>Хороший лифт незаметен: он просто работает. Мы объединяем инженерный подход, продуманный дизайн и внимательный сервис, чтобы так было каждый день.</p>
                <a class="button" href="{{ route('about') }}">Знакомство с компанией<i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
            </div>
            <div class="check-list">
                <div><i class="ph ph-ruler" aria-hidden="true"></i> Решения с учётом архитектуры и задач объекта</div>
                <div><i class="ph ph-gear-six" aria-hidden="true"></i> Внимание к качеству на каждом этапе монтажа</div>
                <div><i class="ph ph-handshake" aria-hidden="true"></i> Понятное взаимодействие на всём пути</div>
                <div><i class="ph ph-shield-check" aria-hidden="true"></i> Регулярная диагностика и профилактика</div>
            </div>
        </div>
    </section>
@endsection
