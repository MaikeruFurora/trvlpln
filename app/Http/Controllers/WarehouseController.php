<?php

namespace App\Http\Controllers;

use App\Models\Wrhs;
use App\Services\WarehouseService;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    protected $warehouseService;

    public function __construct(WarehouseService $warehouseService)
    {
        $this->warehouseService = $warehouseService;
    }
    public function index(){
        return view('admin.warehouse.index');
    }

    public function store(Request $request){
      
        $data = (empty($request->id)) 

        ? Wrhs::storeWarehouse($request)

        : Wrhs::updateWarehouse($request);

        if ($data) {
            return response()->json(['msg'=>'Successfully save data']);
        }
    }

    public function list(Request $request){

        return $this->warehouseService->list($request);

    }
}
