<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Carbon;

use App\Linea;


class DeleteOldLineas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:oldlineas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes lineas that are older than 1 year';

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
        
        Linea::currentStatus(['Recargable', 'Preactiva'])->whereHasMorph('productoable',['App\\Chip'], function ($query) {
            $query->where('preactivated_at', '<=', Carbon::now()->subDays(365)->toDateTimeString());
        })->

        
        chunkById(200, function ($records) {
            $counter = 0;

            foreach ($records as $record) {
            
              $counter ++;

              $record->setStatus('Expirado');

              $record->deleteStatus(['Recargable', 'Preactiva']);

              $this->info("C".$counter." ".$record->dn."-------".$record->status."  CC".($record->icc->icc ?? '')."  I".($record->icc->inventario->inventarioable->name ?? ''));

              $record->icc->delete();


             
            }
        });

       
    }
}
