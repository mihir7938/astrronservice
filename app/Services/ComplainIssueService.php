<?php

namespace App\Services;

use App\Models\ComplainIssue;

class ComplainIssueService
{

    public function getAllComplainIssues($per_page = -1)
    {
        if($per_page == -1){
            return ComplainIssue::orderBy('created_at', 'desc')->get();    
        }
        return ComplainIssue::orderBy('created_at', 'desc')->paginate($per_page);
    }

    public function getComplainIssueById($id)
    {
        return ComplainIssue::find($id);
    }

    public function create($data)
    {
        return ComplainIssue::create($data);
    }

    public function update($complain_issues, $data)
    {
        return $complain_issues->update($data);
    }

    public function delete($complain_issues)
    {
        return $complain_issues->delete($complain_issues);
    }
}
