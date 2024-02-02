<?php

namespace App\Services;

// use App\Helper\Helper;

use App\Models\ActivityList;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ActivityListService{

    public function list($request){
        
        $search = $request->query('search', array('value' => '', 'regex' => false));
        $draw = $request->query('draw', 0);
        $start = $request->query('start', 0);
        $length = $request->query('length', 25);
        $order = $request->query('order', array(1, 'asc'));

        $filter = $search['value'];    
    
        $query = ActivityList::select(['name','is_disabled','color','id','created_at']);
    
        if (!empty($filter)) {
            $query
            ->orWhere('name', 'like', '%'.$filter.'%')
            ->orWhere('is_disabled', 'like', '%'.$filter.'%')
            ->orWhere('color', 'like', '%'.$filter.'%');
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
                    "is_disabled"   => $value->is_disabled,
                    "color"         => $value->color,
                    "created_at"    => ($value->created_at),
                ];
        }

        return $json;
    }

}