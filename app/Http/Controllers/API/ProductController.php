<?php
   
namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Product;
use Validator;
use App\Http\Resources\Product as ProductResource;
use Illuminate\Support\Facades\DB;

class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::leftJoin('product_status', 'products.product_status_id', '=', 'product_status.product_status_id')
        ->leftJoin('category', 'products.category_id', '=', 'category.category_id')
        ->select('products.*', 'category.category_name', 'product_status.product_status_name')->get();

        return $this->sendResponse(ProductResource::collection($products), 'Products displayed successfully.');
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
            'title' => 'required',
            'description' => 'required',
            'sku' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'category_id' => 'required',
            'product_status_id' => 'required',
        ]);


        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        // Check if product name already exist
        $check_product = Product::where('title', $input['title'])->first();
        if($check_product){
            return $this->sendError('Product already exist.');
        }

        // Check if sku already exist
        $check_sku = Product::where('sku', $input['sku'])->first();
        if($check_sku){
            return $this->sendError('SKU already exist.');
        }

        $product = Product::create($input);
   
        return $this->sendResponse(new ProductResource($product), 'Product created successfully.');
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $product_id
     * @return \Illuminate\Http\Response
     */
    public function show($product_id)
    {
        $product = Product::find($product_id);
  
        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }
   
        return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully.');
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $product_id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required',
            'sku' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'category_id' => 'required',
            'product_status_id' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $product->title = $input['title'];
        $product->description = $input['description'];
        $product->sku = $input['sku'];
        $product->price = $input['price'];
        $product->quantity = $input['quantity'];
        $product->product_status_id = $input['product_status_id'];
        $product->category_id = $input['category_id'];
        $product->save();
   
        return $this->sendResponse(new ProductResource($product), 'Product updated successfully.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $product_id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
   
        return $this->sendResponse([], 'Product deleted successfully.');
    }
}