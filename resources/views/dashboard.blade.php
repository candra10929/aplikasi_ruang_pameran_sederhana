@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
{{-- Kotak pencarian + chip kategori (kategori diambil dari tabel kategori) --}}
<section class="kp-banner">
    <div class="kp-container">
        <form method="GET" action="{{ route('dashboard') }}" class="kp-search-bar" role="search">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
            <input type="search" name="q" value="{{ $q }}" class="form-control" placeholder="Cari judul, deskripsi, tools, atau nama mahasiswa" aria-label="Cari karya">
            @if ($kat)<input type="hidden" name="kategori" value="{{ $kat }}">@endif
        </form>
        <div class="kp-chips">
            <a class="kp-chip {{ ! $kat ? 'active' : '' }}" href="{{ route('dashboard', array_filter(['q' => $q])) }}">Semua</a>
            @foreach ($kategori as $k)
                <a class="kp-chip {{ $kat === $k->id_kategori ? 'active' : '' }}"
                    href="{{ route('dashboard', array_filter(['kategori' => $k->id_kategori, 'q' => $q])) }}">{{ $k->jenis_kategori }}</a>
            @endforeach
        </div>
    </div>
</section>

<div class="kp-container pb-3">
@if (isset($hasil))
    {{-- MODE CARI / FILTER --}}
    <h2 class="h4 fw-bold mt-4 mb-3">
        @if ($q !== '') Hasil untuk “{{ $q }}” @else Karya @endif
        @if ($kategoriAktif) <span class="text-secondary fw-normal">· {{ $kategoriAktif->jenis_kategori }}</span> @endif
        <small class="text-secondary fw-normal fs-6">({{ $hasil->total() }} karya)</small>
    </h2>

    @if ($hasil->isEmpty())
        <div class="kp-empty">Tidak ada karya yang cocok. <a href="{{ route('dashboard') }}">Lihat semua karya</a></div>
    @else
        <div class="row g-4">
            @foreach ($hasil as $karya)
                <div class="col-sm-6 col-lg-4">@include('partials.karya-card', ['karya' => $karya])</div>
            @endforeach
        </div>
        <div class="d-flex justify-content-center mt-4">@include('partials.pagination', ['paginator' => $hasil])</div>
    @endif
@else
    {{-- MODE NORMAL --}}
    <h2 class="h3 fw-bold mt-4 mb-3">Karya Terpopuler</h2>
    @if ($populer->isEmpty())
        <div class="kp-empty">
            Belum ada karya.
            @if (auth()->user()->isMahasiswa()) <a href="{{ route('karya.create') }}">Unggah karya pertamamu</a> @endif
        </div>
    @else
        <div class="row g-4">
            @foreach ($populer as $karya)
                <div class="col-sm-6 col-lg-4">@include('partials.karya-card', ['karya' => $karya, 'deskripsi' => false])</div>
            @endforeach
        </div>
    @endif

    @if ($bagian->isNotEmpty())
        <h2 class="h3 fw-bold mt-5 mb-3">Karya Lainnya</h2>
        @foreach ($bagian as $b)
            <div class="d-flex justify-content-between align-items-baseline mt-4 mb-3">
                <h3 class="h5 fw-semibold m-0">{{ $b['judul'] }}</h3>
                @if ($b['id'])<a href="{{ route('dashboard', ['kategori' => $b['id']]) }}" class="small">Lihat semua</a>@endif
            </div>
            <div class="row g-4">
                @foreach ($b['karya'] as $karya)
                    <div class="col-sm-6 col-lg-4">@include('partials.karya-card', ['karya' => $karya])</div>
                @endforeach
            </div>
        @endforeach
    @endif
@endif
</div>
@endsection
