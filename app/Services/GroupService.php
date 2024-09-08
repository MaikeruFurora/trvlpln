<?php

namespace App\Services;

// use App\Helper\Helper;

use App\Models\Group;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupService{

    public function list($request){
        
        $search = $request->query('search', array('value' => '', 'regex' => false));
        $draw = $request->query('draw', 0);
        $start = $request->query('start', 0);
        $length = $request->query('length', 25);
        $order = $request->query('order', array(1, 'asc'));

        $filter = $search['value'];    
    
        $query = Group::select(['id','name','active','created_at']);
    
        if (!empty($filter)) {
            $query
            ->orWhere('name', 'like', '%'.$filter.'%')
            ->orWhere('active', 'like', '%'.$filter.'%');
        }
    
        $recordsTotal = $query->count();
    
    
        $query->take($length)->skip($start);

    
        $json = array(
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => [],
        );
    
        $Groups = $query->get();

        foreach ($Groups as $value) {
           
                $json['data'][] = [
                    "id"            => $value->id,
                    "name"          => $value->name,
                    "active"        => $value->active,
                    "created_at"    => ($value->created_at),
                ];
        }

        return $json;
    }

}