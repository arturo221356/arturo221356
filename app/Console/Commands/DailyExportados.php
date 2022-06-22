<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Linea;

use Illuminate\Support\Carbon;

use App\Jobs\checkExportado;

class DailyExportados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exportados:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revisa todas las lineas tanto como vendidas y preactivas se encuentren en el mismo operador todos los dias';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        Linea::currentStatus(['Preactiva', 'Recargable'])->chunkById(100, function ($lineas) {
            foreach ($lineas as $linea) {
                checkExportado::dispatch($linea);
            }
        });

        Linea::currentStatus('Activado')->whereHasMorph('productoable', ['App\Chip', 'App\Porta', 'App\Pospago'], function ($query) {
            $query->whereBetween('activated_at', [Carbon::now()->subDays(45), Carbon::now()])
                ->orWhereDate('activated_at', Carbon::now()->subDays(60))
                ->orWhereDate('activated_at', Carbon::now()->subDays(90))
                ->orWhereDate('activated_at', Carbon::now()->subDays(120))
                ->orWhereDate('activated_at', Carbon::now()->subDays(150))
                ->orWhereDate('activated_at', Carbon::now()->subDays(180))
                ->orWhereDate('activated_at', Carbon::now()->subDays(210))
                ->orWhereDate('activated_at', Carbon::now()->subDays(365));
        })
        ->chunkById(100, function ($lineas) {
                foreach ($lineas as $linea) {
                    checkExportado::dispatch($linea);
                }
            });
    }
}
