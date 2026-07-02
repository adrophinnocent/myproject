<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Slider;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        $faqs = Faq::where('is_active', true)->orderBy('order')->get();
        $banner = Slider::where('page', 'faq')->active()->first();

        return view('public.faqs', compact('faqs', 'banner'));
    }
}
