@extends('layouts.admin')

@section('title', 'SEO')
@section('heading', 'SEO')
@section('kicker', 'Мета-теги, верификация и сниппеты')

@section('content')
    <form class="admin-form admin-form-grid" method="POST" action="{{ route('admin.seo.globals') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="span-2"><div class="eyebrow">Глобальные настройки</div></div>
        <label class="field">Google Search Console
            <input name="google_site_verification" value="{{ old('google_site_verification', $values['google_site_verification'] ?? '') }}">
        </label>
        <label class="field">Yandex Webmaster
            <input name="yandex_verification" value="{{ old('yandex_verification', $values['yandex_verification'] ?? '') }}">
        </label>
        <label class="field span-2">Дополнительно в robots.txt
            <textarea name="robots_extra">{{ old('robots_extra', $values['robots_extra'] ?? '') }}</textarea>
        </label>
        <div class="field span-2">
            OG-изображение по умолчанию
            @if (! empty($values['og_image_path']))
                <img class="admin-preview" src="{{ \App\Support\Media::url($values['og_image_path']) }}" alt="">
            @endif
            <input type="file" name="og_image" accept="image/*">
        </div>
        <div class="span-2"><button class="button" type="submit">Сохранить глобальные SEO</button></div>
    </form>

    <h2 style="margin: 36px 0 16px; font-size: 28px;">Страницы сайта</h2>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Страница</th>
                    <th>Title</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pages as $key => $page)
                    <tr>
                        <td>{{ $page['label'] }}</td>
                        <td>{{ $metas[$key]->title ?? $page['fallback_title'] }}</td>
                        <td><a class="text-link" href="{{ route('admin.seo.edit', $key) }}">Изменить</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
