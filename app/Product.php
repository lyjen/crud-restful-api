<?php
  
namespace App;
   
use Illuminate\Database\Eloquent\Model;
   
class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'product_id';
    protected $fillable = [
        'title', 'description', 'sku', 'price', 'quantity', 'product_status_id', 'category_id'
    ];
}