@extends('layouts.admin')

@section('title', 'Каталоги')
@section('heading', 'Каталоги')
@section('kicker', 'PDF и обложки производителей')

@section('content')
    <div class="admin-actions">
        <p class="notice" style="margin:0">{{ $catalogs->count() }} записей</p>
        <a class="button" href="{{ route('admin.catalogs.create') }}">Добавить каталог</a>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Каталог</th>
                    <th>Статус</th>
                    <th>Страниц</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($catalogs as $catalog)
                    <tr>
                        <td>
                            <strong>{{ $catalog->title }}</strong><br>
                            <span class="muted">{{ $catalog->makerLabel() }}</span>
                        </td>
                        <td><span class="admin-badge {{ $catalog->is_published ? 'ok' : 'warn' }}">{{ $catalog->is_published ? 'Опубликован' : 'Черновик' }}</span></td>
                        <td>{{ $catalog->page_count }}</td>
                        <td class="row-actions">
                            <a class="text-link" href="{{ route('admin.catalogs.edit', $catalog) }}">Изменить</a>
                            <form method="POST" action="{{ route('admin.catalogs.destroy', $catalog) }}" onsubmit="return confirm('Удалить каталог?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-link" type="submit" style="border:0;background:none;padding:0">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">Каталогов пока нет.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
