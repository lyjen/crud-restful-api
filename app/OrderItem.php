<?php
  
namespace App;
   
use Illuminate\Database\Eloquent\Model;
   
class OrderItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'order_item_id';
    protected $fillable = [
        'order_id', 'product_id', 'quantity', 'unit_cost', 'total'
    ];
}
