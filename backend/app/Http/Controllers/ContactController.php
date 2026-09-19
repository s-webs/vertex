<?php

namespace App\Http\Controllers;

use App\Enums\InquiryStatus;
use App\Http\Requests\StoreInquiryRequest;
use App\Models\Inquiry;
use App\Support\SeoResolver;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function show(SeoResolver $seo): View
    {
        $page = config('site.pages.contacts');
        $document = $seo->forPage(
            'contacts',
            $page['fallback_title'],
            $page['fallback_description'],
            route('contacts.show'),
            [
                ['name' => 'Главная', 'url' => route('home')],
                ['name' => 'Контакты', 'url' => route('contacts.show')],
            ],
        );

        session(['contact_form_loaded_at' => now()->getTimestamp()]);

        return view('pages.contacts', [
            'seo' => $document,
            'jsonLd' => $seo->jsonLd($document),
        ]);
    }

    public function store(StoreInquiryRequest $request): RedirectResponse
    {
        Inquiry::query()->create([
            ...$request->safe()->only(['name', 'phone', 'service', 'message']),
            'status' => InquiryStatus::New,
            'ip_address' => $request->ip(),
        ]);

        $request->session()->forget('contact_form_loaded_at');

        return redirect()
            ->route('contacts.show')
            ->with('status', 'Заявка отправлена. Мы свяжемся с вами в ближайшее время.');
    }
}
