<?php

namespace App\Console\Commands;

use App\Services\NotifikasiService;
use Illuminate\Console\Command;

class CekNotifikasiHarian extends Command
{
    protected $signature = 'notifikasi:cek-harian';

    protected $description = 'Cek santri tidak setor dan target terlewat, lalu buat notifikasi harian';

    public function __construct(private NotifikasiService $notifikasiService)
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->info('Memulai pengecekan notifikasi harian...');

        $this->info('Mengecek santri yang tidak menyetor...');
        $this->notifikasiService->cekSantriTidakSetor();
        $this->info('Selesai: cekSantriTidakSetor.');

        $this->info('Mengecek target hafalan yang terlewat...');
        $this->notifikasiService->cekTargetTerlewat();
        $this->info('Selesai: cekTargetTerlewat.');

        $this->info('Pengecekan notifikasi harian selesai.');

        return self::SUCCESS;
    }
}
