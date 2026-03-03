<?php

use App\Models\SocialMedia;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Starting Social Media Cleanup...\n";

// Ambil semua duplikat berdasarkan socialable_id dan socialable_type
$duplicates = DB::table('social_media')
    ->select('socialable_id', 'socialable_type', DB::raw('COUNT(*) as count'))
    ->groupBy('socialable_id', 'socialable_type')
    ->having('count', '>', 1)
    ->get();

$totalDeleted = 0;

foreach ($duplicates as $duplicate) {
    echo "Processing duplicate: {$duplicate->socialable_id} ({$duplicate->socialable_type})...\n";
    
    // Ambil semua record untuk grup ini
    $records = SocialMedia::where('socialable_id', $duplicate->socialable_id)
        ->where('socialable_type', $duplicate->socialable_type)
        ->get();
        
    // Tentukan record mana yang akan dipertahankan
    // Kriteria: yang punya data URL paling banyak, jika sama pilih yang id-nya terbesar/terbaru
    $bestRecord = null;
    $maxScore = -1;
    
    foreach ($records as $record) {
        $score = 0;
        if (!empty($record->facebook_url)) $score++;
        if (!empty($record->instagram_url)) $score++;
        if (!empty($record->tiktok_url)) $score++;
        
        // Tie-breaker: jika score sama, pilih ID yang lebih besar
        if ($score > $maxScore || ($score === $maxScore && ($bestRecord === null || $record->id > $bestRecord->id))) {
            $maxScore = $score;
            $bestRecord = $record;
        }
    }
    
    // Hapus selain record terbaik
    foreach ($records as $record) {
        if ($record->id !== $bestRecord->id) {
            $record->delete();
            $totalDeleted++;
            echo "  - Deleted ID: {$record->id} (Score: " . ($score) . ")\n";
        }
    }
}

echo "\nCleanup Finished. Total records deleted: {$totalDeleted}\n";
