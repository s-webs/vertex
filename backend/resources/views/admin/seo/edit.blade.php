@extends('layouts.admin')

@section('title', 'SEO: '.$page['label'])
@section('heading', 'SEO: '.$page['label'])
@section('kicker', 'Мета-теги страницы')

@section('content')
    <form class="admin-form admin-form-grid" method="POST" action="{{ route('admin.seo.update', $pageKey) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.partials.seo-fields', [
            'meta' => $meta,
            'fallbackTitle' => $page['fallback_title'],
            'fallbackDescription' => $page['fallback_description'],
        ])
        <div class="span-2"><button class="button" type="submit">Сохранить SEO</button></div>
    </form>
@endsection
