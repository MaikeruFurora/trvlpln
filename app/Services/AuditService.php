<?php

namespace App\Services;
use Illuminate\Support\Facades\DB;

class AuditService{

    public function list($request){

        $search = $request->query('search', array('value' => '', 'regex' => false));
        $draw = $request->query('draw', 0);
        $start = $request->query('start', 0);
        $length = $request->query('length', 25);
        $order = $request->query('order', array(1, 'asc'));        
    
        $filter = $search['value'];
    
        $sortColumns = array('audits.id','name','event','auditable_id','old_values','new_values','url','ip_address' );
    
        $query = DB::table('audits')->select(['name','event','audits.created_at','auditable_id','old_values','new_values','url','ip_address','user_agent','audits.id'])->join('users','audits.user_id','users.id');
    
        if (!empty($filter)) {
            $query
            ->where('name', 'like', '%'.$filter.'%')
            ->orwhere('event', 'like', '%'.$filter.'%')
            ->orwhere('auditable_id', 'like', '%'.$filter.'%')
            ->orwhere('old_values', 'like', '%'.$filter.'%')
            ->orwhere('new_values', 'like', '%'.$filter.'%')
            ->orwhere('ip_address', 'like', '%'.$filter.'%');
        }
    
        $recordsTotal = $query->count();
    
        $sortColumnName = $sortColumns[$order[0]['column']];
    
        $query->take($length)->skip($start);
        
        $json = array(
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => [],
        );
    
        $audit = $query->get();
    
        foreach ($audit as $value) {
    
            $json['data'][] = [
                'name'=>$value->name,
                'event'=>$value->event,
                'auditable_id'=>$value->auditable_id,
                'old_values'=>$value->old_values,
                'new_values'=>$value->new_values,
                'url'=>$value->url,
                'ip_address'=>$value->ip_address,
                'user_agent'=>$value->user_agent,
                'created_at'=>date('M j, Y | g:i a',strtotime($value->created_at)),
                'id'=>$value->id

            ];
        }

        return $json;
    }
}