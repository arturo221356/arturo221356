<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Porta;

use App\Transaction;

class TransactionPortas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:portas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'busca entre todas las recargas y a las portas que no estan activadas les asigna su recarga';

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
        $portas = Porta::whereNull('activated_at')->get();

        foreach ($portas as $porta) {
            $transaction = Transaction::where('dn', $porta->linea->dn)->first();

            if (isset($transaction)) {
                if ($porta->linea->icc->company->id == $this->transaction->company_id) {

                    $porta->activated_at = $this->transaction->created_at;
                    $porta->transaction_id = $this->transaction->id;
                    $porta->save();
                    $porta->linea->setStatus('Activado');
                }
            }
        }

    }
}
