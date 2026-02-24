@extends('errors.layout', [
    'title' => 'Halaman Tidak Ditemukan',
    'colorBg' => 'bg-amber-500/10',
    'colorIconBg' => 'bg-amber-50',
    'colorIconText' => 'text-amber-500'
])

@section('code', '404')
@section('title', 'Ups! Halaman Tidak Ditemukan!')
@section('message', 'Halaman yang Anda cari tidak ditemukan atau telah dipindahkan. Pastikan URL sudah benar.')

@section('icon')
<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
</svg>
@endsection