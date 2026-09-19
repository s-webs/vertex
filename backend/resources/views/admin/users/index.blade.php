@extends('layouts.admin')

@section('title', 'Администраторы')
@section('heading', 'Администраторы')
@section('kicker', 'Полный доступ к админке')

@section('content')
    <form class="admin-form admin-form-grid" method="POST" action="{{ route('admin.users.store') }}" style="margin-bottom: 28px">
        @csrf
        <label class="field">Имя *
            <input name="name" value="{{ old('name') }}" required>
        </label>
        <label class="field">Email *
            <input type="email" name="email" value="{{ old('email') }}" required>
            @error('email') <span class="field-error">{{ $message }}</span> @enderror
        </label>
        <label class="field">Пароль *
            <input type="password" name="password" required>
            @error('password') <span class="field-error">{{ $message }}</span> @enderror
        </label>
        <div class="field" style="align-self:end"><button class="button" type="submit">Добавить</button></div>
    </form>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Имя</th>
                    <th>Email</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user->is(auth()->user()))
                                <span class="muted">Это вы</span>
                            @else
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Удалить администратора?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-link" type="submit" style="border:0;background:none;padding:0">Удалить</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
