<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Catalog;
use App\Models\Document;
use App\Models\Inquiry;
use App\Models\Work;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'catalogsCount' => Catalog::query()->count(),
            'worksCount' => Work::query()->count(),
            'newInquiries' => Inquiry::query()->unread()->count(),
            'documentsCount' => Document::query()->count(),
        ]);
    }
}
