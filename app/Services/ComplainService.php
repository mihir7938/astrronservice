<?php

namespace App\Services;

use App\Models\Complain;

class ComplainService
{

    public function getAllComplains($per_page = -1)
    {
        if($per_page == -1){
            return Complain::orderBy('created_at', 'desc')->get();    
        }
        return Complain::orderBy('created_at', 'desc')->paginate($per_page);
    }

    public function getComplainById($id)
    {
        return Complain::find($id);
    }

    public function create($data)
    {
        return Complain::create($data);
    }

    public function update($complains, $data)
    {
        return $complains->update($data);
    }

    public function delete($complains)
    {
        return $complains->delete($complains);
    }

    public function getComplainsByStatus($status_id)
    {
        return Complain::where('solution_id', $status_id)->orderBy('created_at','desc')->get();
    }

    public function getAllComplainsByFilter($request)
    {
        $query = Complain::orderBy('created_at', 'desc');
        if($request->has('issue_id') && $request->issue_id != ''){
            $query = $query->where('complain_issue_id', $request->issue_id);
        }
        if($request->has('solution_id') && $request->solution_id != ''){
            $query = $query->where('solution_id', $request->solution_id);
        }
        if($request->start_date && $request->end_date){
            $startDate = date("Y-m-d", strtotime(str_replace('/', '-', $request->start_date)));
            $endDate = date("Y-m-d", strtotime(str_replace('/', '-', $request->end_date)));
            $query = $query->whereBetween('complain_date', [$startDate, $endDate]);
        }
        return $query->select('*')->get();
    }

    public function getComplainNumber()
    {
        $complain_number = rand(100000,999999);
        $check_complain_number_exists = Complain::where('complain_number', $complain_number)->exists();
        if($check_complain_number_exists) {
            $complain_number = rand(100000,999999);
        }
        return $complain_number;
    }

    public function getComplainsByUserAssign($user_id, $status_id)
    {
        $query = Complain::where(function ($q) use ($user_id) {
            $q->where('user_id', $user_id)
              ->orWhere('assign_id', $user_id);
        })
        ->orderBy('created_at', 'desc');
        if($status_id != ''){
            $query = $query->where('solution_id', $status_id);
        }
        return $query->get();
    }

    public function getAllComplainsByFilterByUserAssign($request, $user_id)
    {
        $query = Complain::where(function ($q) use ($user_id) {
            $q->where('user_id', $user_id)
              ->orWhere('assign_id', $user_id);
        })
        ->orderBy('created_at', 'desc');
        if($request->has('issue_id') && $request->issue_id != ''){
            $query = $query->where('complain_issue_id', $request->issue_id);
        }
        if($request->has('solution_id') && $request->solution_id != ''){
            $query = $query->where('solution_id', $request->solution_id);
        }
        if($request->start_date && $request->end_date){
            $startDate = date("Y-m-d", strtotime(str_replace('/', '-', $request->start_date)));
            $endDate = date("Y-m-d", strtotime(str_replace('/', '-', $request->end_date)));
            $query = $query->whereBetween('complain_date', [$startDate, $endDate]);
        }
        return $query->select('*')->get();
    }

    public function getComplainsByUser($user_id)
    {
        return Complain::where('user_id', $user_id)->orderBy('created_at','desc')->get();
    }
}
