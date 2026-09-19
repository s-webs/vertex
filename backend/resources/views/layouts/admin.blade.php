<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>@yield('title', 'Админка') — {{ $site->companyName() }}</title>
    @if ($site->faviconUrl())
        <link rel="icon" type="image/png" href="{{ $site->faviconUrl() }}">
    @endif
    @vite(['resources/css/site.css', 'resources/css/admin.css', 'resources/js/admin.js'])
</head>
<body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <a class="brand" href="{{ route('admin.dashboard') }}">
                @if ($site->logoHorizontalUrl())
                    <img class="brand-logo" src="{{ $site->logoHorizontalUrl() }}" alt="{{ $site->companyName() }}">
                @else
                    <span><b>{{ $site->companyName() }}</b></span>
                @endif
            </a>
            <nav class="admin-nav" aria-label="Админка">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="ph ph-squares-four" aria-hidden="true"></i> Дашборд</a>
                <a href="{{ route('admin.settings.edit') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"><i class="ph ph-gear" aria-hidden="true"></i> Настройки</a>
                <a href="{{ route('admin.page-images.index') }}" class="{{ request()->routeIs('admin.page-images.*') ? 'active' : '' }}"><i class="ph ph-image" aria-hidden="true"></i> Изображения</a>
                <a href="{{ route('admin.catalogs.index') }}" class="{{ request()->routeIs('admin.catalogs.*') ? 'active' : '' }}"><i class="ph ph-files" aria-hidden="true"></i> Каталоги</a>
                <a href="{{ route('admin.works.index') }}" class="{{ request()->routeIs('admin.works.*') ? 'active' : '' }}"><i class="ph ph-buildings" aria-hidden="true"></i> Портфолио</a>
                <a href="{{ route('admin.seo.index') }}" class="{{ request()->routeIs('admin.seo.*') ? 'active' : '' }}"><i class="ph ph-magnifying-glass" aria-hidden="true"></i> SEO</a>
                <a href="{{ route('admin.inquiries.index') }}" class="{{ request()->routeIs('admin.inquiries.*') ? 'active' : '' }}"><i class="ph ph-envelope-simple" aria-hidden="true"></i> Заявки @if ($unreadInquiries) ({{ $unreadInquiries }}) @endif</a>
                <a href="{{ route('admin.documents.index') }}" class="{{ request()->routeIs('admin.documents.*') ? 'active' : '' }}"><i class="ph ph-certificate" aria-hidden="true"></i> Документы</a>
                <a href="{{ route('admin.redirects.index') }}" class="{{ request()->routeIs('admin.redirects.*') ? 'active' : '' }}"><i class="ph ph-arrow-bend-up-right" aria-hidden="true"></i> Редиректы</a>
                <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="ph ph-users" aria-hidden="true"></i> Администраторы</a>
            </nav>
            <div class="admin-sidebar-foot">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button class="button outline light w-full" type="submit">Выйти</button>
                </form>
            </div>
        </aside>
        <div class="admin-main">
            <header class="admin-top">
                <div>
                    <h1>@yield('heading')</h1>
                    <span class="muted">@yield('kicker', 'Управление сайтом')</span>
                </div>
                <a class="text-link" href="{{ url('/') }}" target="_blank" rel="noopener">Открыть сайт <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
            </header>
            <div class="admin-content">
                @if (session('status'))
                    <div class="alert alert-success" role="status">{{ session('status') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-error">
                        Проверьте поля формы.
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>
