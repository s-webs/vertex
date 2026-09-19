<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $seo->title }}</title>
    <meta name="description" content="{{ $seo->description }}">
    <meta name="robots" content="{{ $seo->robots }}">
    <link rel="canonical" href="{{ $seo->canonical }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="ru_RU">
    <meta property="og:site_name" content="{{ $site->companyName() }}">
    <meta property="og:title" content="{{ $seo->ogTitle }}">
    <meta property="og:description" content="{{ $seo->ogDescription }}">
    <meta property="og:url" content="{{ $seo->canonical }}">
    @if ($seo->ogImage)
        <meta property="og:image" content="{{ $seo->ogImage }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:image" content="{{ $seo->ogImage }}">
    @else
        <meta name="twitter:card" content="summary">
    @endif
    <meta name="twitter:title" content="{{ $seo->ogTitle }}">
    <meta name="twitter:description" content="{{ $seo->ogDescription }}">
    <meta name="theme-color" content="#282c26">
    @if ($site->faviconUrl())
        <link rel="icon" type="image/png" href="{{ $site->faviconUrl() }}">
    @endif
    @if ($site->get('google_site_verification'))
        <meta name="google-site-verification" content="{{ $site->get('google_site_verification') }}">
    @endif
    @if ($site->get('yandex_verification'))
        <meta name="yandex-verification" content="{{ $site->get('yandex_verification') }}">
    @endif
    @vite(['resources/css/site.css', 'resources/js/site.js'])
    <script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
</head>
<body data-brand="{{ $site->companyName() }}">
    <a href="#main" class="sr-only">Перейти к содержимому</a>
    <div class="topbar">
        <div class="wrap">
            <span>Инженерия движения. Качество на каждом уровне.</span>
            <div class="topbar-links">
                @if ($site->primaryPhone())
                    <a href="{{ $site->telHref($site->primaryPhone()['number']) }}" aria-label="Позвонить">
                        <i class="ph-thin ph-phone" aria-hidden="true"></i> {{ $site->primaryPhone()['number'] }}
                    </a>
                @endif
                @if ($site->whatsappUrl())
                    <a class="whatsapp-link" href="{{ $site->whatsappUrl() }}" target="_blank" rel="noopener noreferrer" aria-label="Написать в WhatsApp">
                        <i class="ph-thin ph-whatsapp-logo" aria-hidden="true"></i> WhatsApp
                    </a>
                @endif
                @if ($site->instagramUrl())
                    <a href="{{ $site->instagramUrl() }}" target="_blank" rel="noopener noreferrer" aria-label="Открыть Instagram">
                        <i class="ph-thin ph-instagram-logo" aria-hidden="true"></i> Instagram
                    </a>
                @endif
            </div>
        </div>
    </div>
    <div class="wrap">
        <header class="header">
            <a class="brand" href="{{ route('home') }}" aria-label="{{ $site->companyName() }} — главная">
                @if ($site->logoHorizontalUrl())
                    <img class="brand-logo" src="{{ $site->logoHorizontalUrl() }}" alt="Логотип {{ $site->companyName() }}">
                @else
                    <i class="brand-symbol ph ph-elevator" aria-hidden="true"></i>
                    <span><b>{{ $site->companyName() }}</b></span>
                @endif
            </a>
            <nav class="nav" aria-label="Основная навигация">
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}" @if ($item['active']) class="active" aria-current="page" @endif>{{ $item['label'] }}</a>
                @endforeach
            </nav>
            <a class="button outline" href="{{ route('contacts.show') }}">Обсудить проект<i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
            <button class="mobile-toggle" type="button" aria-label="Открыть меню" aria-expanded="false"><i class="ph ph-list" aria-hidden="true"></i></button>
        </header>
    </div>
    <main id="main">
        @yield('content')
    </main>
    @hasSection('cta')
        @yield('cta')
    @else
        <section class="wrap">
            <div class="cta">
                <div>
                    <h2>Ваш следующий уровень — с {{ $site->companyName() }}.</h2>
                    <p>Обсудим задачу и найдём подходящее решение.</p>
                </div>
                <a class="button" href="{{ route('contacts.show') }}">Обсудить проект<i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
            </div>
        </section>
    @endif
    <footer class="footer">
        <div class="wrap">
            <div class="footer-grid">
                <div>
                    <a class="brand" href="{{ route('home') }}" aria-label="{{ $site->companyName() }} — главная">
                        @if ($site->logoUrl())
                            <img class="brand-logo" src="{{ $site->logoUrl() }}" alt="Логотип {{ $site->companyName() }}">
                        @else
                            <i class="brand-symbol ph ph-elevator" aria-hidden="true"></i>
                            <span><b>{{ $site->companyName() }}</b></span>
                        @endif
                    </a>
                    <p>Установка, модернизация и обслуживание лифтов. Внимание к каждой детали вашего движения.</p>
                </div>
                <div>
                    <h3>Навигация</h3>
                    <div class="footer-links">
                        @foreach ($navItems as $item)
                            <a href="{{ route($item['route']) }}">{{ $item['label'] }}</a>
                        @endforeach
                    </div>
                </div>
                <div>
                    <h3>Контакты</h3>
                    <div class="footer-links footer-contacts">
                        @foreach ($site->phones() as $phone)
                            <a href="{{ $site->telHref($phone['number']) }}">
                                <i class="ph-thin ph-phone" aria-hidden="true"></i> {{ $phone['number'] }}
                            </a>
                        @endforeach
                        @if ($site->email())
                            <a href="mailto:{{ $site->email() }}">
                                <i class="ph-thin ph-envelope-simple" aria-hidden="true"></i> {{ $site->email() }}
                            </a>
                        @endif
                        @if ($site->address())
                            <span>
                                <i class="ph-thin ph-map-pin" aria-hidden="true"></i> {{ $site->address() }}
                            </span>
                        @endif
                        @if ($site->whatsappUrl())
                            <a class="whatsapp-link" href="{{ $site->whatsappUrl() }}" target="_blank" rel="noopener noreferrer">
                                <i class="ph-thin ph-whatsapp-logo" aria-hidden="true"></i> WhatsApp
                            </a>
                        @endif
                        @if ($site->instagramUrl())
                            <a href="{{ $site->instagramUrl() }}" target="_blank" rel="noopener noreferrer">
                                <i class="ph-thin ph-instagram-logo" aria-hidden="true"></i> Instagram
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <span>© {{ now()->year }} {{ $site->companyName() }}</span>
            </div>
        </div>
    </footer>
    @stack('modals')
</body>
</html>
