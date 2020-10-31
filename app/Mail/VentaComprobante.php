<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Venta;
use Illuminate\Support\Carbon;

class VentaComprobante extends Mailable
{
    use Queueable, SerializesModels;

    public   $venta;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Venta $venta)
    {
        $this->venta = $venta;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       

        $ventaData = [

            'inventario'=>$this->venta->inventario->inventarioable,

            'distribution'=>$this->venta->inventario->distribution->name,

            'venta'=>$this->venta,
            
            'fecha' => Carbon::parse($this->venta->created_at)->format('d/m/y h:i:s' ),

            'vendedor' => $this->venta->user->name,

            'cliente' => $this->venta->cliente,

            'productosGenerales' =>$this->venta->generalProducts,

            'imeis' => $this->venta->imeis()->with('equipo')->get(),

            'iccs' => $this->venta->iccs()->with('linea','company','linea.product','linea.subProduct')->get(),

            'transactions' => $this->venta->transactions()->with('recarga')->get(),
            
        ];

        return $this->view('venta.show',$ventaData);
    }
}
