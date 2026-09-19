@extends('layouts.admin')

@section('title', 'Редиректы')
@section('heading', 'Редиректы')
@section('kicker', '301 и 302 для SEO')

@section('content')
    <form class="admin-form admin-form-grid" method="POST" action="{{ route('admin.redirects.store') }}" style="margin-bottom: 28px">
        @csrf
        <label class="field">Откуда *
            <input name="from_path" value="{{ old('from_path') }}" placeholder="/old-page" required>
            @error('from_path') <span class="field-error">{{ $message }}</span> @enderror
        </label>
        <label class="field">Куда *
            <input name="to_path" value="{{ old('to_path') }}" placeholder="/new-page" required>
        </label>
        <label class="field">Статус
            <select name="status">
                <option value="301">301</option>
                <option value="302">302</option>
            </select>
        </label>
        <div class="field" style="align-self:end"><button class="button" type="submit">Добавить</button></div>
    </form>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Откуда</th>
                    <th>Куда</th>
                    <th>Статус</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($redirects as $redirect)
                    <tr>
                        <td>{{ $redirect->from_path }}</td>
                        <td>{{ $redirect->to_path }}</td>
                        <td>{{ $redirect->status }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.redirects.destroy', $redirect) }}" onsubmit="return confirm('Удалить редирект?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-link" type="submit" style="border:0;background:none;padding:0">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">Редиректов нет.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
