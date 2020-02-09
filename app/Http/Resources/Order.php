<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Order extends JsonResource
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
            'order_id' => $this->order_id,
            'customer_id' => $this->customer_id,
            'customer_name' => $this->name,
            'order_status_id' => $this->order_status_id,
            'order_status' => $this->order_status,
            'total_product' => $this->total_product,
            'subtotal' => $this->subtotal,
            'total' => $this->total,
            'payment_id' => $this->payment_id,
            'payment_option' => $this->payment_method,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}

   