<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UploadImageService;
use App\Services\UserService;
use App\Services\ComplainService;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $imageService, $userService, $complainService;

    public function __construct (
        UploadImageService $imageService,
        UserService $userService,
        ComplainService $complainService
    )
    {
        $this->imageService = $imageService;
        $this->userService = $userService;
        $this->complainService = $complainService;
    }

    public function index(Request $request)
    {
        $user_id = Auth::user()->id;
        $complains = $this->complainService->getComplainsByUser($user_id);
        return view('users.index')->with('complains', $complains);
    }

    public function viewComplain(Request $request, $id)
    {
        try{
            $complain = $this->complainService->getComplainById($id);
            if(!$complain){
                throw new BadRequestException('Invalid Request id');
            }
            return view('users.view')->with('complain', $complain);
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('users.index');
        }
    }
}