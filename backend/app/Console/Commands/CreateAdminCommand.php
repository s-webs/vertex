<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

#[Signature('admin:create {name : Имя администратора} {email : Email для входа} {password : Пароль в кавычках, если есть пробелы или спецсимволы} {--update : Обновить имя и пароль, если email уже есть}')]
#[Description('Создать администратора сайта')]
class CreateAdminCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $data = [
            'name' => $this->argument('name'),
            'email' => $this->argument('email'),
            'password' => $this->argument('password'),
        ];

        $existing = User::query()->where('email', $data['email'])->first();

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($existing)],
            'password' => ['required', 'string', Password::min(8)],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }

            return self::FAILURE;
        }

        if ($existing !== null && ! $this->option('update')) {
            $this->error('Пользователь с таким email уже есть. Добавьте --update, чтобы сменить имя и пароль.');

            return self::FAILURE;
        }

        $user = User::query()->updateOrCreate(
            ['email' => $data['email']],
            [
                'name' => $data['name'],
                'password' => $data['password'],
            ],
        );

        $this->info($existing ? 'Администратор обновлён: '.$user->email : 'Администратор создан: '.$user->email);

        return self::SUCCESS;
    }
}
