@extends('layouts.admin')

@section('title', 'Документы')
@section('heading', 'Документы')
@section('kicker', 'Сертификаты и награды')

@section('content')
    <div class="admin-actions">
        <span></span>
        <a class="button" href="{{ route('admin.documents.create') }}">Добавить документ</a>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Документ</th>
                    <th>Статус</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($documents as $document)
                    <tr>
                        <td>{{ $document->title }}</td>
                        <td><span class="admin-badge {{ $document->is_published ? 'ok' : 'warn' }}">{{ $document->is_published ? 'Опубликован' : 'Черновик' }}</span></td>
                        <td class="row-actions">
                            <a class="text-link" href="{{ route('admin.documents.edit', $document) }}">Изменить</a>
                            <form method="POST" action="{{ route('admin.documents.destroy', $document) }}" onsubmit="return confirm('Удалить документ?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-link" type="submit" style="border:0;background:none;padding:0">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3">Документов пока нет.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
