<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\OrderItem;
use App\Order;
use Validator;
use App\Http\Resources\OrderItem as OrderItemResource;
use Illuminate\Support\Facades\DB;

class OrderItemController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $OrderItems = OrderItem::all();

        return $this->sendResponse(OrderItemResource::collection($OrderItems), 'Items displayed successfully.');
    }

    public function show($order_item_id)
    {
        $order_detail = OrderItem::find($order_item_id);
  
        if (is_null($order_detail)) {
            return $this->sendError('Order not found.');
        }
   
        return $this->sendResponse(new OrderItemResource($order_detail), 'Order retrieved successfully.');
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
            'order_id' => 'required',
            'product_id' => 'required',
            'quantity' => 'required',
            'unit_cost' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $input['total'] =  ($input['quantity'] * $input['unit_cost']);

        // Check if product already exist
        $check_product = OrderItem::where('product_id', $input['product_id'])
                                    ->where('order_id', $input['order_id'])->first();
        if($check_product){
            // Item should be added later as of now let's throw a message
            return $this->sendError('Product already exist.');
        }

        $OrderItem = OrderItem::create($input);
        if($OrderItem){
            $this->update_order($OrderItem->order_id);
        }
   
        return $this->sendResponse(new OrderItemResource($OrderItem), 'Product created successfully.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $order_item_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($order_item_id)
    {
        $OrderItem = OrderItem::find($order_item_id)->first();
        $order_id = $OrderItem->order_id;

        $OrderItem->delete();
        $this->update_order($order_id);
   
        return $this->sendResponse([], 'Item deleted successfully.');
    }

    public function update_order($order_id){

        $order = DB::table('orders')
            ->leftJoin('order_items', 'orders.order_id', '=', 'order_items.order_id')
            ->select('orders.order_id', DB::raw('COUNT(order_items.product_id) as total_product'),
                                        DB::raw('SUM(order_items.total) as total'))
            ->where('orders.order_id', '=', $order_id)
            ->groupBy('orders.order_id')
            ->first();

        // Update Order Table
        if($order){
            $update_order = DB::table('orders')
                                ->where('order_id', $order_id)
                                ->update([
                                    'total_product' => $order->total_product,
                                    'subtotal' => $order->total,
                                    'total' => $order->total
                                ]);
        }

        return 1;

    }

}
