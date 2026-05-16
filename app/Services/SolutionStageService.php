<?php

namespace App\Services;

use App\Models\SolutionStage;

class SolutionStageService
{

    public function getAllSolutionStages($per_page = -1)
    {
        if($per_page == -1){
            return SolutionStage::withCount('complains')->orderBy('created_at', 'asc')->get();    
        }
        return SolutionStage::withCount('complains')->orderBy('created_at', 'asc')->paginate($per_page);
    }

    public function getSolutionStageById($id)
    {
        return SolutionStage::find($id);
    }

    public function create($data)
    {
        return SolutionStage::create($data);
    }

    public function update($solution_stages, $data)
    {
        return $solution_stages->update($data);
    }

    public function delete($solution_stages)
    {
        return $solution_stages->delete($solution_stages);
    }

    public function getAllSolutionStagesByUserAssign($user_id)
    {
        $query = SolutionStage::withCount([
        'complains' => function ($q) use ($user_id) {
            $q->where(function ($query) use ($user_id) {
                $query->where('user_id', $user_id)
                      ->orWhere('assign_id', $user_id);
            });
        }
        ])->orderBy('created_at', 'asc');
        return $query->get();
    }
}
