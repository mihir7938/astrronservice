<?php

namespace App\Services;

use App\Models\ComplainPhoto;

class ComplainPhotosService
{

    public function getAllComplainPhotos($per_page = -1)
    {
        if($per_page == -1){
            return ComplainPhoto::orderBy('created_at', 'desc')->get();    
        }
        return ComplainPhoto::orderBy('created_at', 'desc')->paginate($per_page);
    }

    public function getComplainPhotoById($id)
    {
        return ComplainPhoto::find($id);
    }

    public function create($data)
    {
        return ComplainPhoto::create($data);
    }

    public function update($complain_photo, $data)
    {
        return $complain_photo->update($data);
    }

    public function delete($complain_photo)
    {
        return $complain_photo->delete($complain_photo);
    }
    
}
