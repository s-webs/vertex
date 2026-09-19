<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title>Вход — {{ $site->companyName() }}</title>
    @if ($site->faviconUrl())
        <link rel="icon" type="image/png" href="{{ $site->faviconUrl() }}">
    @endif
    @vite(['resources/css/site.css', 'resources/css/admin.css'])
</head>
<body class="admin-login">
    <div class="admin-login-card">
        @if ($site->logoHorizontalUrl())
            <img class="brand-logo" src="{{ $site->logoHorizontalUrl() }}" alt="{{ $site->companyName() }}">
        @endif
        <div class="eyebrow">Админка</div>
        <h1>Вход в панель</h1>
        <p>Управление сайтом {{ $site->companyName() }}.</p>
        <form method="POST" action="{{ route('admin.login.store') }}">
            @csrf
            <label class="field">Email
                <input type="email" name="email" value="{{ old('email') }}" autocomplete="username" required>
                @error('email') <span class="field-error">{{ $message }}</span> @enderror
            </label>
            <label class="field">Пароль
                <input type="password" name="password" autocomplete="current-password" required>
            </label>
            <label class="admin-check">
                <input type="checkbox" name="remember" value="1"> Запомнить меня
            </label>
            <button class="button w-full" type="submit">Войти <i class="ph ph-arrow-up-right" aria-hidden="true"></i></button>
        </form>
    </div>
</body>
</html>
