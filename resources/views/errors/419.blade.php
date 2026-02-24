@extends('errors.layout', [
'title' => 'Sesi Berakhir',
'colorBg' => 'bg-indigo-500/10',
'colorIconBg' => 'bg-indigo-50',
'colorIconText' => 'text-indigo-600'
])

@section('code', '419')
@section('title', 'Sesi Telah Habis')
@section('message', 'Maaf, sesi Anda telah berakhir demi keamanan. Silakan segarkan halaman dan coba kembali.')

@section('icon')
<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
@endsection