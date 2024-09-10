<?php

namespace App\Services;

// use App\Helper\Helper;

use App\Models\Group;
use App\Models\HandleGroup;
use App\Models\User;
use App\Models\UserGroup;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserGroupService{

    public function list($request){
        
        $search = $request->query('search', array('value' => '', 'regex' => false));
        $draw = $request->query('draw', 0);
        $start = $request->query('start', 0);
        $length = $request->query('length', 25);
        $order = $request->query('order', array(1, 'asc'));

        $filter = $search['value'];    
    
        $query = UserGroup::select(['user_groups.id','handle_groups.handle_group_name','handle_groups.handle_group_id','handle_groups.active','handle_groups.created_at','user_id','users.name as user_name'])
        ->join('users','handle_groups.user_id','users.id')
        ->join('handle_groups as hg','hg.id','handle_groups.group_id');
    
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
                    "user_name"     => $value->user_name,
                    "user_id"       => $value->user_id,
                    "active"        => $value->active,
                    "created_at"    => ($value->created_at),
                ];
        }

        return $json;
    }

}