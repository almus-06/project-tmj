<?php

namespace App\Services;

use App\Models\Attendance;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Membuat kode absensi harian "FitToWork-TMJ-ddmmyy-0001".
 *
 * Nomor urut diambil dari tabel attendance_counters yang barisnya dikunci
 * (SELECT ... FOR UPDATE) selama transaksi. Dengan begitu dua submit pada
 * detik yang sama tetap antre dan mendapat nomor berbeda, berbeda dengan
 * cara lama yang menghitung COUNT() baris (bisa sama untuk dua transaksi
 * yang belum saling melihat).
 *
 * WAJIB dipanggil di dalam DB::transaction().
 */
class AttendanceCodeGenerator
{
    public const PREFIX = 'FitToWork-TMJ-';

    public function next(Carbon $waktu): string
    {
        $tanggal = $waktu->toDateString();
        $prefix = self::PREFIX . $waktu->format('dmy');

        $this->pastikanCounterAda($tanggal, $prefix);

        // Kunci baris counter; transaksi lain untuk tanggal yang sama menunggu di sini.
        $counter = DB::table('attendance_counters')
            ->where('date', $tanggal)
            ->lockForUpdate()
            ->first();

        $seq = (int) $counter->last_seq + 1;

        DB::table('attendance_counters')
            ->where('date', $tanggal)
            ->update(['last_seq' => $seq, 'updated_at' => now()]);

        return $prefix . '-' . str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Buat baris counter untuk tanggal tersebut jika belum ada. Nilai awal
     * diambil dari nomor urut terbesar yang sudah tersimpan hari itu, agar
     * kode lama (sebelum tabel counter ada) tidak bentrok.
     */
    private function pastikanCounterAda(string $tanggal, string $prefix): void
    {
        if (DB::table('attendance_counters')->where('date', $tanggal)->exists()) {
            return;
        }

        $seqTerakhir = Attendance::where('attendance_code', 'like', $prefix . '-%')
            ->pluck('attendance_code')
            ->map(fn (string $kode) => (int) substr($kode, strlen($prefix) + 1))
            ->max() ?? 0;

        // insertOrIgnore: jika transaksi lain sudah membuat barisnya, abaikan.
        DB::table('attendance_counters')->insertOrIgnore([
            'date' => $tanggal,
            'last_seq' => $seqTerakhir,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
