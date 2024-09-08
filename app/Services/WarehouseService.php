<?php

namespace App\Services;

// use App\Helper\Helper;

use App\Models\Wrhs;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WarehouseService{

    public function list($request){
        
        $search = $request->query('search', array('value' => '', 'regex' => false));
        $draw = $request->query('draw', 0);
        $start = $request->query('start', 0);
        $length = $request->query('length', 25);
        $order = $request->query('order', array(1, 'asc'));

        $filter = $search['value'];    
    
        $query = Wrhs::select(['name','active','is_show','id','wrhs.created_at']);
    
        if (!empty($filter)) {
            $query
            ->orWhere('name', 'like', '%'.$filter.'%');
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
                    "active"        => $value->active,
                    "is_show"       => $value->is_show,
                    "created_at"    => ($value->created_at),
                ];
        }

        return $json;
    }

}