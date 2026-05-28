<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UploadImageService;
use App\Services\UserService;
use App\Services\ComplainIssueService;
use App\Services\ComplainService;
use App\Services\ComplainPhotosService;
use App\Services\WhatsappService;
use App\Models\User;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller {

	private $imageService, $userService, $complainIssueService, $complainService, $complainPhotosService, $whatsappService;

    public function __construct(
        UploadImageService $imageService,
        UserService $userService,
        ComplainIssueService $complainIssueService,
        ComplainService $complainService,
        ComplainPhotosService $complainPhotosService,
        WhatsappService $whatsappService
    )
    {
        $this->imageService = $imageService;
        $this->userService = $userService;
        $this->complainIssueService = $complainIssueService;
        $this->complainService = $complainService;
        $this->complainPhotosService = $complainPhotosService;
        $this->whatsappService = $whatsappService;
    }

    public function complain(Request $request)
    {
        $issues = $this->complainIssueService->getAllComplainIssues();
        $users = $this->userService->getAllUsers();
        return view('complain')->with('issues', $issues)->with('users', $users);
    }

    public function saveComplain(Request $request)
    {
        $data = $request->all();
        $complain_number = $this->complainService->getComplainNumber();
        $data['complain_number'] = $complain_number;
        $data['user_id'] = Auth::user()->id;
        $data['assign_id'] = $request->assign;
        $data['contact_name'] = $request->name;
        $data['contact_number'] = $request->phone;
        $data['complain_issue_id'] = $request->complain_issue;
        $data['company_name'] = $request->company_name;
        $data['company_address'] = $request->company_address;
        $data['message'] = $request->message;
        $data['complain_date'] = date('Y-m-d');
        if($request->has('upload_video')){
            $videofilename = $this->imageService->uploadFile($request->upload_video, "assets/complain");
            $data['complain_video'] = '/complain/'.$videofilename;
        }
        $complain_data = $this->complainService->create($data);
        $complain_id = $complain_data->id;
        if($request->has('upload_image')){
            $data['complain_id'] = $complain_id;
            foreach($request->upload_image as $img) {
                $filename = $this->imageService->uploadFile($img, "assets/complain");
                $data['image'] = '/complain/'.$filename;
                $this->complainPhotosService->create($data);
            }
        }
        $message_type = "text";
        $to_number = '91'.$request->phone;
        $message = '>>> *New Complain* <<<';
        $message .= '\n\n*Complain No* : '.$complain_id;
        $response = $this->whatsappService->sendMessage($message_type, $to_number, $message);
        $request->session()->put('message', 'Complain has been generated successfully.');
        $request->session()->put('alert-type', 'alert-success');
        return redirect()->route('complain');
    }
}