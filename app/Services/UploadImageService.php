<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\File;


class UploadImageService
{
    public function saveImage($request, $path = '/public/uploads')
    {
        // $this->validateFile($request);
        $file = $request->file('file');
        $url = $file->store($path);

        return $url;
    }

    public function createUpdateImage($request, $path = '/public/uploads', $existingFile = '')
    {
        if($existingFile != ''){
            $this->deleteFile($existingFile);
        }

        return $this->saveImage($request, $path);
    }

    public function deleteFile($path)
    {
        if (File::exists($path)) {
            File::delete($path);
            return true;
        }

        return false;
    }


    public function uploadFile($file, $path, $existingFile = '')
    {
        if ($existingFile) {
            $this->deleteFile($existingFile);
        }

        $extension = $file->getClientOriginalExtension();
        $fname = $file->getClientOriginalName().time();
        $filename = pathinfo($fname, PATHINFO_FILENAME).time().'.'.$extension;
        $url = $file->move(public_path($path), $filename);


        return $filename;
    }

    private function validateFile($request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:jpeg,jpg,png|max:2048',
            ],
            [
                'file.mimes' => 'Please upload image in jpeg, jpg or png format',
                'file.max' => 'The uploaded image size is too large. Please upload a file that is smaller than 2MB',
            ]
        );
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}