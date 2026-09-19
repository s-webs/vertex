<?php

namespace App\Http\Middleware;

use App\Models\Redirect as PathRedirect;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class ApplyRedirects
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Schema::hasTable('redirects')) {
            return $next($request);
        }

        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            return $next($request);
        }

        if ($request->is('admin', 'admin/*', 'storage/*', 'up', 'sitemap.xml', 'robots.txt')) {
            return $next($request);
        }

        $path = '/'.ltrim($request->getPathInfo(), '/');

        if ($path !== '/') {
            $path = rtrim($path, '/') ?: '/';
        }

        $redirect = PathRedirect::query()->where('from_path', $path)->first();

        if ($redirect === null) {
            return $next($request);
        }

        return redirect($redirect->to_path, $redirect->status);
    }
}
