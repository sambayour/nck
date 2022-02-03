<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CartsResource extends JsonResource
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
            'order_ref' => $this->order_ref,  
            'payment_ref' => $this->payment_ref,
            'status' => $this->status,
            'user' => $this->user,
            'total_amount' => $this->total_amount,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'cart_item' => $this->cart_item,
            'created_at' => $this->created_at ? date('F d, Y',strtotime($this->created_at)): null,
            'updated_at' => $this->updated_at ? date('F d, Y',strtotime($this->updated_at)): null
        ];
    }
}
