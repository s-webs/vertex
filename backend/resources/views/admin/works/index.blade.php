@extends('layouts.admin')

@section('title', 'Портфолио')
@section('heading', 'Портфолио')
@section('kicker', 'Наши работы')

@section('content')
    <div class="admin-actions">
        <p class="notice" style="margin:0">{{ $works->count() }} объектов</p>
        <a class="button" href="{{ route('admin.works.create') }}">Добавить объект</a>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Объект</th>
                    <th>Статус</th>
                    <th>Фото</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($works as $work)
                    <tr>
                        <td>
                            <strong>{{ $work->title }}</strong><br>
                            <span class="muted">{{ $work->number }} · {{ $work->slug }}</span>
                        </td>
                        <td><span class="admin-badge {{ $work->is_published ? 'ok' : 'warn' }}">{{ $work->is_published ? 'Опубликован' : 'Черновик' }}</span></td>
                        <td>{{ $work->photos_count }}</td>
                        <td class="row-actions">
                            <a class="text-link" href="{{ route('admin.works.edit', $work) }}">Изменить</a>
                            <form method="POST" action="{{ route('admin.works.destroy', $work) }}" onsubmit="return confirm('Удалить объект?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-link" type="submit" style="border:0;background:none;padding:0">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">Объектов пока нет.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
