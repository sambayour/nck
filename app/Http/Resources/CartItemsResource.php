<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,   
            'inventory' => $this->inventory,
            'quantity' => $this->quantity,
            'cart' => $this->cart,
            'created_at' => $this->created_at ? date('F d, Y',strtotime($this->created_at)): null,
            'updated_at' => $this->updated_at ? date('F d, Y',strtotime($this->updated_at)): null
        ];
    }
}
