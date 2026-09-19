@extends('layouts.admin')

@section('title', 'Заявка')
@section('heading', $inquiry->name)
@section('kicker', $inquiry->created_at->format('d.m.Y H:i'))

@section('content')
    <p><strong>Телефон:</strong> {{ $inquiry->phone }}</p>
    <p><strong>Услуга:</strong> {{ $inquiry->service }}</p>
    <p><strong>Статус:</strong> {{ $inquiry->status->value === 'new' ? 'Новая' : 'Прочитана' }}</p>
    <div class="inquiry-body">{{ $inquiry->message ?: 'Без комментария' }}</div>
    <div class="row-actions" style="margin-top: 18px">
        @if ($inquiry->status->value === 'new')
            <form method="POST" action="{{ route('admin.inquiries.read', $inquiry) }}">
                @csrf
                @method('PUT')
                <button class="button" type="submit">Отметить прочитанной</button>
            </form>
        @endif
        <form method="POST" action="{{ route('admin.inquiries.destroy', $inquiry) }}" onsubmit="return confirm('Удалить заявку?')">
            @csrf
            @method('DELETE')
            <button class="button outline" type="submit">Удалить</button>
        </form>
    </div>
@endsection
