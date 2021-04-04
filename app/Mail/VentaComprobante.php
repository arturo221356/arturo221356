<?php

namespace App\Mail;

use App\Venta;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;


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

       

        $seller = new Party([
            'name'          => $this->venta->inventario->distribution->name,
            'address'       => $this->venta->inventario->inventarioable->address,
    
            'custom_fields' => [
                'sucursal' =>  $this->venta->inventario->inventarioable->name,
                'vendedor'          => $this->venta->user->name,
    
            ],
        ]);
    
    
    
        $customer = new Buyer([
            'name'          => $this->venta->cliente->name,
            'custom_fields' => [
                'Correo' => $this->venta->cliente->email,
                'RFC'  => $this->venta->cliente->rfc,
                'CURP' =>  $this->venta->cliente->curp,
            ],
        ]);
    
        $items = [];
    
        $imeis = $this->venta->imeis()->with('equipo')->get();
    
        foreach($imeis as $imei){
            
            array_push($items, (new InvoiceItem())->title($imei->equipo->marca.'  '.$imei->equipo->modelo.'  '.$imei->imei)->pricePerUnit($imei->pivot->price));
            
        }
    
        $iccs = $this->venta->iccs()->with('linea','company','linea.product','linea.subProduct')->get();
    
        foreach($iccs as $icc){
            
            array_push($items, (new InvoiceItem())->title($icc->linea->dn.' '.$icc->linea->subProduct->name.'  '.$icc->linea->product->name.'  '.$icc->company->name.'  '.$icc->icc)->pricePerUnit($icc->pivot->price));
            
        }
    
        $transactions = $this->venta->transactions()->with('recarga')->get();
    
        foreach($transactions as $transaction){
            
            array_push($items, (new InvoiceItem())->title($transaction->company->name.'  '. $transaction->recarga->name.'  '.$transaction->dn)->pricePerUnit($transaction->pivot->price));
            
        }
    
        $generales = $this->venta->generalProducts;
    
        foreach($generales as $vtageneral){
            
            array_push($items, (new InvoiceItem())->title($vtageneral->name.'  '. $vtageneral->description)->pricePerUnit($vtageneral->pivot->price));
            
        }
    
    
    
    
        $invoice = Invoice::make('Comprobante')
            ->buyer($customer)
            ->seller($seller)
            ->date($this->venta->created_at)
            ->filename('invoices/Comprobante_'.$this->venta->id)
            ->sequence($this->venta->id)
            ->addItems($items);

        $invoiceUrl = $invoice->save('local')->url();




        return $this->
        
        subject('Comprobante de compra')->

        attachFromStorage('invoices/Comprobante_'.$this->venta->id.'.pdf')->
        
        markdown('emails.comprobantes.venta',  [

             'cliente_name' => $this->venta->cliente->name,

            'distribution_name' => $this->venta->inventario->distribution->name,

            'url' => 'http://ventachip.com/venta/comprobante/?folio='.$this->venta->id,
        ]);
    }
}
