<?php

namespace App\Http\Middleware;

use App\Models\Inquiry;
use App\Support\SiteSettings;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ShareSiteViewData
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        View::share('site', app(SiteSettings::class));
        View::share('navItems', [
            ['route' => 'home', 'label' => 'Главная', 'active' => $request->routeIs('home')],
            ['route' => 'catalog', 'label' => 'Каталоги', 'active' => $request->routeIs('catalog')],
            ['route' => 'about', 'label' => 'О компании', 'active' => $request->routeIs('about')],
            ['route' => 'works.index', 'label' => 'Наши работы', 'active' => $request->routeIs('works.*')],
            ['route' => 'contacts.show', 'label' => 'Контакты', 'active' => $request->routeIs('contacts.*')],
        ]);
        View::share(
            'unreadInquiries',
            $request->user() ? Inquiry::query()->unread()->count() : 0,
        );

        return $next($request);
    }
}
