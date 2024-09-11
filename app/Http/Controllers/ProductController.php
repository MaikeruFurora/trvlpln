<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    public function index(){
        $groups = Group::active()->orderBy('name')->get(['id','name']);
        return view('admin.product.index',compact('groups'));
    }

    public function store(Request $request){
      
        $data = (empty($request->id)) 

        ? Product::storeProduct($request)

        : Product::updateProduct($request);

        if ($data) {
            return response()->json(['msg'=>'Successfully save data']);
        }
    }

    public function list(Request $request){

        return $this->productService->list($request);

    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        $products = Product::where('name', 'like', "%{$query}%")
            ->where('group_id', auth()->user()->group_id)
            ->limit(5)
            ->get(['id', 'name']); // Adjust fields as necessary

        return response()->json($products);
    }
}
