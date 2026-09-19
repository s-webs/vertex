@extends('layouts.admin')

@section('title', 'Заявки')
@section('heading', 'Заявки')
@section('kicker', 'Форма на странице контактов')

@section('content')
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Дата</th>
                    <th>Имя</th>
                    <th>Телефон</th>
                    <th>Услуга</th>
                    <th>Статус</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($inquiries as $inquiry)
                    <tr>
                        <td>{{ $inquiry->created_at->format('d.m.Y H:i') }}</td>
                        <td>{{ $inquiry->name }}</td>
                        <td>{{ $inquiry->phone }}</td>
                        <td>{{ $inquiry->service }}</td>
                        <td><span class="admin-badge {{ $inquiry->status->value === 'new' ? 'warn' : 'ok' }}">{{ $inquiry->status->value === 'new' ? 'Новая' : 'Прочитана' }}</span></td>
                        <td class="row-actions">
                            <a class="text-link" href="{{ route('admin.inquiries.show', $inquiry) }}">Открыть</a>
                            <form method="POST" action="{{ route('admin.inquiries.destroy', $inquiry) }}" onsubmit="return confirm('Удалить заявку?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-link" type="submit" style="border:0;background:none;padding:0">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">Заявок пока нет.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
