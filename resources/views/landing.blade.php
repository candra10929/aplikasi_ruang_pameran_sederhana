@extends('layouts.app')
@section('title', 'Aplikasi Pameran Sederhana')

@section('content')
<div class="kp-container">

    {{-- HERO --}}
    <section class="kp-hero">
        <h1>Aplikasi Pameran Sederhana</h1>
        <p>Satu tempat sederhana untuk memamerkan karya terbaikmu. Tampilkan dan ceritakan karyamu dengan cara yang rapi,
            menarik, dan mudah dibagikan kepada siapa saja.</p>
        <img class="kp-hero__img" src="{{ asset('images/landing/hero.jpg') }}" alt="Foto utama" onerror="this.style.visibility='hidden'">
    </section>

    {{-- PAMERKAN KARYAMU --}}
    <section class="kp-block">
        <h2>Pamerkan karyamu</h2>
        <div class="row g-4">
            @foreach ([
                ['pamer-1.jpg', 'Poster', 'Tampilkan desain poster terbaikmu dengan layout yang bersih dan profesional.'],
                ['pamer-2.jpg', 'Ilustrasi', 'Unggah ilustrasimu dan biarkan orang lain menikmati setiap detailnya.'],
                ['pamer-3.jpg', 'UI/UX', 'Bagikan rancangan antarmuka dan alur desain yang sudah kamu buat.'],
            ] as [$gambar, $judul, $isi])
                <div class="col-md-4 kp-info">
                    <img class="kp-pic kp-pic--3" src="{{ asset('images/landing/'.$gambar) }}" alt="{{ $judul }}" onerror="this.style.visibility='hidden'">
                    <h3>{{ $judul }}</h3>
                    <p>{{ $isi }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- GALERI DIGITAL: karya terpopuler dari database; kalau belum ada, contoh --}}
    <section class="kp-block">
        <h2>Galeri Digital-mu</h2>
        <div class="row g-3 kp-gallery">
            @forelse ($galeri as $karya)
                <div class="col-4 kp-gallery__item">
                    <a href="{{ route('login') }}" class="kp-thumb rounded-3" aria-label="{{ $karya->judul }}">
                        @if ($karya->url_gambar)
                            <img src="{{ $karya->url_gambar }}" alt="{{ $karya->judul }}" loading="lazy" onerror="this.remove()">
                        @endif
                    </a>
                    <div class="d-flex justify-content-between align-items-center gap-1">
                        <h3>{{ \Illuminate\Support\Str::limit($karya->judul, 22) }}</h3>
                        <a href="{{ route('login') }}" class="kp-gallery__like" aria-label="Masuk untuk menyukai">♡</a>
                    </div>
                    <p>{{ \Illuminate\Support\Str::limit($karya->deskripsi, 40) }}</p>
                </div>
            @empty
                @foreach (['Echoes of Lights', 'Among the Flowers', 'A Moment in Focus', 'The Mountain', 'The Journey', 'Fleeting Beauty'] as $judul)
                    <div class="col-4 kp-gallery__item">
                        <div class="kp-thumb rounded-3">
                            <img src="{{ asset('images/landing/galeri.jpg') }}" alt="{{ $judul }}" onerror="this.remove()">
                        </div>
                        <div class="d-flex justify-content-between align-items-center gap-1">
                            <h3>{{ $judul }}</h3>
                            <a href="{{ route('login') }}" class="kp-gallery__like" aria-label="Masuk untuk menyukai">♡</a>
                        </div>
                        <p>Tampilkan karya favoritmu di sini.</p>
                    </div>
                @endforeach
            @endforelse
        </div>
    </section>

    {{-- PORTOFOLIO --}}
    <section class="kp-block">
        <h2>Tampilkan Portofolio Anda</h2>
        <div class="row g-4">
            @foreach ([
                ['portofolio-1.jpg', 'Girlie', 'Kumpulan karya dan proyek yang menunjukkan kemampuanmu.'],
                ['portofolio-2.jpg', 'Princess', 'Susun portofolio dengan tampilan yang segar dan menarik.'],
            ] as [$gambar, $judul, $isi])
                <div class="col-md-6 kp-info">
                    <img class="kp-pic kp-pic--2" src="{{ asset('images/landing/'.$gambar) }}" alt="{{ $judul }}" onerror="this.style.visibility='hidden'">
                    <h3>{{ $judul }}</h3>
                    <p>{{ $isi }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- APRESIASI: komentar terbaru dari database; kalau belum ada, contoh --}}
    <section class="kp-block">
        <h2>Apresiasi</h2>
        <div class="row g-4">
            @forelse ($apresiasi as $k)
                <div class="col-md-4">
                    <article class="kp-testi">
                        <h3>{{ \Illuminate\Support\Str::limit($k->isi_komentar, 80) }}</h3>
                        <div class="d-flex align-items-center gap-2">
                            <span class="kp-avatar {{ $k->akun->warna_avatar }}" style="width:34px;height:34px">
                                @if ($k->akun->foto_url)<img src="{{ $k->akun->foto_url }}" alt="" onerror="this.remove()">@else{{ $k->akun->inisial }}@endif
                            </span>
                            <div class="kp-comment__meta"><strong>{{ $k->akun->name }}</strong><span>{{ $k->akun->label_level }}</span></div>
                        </div>
                    </article>
                </div>
            @empty
                @foreach ([['Wow keren banget sih ini', 'Candra Winata', 'Mahasiswa'], ['Ini sih gila!! tetap konsisten bung', 'Farras Radithya', 'Mahasiswa'], ['Sebuah Mahakarya', 'Zakky Mubarraq', 'Dosen']] as [$isi, $nama, $peran])
                    <div class="col-md-4">
                        <article class="kp-testi">
                            <h3>{{ $isi }}</h3>
                            <div class="d-flex align-items-center gap-2">
                                <span class="kp-avatar av-blue" style="width:34px;height:34px">{{ mb_substr($nama, 0, 1) }}</span>
                                <div class="kp-comment__meta"><strong>{{ $nama }}</strong><span>{{ $peran }}</span></div>
                            </div>
                        </article>
                    </div>
                @endforeach
            @endforelse
        </div>
    </section>
</div>
@endsection
