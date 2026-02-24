@extends('errors.layout', [
'title' => 'Layanan Tidak Tersedia',
'colorBg' => 'bg-slate-500/10',
'colorIconBg' => 'bg-slate-100',
'colorIconText' => 'text-slate-600'
])

@section('code', '503')
@section('title', 'Sedang Sibuk')
@section('message', 'Layanan kami sedang tidak tersedia untuk sementara waktu. Kami akan segera kembali secepat mungkin.')

@section('icon')
<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
</svg>
@endsection