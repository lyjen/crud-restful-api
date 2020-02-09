<?php
  
namespace App;
   
use Illuminate\Database\Eloquent\Model;
   
class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'order_id';
    protected $fillable = [
        'customer_id', 'order_status_id', 'order_date', 'total_product','subtotal', 'total', 'payment_id'
    ];
}
