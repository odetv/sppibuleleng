@extends('errors.layout', [
'title' => 'Terlalu Banyak Permintaan',
'colorBg' => 'bg-amber-500/10',
'colorIconBg' => 'bg-amber-50',
'colorIconText' => 'text-amber-600'
])

@section('code', '429')
@section('title', 'Tenang Sejenak')
@section('message', 'Sistem mendeteksi terlalu banyak permintaan dari perangkat Anda. Mohon tunggu beberapa saat sebelum mencoba lagi.')

@section('icon')
<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
</svg>
@endsection