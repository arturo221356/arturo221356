<?php

namespace App\Http\Resources;

use Illuminate\Support\Carbon;

use Illuminate\Http\Resources\Json\JsonResource;

class TraspasoCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
       $user_name = null;
       
        if($this->user_id){
         $user_name = $this->user->name;
       }
        return [

            'id'=> $this->id,
            'sucursal_name'    => $this->sucursal->name,
            'user_name'    =>  $user_name,
            'id_sucursal' =>$this->sucursal_id,
            'accepted'    => $this->accepted,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/y h:i:s' ),
            'updated_at' => Carbon::parse($this->updated_at)->format('d/m/y h:i:s' ),
    
];
    }
}
