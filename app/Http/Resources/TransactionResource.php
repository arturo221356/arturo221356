<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Support\Carbon;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'dn' => $this->dn,
            'taecel' => $this->taecel,
            'taecel_success' => $this->taecel_success,
            'taecel_tansID' => $this->taecel_transID,
            'taecel_message' => $this->taecel_message,
            'taecel_folio' => $this->taecel_folio,
            'taecel_status' => $this->taecel_status,
            'monto' => $this->monto,
            'company_name' => $this->company->name,
            'inventario_name' => $this->inventario->inventarioable->name,
            'recarga_name' => $this->recarga->name,
            'fecha' => Carbon::parse($this->updated_at)->format('d/m/y h:i:s'),
           ];
    }
}
