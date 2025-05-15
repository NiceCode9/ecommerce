<?php

namespace App\Console\Commands;

use App\Imports\BrandImport;
use App\Imports\KategoriImport;
use App\Imports\ProdukImport;
use App\Imports\SocketImport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ImportMaster extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-master';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $brand = public_path('brands.xlsx');
        $kategori = public_path('kategori.xlsx');
        $socket = public_path('socket.xlsx');
        $produk = public_path('produk.xlsx');

        Log::info("Importing file: " . $brand);

        DB::beginTransaction();
        try {
            // Excel::import(new BrandImport, $brand);
            // Excel::import(new KategoriImport, $kategori);
            // Excel::import(new SocketImport, $socket);
            Excel::import(new ProdukImport, $produk);

            DB::commit();
            $this->info('Import completed.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Import failed: ' . $e->getMessage());
            $this->error('Import failed: ' . $e->getMessage());
        }
    }
}
