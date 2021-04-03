<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Storage;

class DeleteInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes invoices path';

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
        $files = Storage::allFiles('invoices/');

         Storage::delete($files);

         $this->info('Invoices Deleted');

        return 0;


    }
}
