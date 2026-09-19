<?php

return [
    'company_name' => 'Adilet-lift',

    'pages' => [
        'home' => [
            'label' => 'Главная',
            'route' => 'home',
            'fallback_title' => 'Главная — Adilet-lift',
            'fallback_description' => 'Adilet-lift — установка, обслуживание и модернизация лифтов. Главная.',
        ],
        'catalog' => [
            'label' => 'Каталоги',
            'route' => 'catalog',
            'fallback_title' => 'Каталоги — Adilet-lift',
            'fallback_description' => 'Adilet-lift — установка, обслуживание и модернизация лифтов. Каталоги.',
        ],
        'about' => [
            'label' => 'О компании',
            'route' => 'about',
            'fallback_title' => 'О компании — Adilet-lift',
            'fallback_description' => 'Adilet-lift — установка, обслуживание и модернизация лифтов. О компании.',
        ],
        'works' => [
            'label' => 'Наши работы',
            'route' => 'works.index',
            'fallback_title' => 'Наши работы — Adilet-lift',
            'fallback_description' => 'Примеры выполненных объектов Adilet-lift.',
        ],
        'contacts' => [
            'label' => 'Контакты',
            'route' => 'contacts.show',
            'fallback_title' => 'Контакты — Adilet-lift',
            'fallback_description' => 'Adilet-lift — установка, обслуживание и модернизация лифтов. Контакты.',
        ],
    ],

    'page_images' => [
        'home' => [
            'hero' => [
                'label' => 'Главный баннер',
                'fallback' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=85',
                'alt' => 'Современный высотный комплекс со стеклянными фасадами',
            ],
        ],
        'about' => [
            'side' => [
                'label' => 'Изображение на странице «О компании»',
                'fallback' => null,
                'alt' => 'Логотип Adilet-lift',
            ],
        ],
    ],

    'admin' => [
        'name' => env('ADMIN_NAME', 'Администратор'),
        'email' => env('ADMIN_EMAIL', 'admin@adilet-lift.test'),
        'password' => env('ADMIN_PASSWORD', 'password'),
    ],

    'inquiry_services' => [
        'Установка нового лифта',
        'Сервисное обслуживание',
        'Модернизация оборудования',
        'Другой вопрос',
    ],

    'inquiry' => [
        'per_minute' => 5,
        'min_seconds' => 3,
        'duplicate_minutes' => 2,
        'max_links' => 2,
    ],
];
