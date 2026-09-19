@extends('layouts.site')

@section('content')
    <section class="wrap">
        <div class="page-head">
            <div class="breadcrumbs"><a href="{{ route('home') }}">Главная</a><span>/</span><span>Контакты</span></div>
            <div class="eyebrow">Контакты</div>
            <h1>Оставьте заявку или свяжитесь с нами</h1>
            <p>Расскажите о вашем объекте, задаче или вопросе по обслуживанию. Вместе определим следующий шаг.</p>
        </div>
        <div class="contact-grid pb-16">
            <div class="contact-details">
                @if ($site->primaryPhone())
                    <div class="contact-item">
                        <i class="ph ph-phone" aria-hidden="true"></i>
                        <div>
                            <small>Телефон</small>
                            <strong><a href="{{ $site->telHref($site->primaryPhone()['number']) }}">{{ $site->primaryPhone()['number'] }}</a></strong>
                        </div>
                    </div>
                @endif
                @foreach ($site->phones() as $index => $phone)
                    @continue($index === 0)
                    <div class="contact-item">
                        <i class="ph ph-phone" aria-hidden="true"></i>
                        <div>
                            <small>{{ $phone['label'] }}</small>
                            <strong><a href="{{ $site->telHref($phone['number']) }}">{{ $phone['number'] }}</a></strong>
                        </div>
                    </div>
                @endforeach
                @if ($site->email())
                    <div class="contact-item">
                        <i class="ph ph-envelope-simple" aria-hidden="true"></i>
                        <div>
                            <small>Электронная почта</small>
                            <strong><a href="mailto:{{ $site->email() }}">{{ $site->email() }}</a></strong>
                            <p>Для запросов, проектов и сотрудничества</p>
                        </div>
                    </div>
                @endif
                @if ($site->address())
                    <div class="contact-item">
                        <i class="ph ph-map-pin" aria-hidden="true"></i>
                        <div>
                            <small>Наш офис</small>
                            <strong>{{ $site->address() }}</strong>
                        </div>
                    </div>
                @endif
                @if ($site->whatsappUrl())
                    <div class="contact-item">
                        <i class="ph ph-whatsapp-logo" aria-hidden="true"></i>
                        <div>
                            <small>WhatsApp</small>
                            <strong><a href="{{ $site->whatsappUrl() }}" target="_blank" rel="noopener noreferrer">Написать в чат</a></strong>
                        </div>
                    </div>
                @endif
                @if ($site->instagramUrl())
                    <div class="contact-item">
                        <i class="ph ph-instagram-logo" aria-hidden="true"></i>
                        <div>
                            <small>Instagram</small>
                            <strong><a href="{{ $site->instagramUrl() }}" target="_blank" rel="noopener noreferrer">Смотреть профиль</a></strong>
                        </div>
                    </div>
                @endif
            </div>
            <div class="form-box">
                <h3>Обсудим ваш проект</h3>
                <p>Оставьте контактные данные и коротко опишите задачу.</p>
                @if (session('status'))
                    <div class="alert alert-success" role="status">{{ session('status') }}</div>
                @endif
                @error('form')
                    <div class="alert alert-error" role="alert">{{ $message }}</div>
                @enderror
                <form method="POST" action="{{ route('contacts.store') }}">
                    @csrf
                    <div class="hp-field" aria-hidden="true">
                        <label>Сайт
                            <input type="text" name="website" value="" tabindex="-1" autocomplete="off">
                        </label>
                    </div>
                    <div class="form-grid">
                        <label class="field">Ваше имя *
                            <input name="name" autocomplete="name" placeholder="Как к вам обращаться" value="{{ old('name') }}" required maxlength="100">
                            @error('name') <span class="field-error">{{ $message }}</span> @enderror
                        </label>
                        <label class="field">Телефон *
                            <input name="phone" type="tel" data-phone-mask autocomplete="tel" placeholder="+7 (___) ___-__-__" value="{{ old('phone') }}" required>
                            @error('phone') <span class="field-error">{{ $message }}</span> @enderror
                        </label>
                    </div>
                    <label class="field">Вас интересует
                        <select name="service">
                            @foreach (config('site.inquiry_services') as $service)
                                <option value="{{ $service }}" @selected(old('service') === $service)>{{ $service }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="field">О вашем проекте
                        <textarea name="message" placeholder="Тип объекта, количество этажей, пожелания…" maxlength="3000">{{ old('message') }}</textarea>
                        @error('message') <span class="field-error">{{ $message }}</span> @enderror
                    </label>
                    <button class="button dark w-full" type="submit">Отправить заявку <i class="ph ph-arrow-up-right" aria-hidden="true"></i></button>
                </form>
            </div>
        </div>
        <div class="section-head">
            <div>
                <div class="eyebrow">Как нас найти</div>
                <h2>Мы на карте</h2>
            </div>
            <span class="notice">{{ $site->hasMapPin() ? 'Офис на карте' : 'Точка офиса появится после уточнения адреса' }}</span>
        </div>
        <iframe class="map" title="{{ $site->hasMapPin() ? 'Карта офиса '.$site->companyName() : 'Обзорная карта Казахстана' }}" loading="lazy" referrerpolicy="no-referrer" src="{{ $site->mapEmbedSrc() }}"></iframe>
        <p class="notice mb-12">
            © <a class="underline" href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener noreferrer">OpenStreetMap</a>.
            <a class="underline" href="{{ $site->mapExternalUrl() }}" target="_blank" rel="noopener noreferrer">Открыть карту отдельно <i class="ph ph-arrow-up-right" aria-hidden="true"></i></a>
        </p>
    </section>
@endsection
