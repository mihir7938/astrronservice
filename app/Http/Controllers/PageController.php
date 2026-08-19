<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UploadImageService;
use App\Services\UserService;
use App\Services\ComplainIssueService;
use App\Services\ComplainService;
use App\Services\ComplainPhotosService;
use App\Services\AssignLogService;
use App\Services\WhatsappService;
use App\Models\User;
use App\Models\WhatsappMessage;
use App\Jobs\SendWhatsappMessageJob;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Illuminate\Support\Facades\Auth;

class PageController extends Controller {

	private $imageService, $userService, $complainIssueService, $complainService, $complainPhotosService, $assignLogService, $whatsappService;

    public function __construct(
        UploadImageService $imageService,
        UserService $userService,
        ComplainIssueService $complainIssueService,
        ComplainService $complainService,
        ComplainPhotosService $complainPhotosService,
        AssignLogService $assignLogService,
        WhatsappService $whatsappService
    )
    {
        $this->imageService = $imageService;
        $this->userService = $userService;
        $this->complainIssueService = $complainIssueService;
        $this->complainService = $complainService;
        $this->complainPhotosService = $complainPhotosService;
        $this->assignLogService = $assignLogService;
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
        if(Auth::user()->isAdmin()) {
            if($request->assign) {
                $log_data['complain_id'] = $complain_id;
                $log_data['user_id'] = Auth::user()->id;
                $log_data['assign_from'] = Auth::user()->id;
                $log_data['assign_to'] = $request->assign;
                $log_data['date'] = date('Y-m-d H:i:s');
                $this->assignLogService->create($log_data);
            }
        }
        $complain = $this->complainService->getComplainById($complain_id);
        $message = WhatsappMessage::create([
            'wa_id'         => env('ADMIN_MOBILE'),
            'from_number'   => env('WHATSAPP_PHONE_NUMBER'),
            'to_number'     => env('ADMIN_MOBILE'),
            'direction'     => 'outgoing',
            'type'          => 'template',
            'template_name' => env('NEW_COMPLAINT_TEMPLATE'),
            'parameters'    => [
                'staff_name' => Auth::user()->name,
                'complain_no'    => $complain_id,
                'customer_name'=> $request->name,
                'customer_mobile' => $request->phone,
                'company_name'  => $request->company_name,
                'address'  => $request->company_address,
                'complain'  => $complain->issue->name,
            ],
            'status'        => 'pending'
        ]);
        SendWhatsappMessageJob::dispatch($message->id);
        if(Auth::user()->isAdmin()) {
            if($request->assign) {
                $assignedUser = $this->userService->getUserById($request->assign);
                $message2 = WhatsappMessage::create([
                    'wa_id'         => '91' . $assignedUser->phone,
                    'from_number'   => env('WHATSAPP_PHONE_NUMBER'),
                    'to_number'     => '91' . $assignedUser->phone,
                    'direction'     => 'outgoing',
                    'type'          => 'template',
                    'template_name' => env('ASSIGN_COMPLAINT_TEMPLATE'),
                    'parameters'    => [
                        'staff_name' => $assignedUser->name,
                        'customer_name'=> $request->name,
                        'customer_mobile' => $request->phone,
                        'address'  => $request->company_address,
                        'complain'  => $complain->issue->name,
                        'message'  => $request->message,
                    ],
                    'status'        => 'pending'
                ]);
                SendWhatsappMessageJob::dispatch($message2->id);
            }
        }
        $request->session()->put('message', 'Complain has been generated successfully.');
        $request->session()->put('alert-type', 'alert-success');
        return redirect()->route('complain');
    }
}