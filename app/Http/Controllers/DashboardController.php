<?php

namespace App\Http\Controllers;

use App\Models\Karya;
use App\Models\Kategori;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $uid = $request->user()->id_akun;
        $kategori = Kategori::orderBy('jenis_kategori')->get();
        $q = trim((string) $request->query('q', ''));
        $kat = (int) $request->query('kategori', 0);
        $kategoriAktif = $kat ? $kategori->firstWhere('id_kategori', $kat) : null;

        // Mode pencarian / filter kategori: satu daftar hasil
        if ($q !== '' || $kat) {
            $like = '%'.addcslashes($q, '%_\\').'%';

            $hasil = Karya::denganInfo($uid)
                ->when($q !== '', fn ($b) => $b->where(function ($w) use ($like) {
                    $w->where('judul', 'like', $like)
                        ->orWhere('deskripsi', 'like', $like)
                        ->orWhere('tools', 'like', $like)
                        ->orWhereHas('pemilik', fn ($p) => $p->where('name', 'like', $like));
                }))
                ->when($kat, fn ($b) => $b->where('id_kategori', $kat))
                ->latest('id_karya')
                ->paginate(12)
                ->withQueryString();

            return view('dashboard', compact('kategori', 'q', 'kat', 'kategoriAktif', 'hasil'));
        }

        // Mode normal: terpopuler + per kategori
        $populer = Karya::denganInfo($uid)
            ->orderByDesc('likes_count')
            ->orderByDesc('id_karya')
            ->limit(6)
            ->get();

        $bagian = $kategori->map(fn ($k) => [
            'judul' => $k->jenis_kategori,
            'id' => $k->id_kategori,
            'karya' => Karya::denganInfo($uid)->where('id_kategori', $k->id_kategori)->latest('id_karya')->limit(3)->get(),
        ]);

        $tanpaKategori = Karya::denganInfo($uid)->whereNull('id_kategori')->latest('id_karya')->limit(3)->get();
        if ($tanpaKategori->isNotEmpty()) {
            $bagian->push(['judul' => 'Lainnya', 'id' => null, 'karya' => $tanpaKategori]);
        }

        $bagian = $bagian->filter(fn ($b) => $b['karya']->isNotEmpty())->values();

        return view('dashboard', compact('kategori', 'q', 'kat', 'kategoriAktif', 'populer', 'bagian'));
    }
}
