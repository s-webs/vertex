@extends('layouts.admin')

@section('title', 'Дашборд')
@section('heading', 'Дашборд')
@section('kicker', 'Обзор сайта')

@section('content')
    <div class="admin-stats">
        <div class="admin-stat"><strong>{{ $catalogsCount }}</strong><span>Каталогов</span></div>
        <div class="admin-stat"><strong>{{ $worksCount }}</strong><span>Объектов</span></div>
        <div class="admin-stat"><strong>{{ $newInquiries }}</strong><span>Новых заявок</span></div>
        <div class="admin-stat"><strong>{{ $documentsCount }}</strong><span>Документов</span></div>
    </div>
    <p><a class="button" href="{{ url('/') }}" target="_blank" rel="noopener">Открыть публичный сайт</a></p>
@endsection
