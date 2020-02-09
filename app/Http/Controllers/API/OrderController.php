<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Order;
use App\OrderItem;
use Validator;
use App\Http\Resources\Order as OrderResource;
use Illuminate\Support\Facades\DB;

class OrderController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $orders = Order::all();
        $orders = Order::leftJoin('users', 'orders.customer_id', '=', 'users.id')
        ->leftJoin('order_status', 'orders.order_status_id', '=', 'order_status.order_status_id')
        ->leftJoin('payment_options', 'orders.payment_id', '=', 'payment_options.payment_id')
        ->select('orders.*', 'users.name', 'order_status.order_status', 'payment_options.payment_method')->get();
        return $this->sendResponse(OrderResource::collection($orders), 'Orders displayed successfully.');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'customer_id' => 'required',
            'order_status_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
            'unit_cost' => 'required'
        ]);


        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $order = Order::create($input);
        if($order){
            $item = array();
            $item['order_id'] = $order->order_id;
            $item['product_id'] = $input['product_id'];
            $item['quantity'] = $input['quantity'];
            $item['unit_cost'] = $input['unit_cost'];
            $item['total'] =  ($input['quantity'] * $input['unit_cost']);

            $order_item = OrderItem::create($item);
            
            if($order->order_status_id == 4){ // Enum Value of Completed is 4
                $this->update_quantity($order->order_id);
            }
            return $this->sendResponse(new OrderResource($order), 'Order added successfully.');
        }else{
            return $this->sendError('Failed to add order(s).');
        }

    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $order_id
     * @return \Illuminate\Http\Response
     */
    public function show($order_id)
    {
        $order = Order::find($order_id);
  
        if (is_null($order)) {
            return $this->sendError('Order not found.');
        }
   
        return $this->sendResponse(new OrderResource($order), 'Order retrieved successfully.');
    }
 
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $order_id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        $order->delete();
   
        return $this->sendResponse([], 'Order deleted successfully.');
    }

    public function update(Request $request, Order $order)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'customer_id' => 'required',
            'order_status_id' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $order->order_status_id = $input['order_status_id'];
        $order->customer_id = $input['customer_id'];

        $order->save();
        if($input['order_status_id'] == 4){
            $this->update_quantity($order->order_id);
        }
        return $this->sendResponse(new OrderResource($order), 'Order updated successfully.');
    }

    public function update_quantity($order_id){

        $items = DB::table('order_items')->where('order_id', $order_id)->get();
        if($items){
            foreach($items as $item){
                $product_id = $item->product_id;

                // Check Product Quantity
                $product = DB::table('products')->where('product_id', $product_id)->first();
                $quantity = $product->quantity - $item->quantity;
                $product_status_id = 1;

                if($quantity < 0){
                    // Update Product Status to Out of Stock
                    $product_status_id = 2;
                }
    
                // Update Product Stocks
                $update_product = DB::table('products')
                ->where('product_id', $product_id)
                ->update([
                    'quantity' => $quantity,
                    'product_status_id' => $product_status_id
                ]);

            }
            return 1;
        }

        return 0;

    }

}