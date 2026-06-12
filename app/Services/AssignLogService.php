<?php

namespace App\Services;

use App\Models\AssignLog;

class AssignLogService
{

    public function getAllAssignLogs($per_page = -1)
    {
        if($per_page == -1){
            return AssignLog::orderBy('created_at')->get();    
        }
        return AssignLog::orderBy('created_at')->paginate($per_page);
    }

    public function getAssignLogById($id)
    {
        return AssignLog::find($id);
    }

    public function create($data)
    {
        return AssignLog::create($data);
    }

    public function update($assign_log, $data)
    {
        return $assign_log->update($data);
    }

    public function delete($assign_log)
    {
        return $assign_log->delete($assign_log);
    }
}
