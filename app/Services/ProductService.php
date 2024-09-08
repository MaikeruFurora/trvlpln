<?php

namespace App\Services;

// use App\Helper\Helper;

use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductService{

    public function list($request){
        
        $search = $request->query('search', array('value' => '', 'regex' => false));
        $draw = $request->query('draw', 0);
        $start = $request->query('start', 0);
        $length = $request->query('length', 25);
        $order = $request->query('order', array(1, 'asc'));

        $filter = $search['value'];    
    
        $query = Product::select(['.products.id','products.name','groups.name as group_name','code','products.id','group_id','products.created_at'])
        ->join('groups','products.group_id','groups.id');
    
        if (!empty($filter)) {
            $query
            ->orWhere('products.name', 'like', '%'.$filter.'%')
            ->orWhere('code', 'like', '%'.$filter.'%');
        }
    
        $recordsTotal = $query->count();
    
    
        $query->take($length)->skip($start);

    
        $json = array(
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => [],
        );
    
        $products = $query->get();

        foreach ($products as $value) {
           
                $json['data'][] = [
                    "id"            => $value->id,
                    "name"          => $value->name,
                    "code"          => $value->code,
                    "group_name"    => $value->group_name,
                    "group_id"      => $value->group_id,
                    "created_at"    => ($value->created_at),
                ];
        }

        return $json;
    }

}