<?php

namespace App\Http\Controllers;

use App\Models\Karya;
use App\Models\Komentar;
use Illuminate\Support\Facades\Auth;

class LandingController extends Controller
{
    public function __invoke()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $galeri = Karya::with('pemilik')
            ->withCount('likes')
            ->orderByDesc('likes_count')
            ->orderByDesc('id_karya')
            ->limit(6)
            ->get();

        $apresiasi = Komentar::with('akun')->latest('id_komentar')->limit(3)->get();

        return view('landing', compact('galeri', 'apresiasi'));
    }
}
