<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UploadImageService;
use App\Services\UserService;
use App\Services\ComplainIssueService;
use App\Services\SolutionStageService;
use App\Services\ProductService;
use App\Services\ComplainService;
use App\Services\ComplainPhotosService;
use App\Models\Complain;
use App\Models\ComplainIssueProduct;
use App\Models\ComplainReceiveProduct;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    private $imageService, $userService, $complainIssueService, $solutionStageService, $productService, $complainService, $complainPhotosService;

    public function __construct (
        UploadImageService $imageService,
        UserService $userService,
        ComplainIssueService $complainIssueService,
        SolutionStageService $solutionStageService,
        ProductService $productService,
        ComplainService $complainService,
        ComplainPhotosService $complainPhotosService
    )
    {
        $this->imageService = $imageService;
        $this->userService = $userService;
        $this->complainIssueService = $complainIssueService;
        $this->solutionStageService = $solutionStageService;
        $this->productService = $productService;
        $this->complainService = $complainService;
        $this->complainPhotosService = $complainPhotosService;
    }

    public function index(Request $request)
    {
        $user_id = Auth::user()->id;
        $solutions = $this->solutionStageService->getAllSolutionStagesByUserAssign($user_id);
        return view('services.index')->with('solutions', $solutions);
    }
    public function getComplains(Request $request)
    {
        $user_id = Auth::user()->id;
        $status_id = "";
        $issues = $this->complainIssueService->getAllComplainIssues();
        $solutions = $this->solutionStageService->getAllSolutionStages();
        if( $request->has('status') ) {
            $status_id = $request->input('status');
        }
        $complains = $this->complainService->getComplainsByUserAssign($user_id, $status_id);
        return view('services.complains')->with('issues', $issues)->with('solutions', $solutions)->with('complains', $complains)->with('status_id', $status_id);
    }
    public function fetchComplainsByFilter(Request $request)
    {
        $user_id = Auth::user()->id;
        $complains = $this->complainService->getAllComplainsByFilterByUserAssign($request, $user_id);
        return view('services.list')->with('complains', $complains)->render();
    }
    public function editComplain(Request $request, $id)
    {
        try{
            $complain = Complain::with(['issueProducts', 'receiveProducts'])->find($id);
            if(!$complain){
                throw new BadRequestException('Invalid Request id');
            }
            $issues = $this->complainIssueService->getAllComplainIssues();
            $solutions = $this->solutionStageService->getAllSolutionStages();
            $products = $this->productService->getAllProducts();
            $users = $this->userService->getAllUsers();
            return view('services.edit')->with('complain', $complain)->with('issues', $issues)->with('solutions', $solutions)->with('products', $products)->with('users', $users);
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('services.complains');
        }
    }
    public function updateComplain(Request $request)
    {
        try{
            $complain = $this->complainService->getComplainById($request->id);
            if(!$complain){
                throw new BadRequestException('Invalid Request id');
            }
            $data['assign_id'] = $request->assign;
            $data['contact_name'] = $request->name;
            $data['contact_number'] = $request->phone;
            $data['complain_issue_id'] = $request->complain_issue;
            $data['company_name'] = $request->company_name;
            $data['estimation_cost'] = $request->estimation_cost;
            $data['solution_id'] = $request->solution_status;
            $data['message'] = $request->message;
            if($request->has('video')){
                $filepath = public_path('assets/' . $complain->complain_video);
                $this->imageService->deleteFile($filepath);
                $videofilename = $this->imageService->uploadFile($request->video, "assets/complain");
                $data['complain_video'] = '/complain/'.$videofilename;
            }
            $this->complainService->update($complain, $data);
            $issueExistingIds = [];
            if ($request->issue_product) {
                foreach ($request->issue_product as $key => $productId) {
                    if (!$productId) {
                        continue;
                    }
                    $saveData = [
                        'complain_id' => $complain->id,
                        'product_id' => $productId,
                        'product_number' => $request->issue_product_number[$key] ?? null,
                        'issue_date' => !empty($request->issue_date1[$key]) ? date('Y-m-d',strtotime(str_replace('/','-',$request->issue_date1[$key]))) : null,
                        'receive_date' => !empty($request->receive_date1[$key]) ? date('Y-m-d',strtotime(str_replace('/','-',$request->receive_date1[$key]))) : null,
                    ];
                    $rowId = $request->issue_row_id[$key] ?? null;
                    if ($rowId) {
                        $issueRow = ComplainIssueProduct::find($rowId);
                        if ($issueRow) {
                            $issueRow->update($saveData);
                            $issueExistingIds[] = $issueRow->id;
                        }
                    } else {
                        $newIssueRow = ComplainIssueProduct::create($saveData);
                        $issueExistingIds[] = $newIssueRow->id;
                    }
                }
            }
            ComplainIssueProduct::where('complain_id', $complain->id)->whereNotIn('id', $issueExistingIds)->delete();
            $receiveExistingIds = [];
            if ($request->receive_product) {
                foreach ($request->receive_product as $key => $productId) {
                    if (!$productId) {
                        continue;
                    }
                    $saveData = [
                        'complain_id' => $complain->id,
                        'product_id' => $productId,
                        'product_number' => $request->receive_product_number[$key] ?? null,
                        'receive_date' => !empty($request->receive_date2[$key]) ? date('Y-m-d',strtotime(str_replace('/','-',$request->receive_date2[$key]))) : null,
                        'issue_date' => !empty($request->issue_date2[$key]) ? date('Y-m-d',strtotime(str_replace('/','-',$request->issue_date2[$key]))) : null,
                    ];
                    $rowId = $request->receive_row_id[$key] ?? null;
                    if ($rowId) {
                        $receiveRow = ComplainReceiveProduct::find($rowId);
                        if ($receiveRow) {
                            $receiveRow->update($saveData);
                            $receiveExistingIds[] = $receiveRow->id;
                        }
                    } else {
                        $newReceiveRow = ComplainReceiveProduct::create($saveData);
                        $receiveExistingIds[] = $newReceiveRow->id;
                    }
                }
            }
            ComplainReceiveProduct::where('complain_id', $complain->id)->whereNotIn('id', $receiveExistingIds)->delete();
            if($request->has('image')){
                $data['complain_id'] = $request->id;
                foreach($request->image as $img) {
                    $filename = $this->imageService->uploadFile($img, "assets/complain");
                    $data['image'] = '/complain/'.$filename;
                    $this->complainPhotosService->create($data);
                }
            }
            $request->session()->put('message', 'Complain has been updated successfully.');
            $request->session()->put('alert-type', 'alert-success');
            return redirect()->route('services.complains');
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('services.complains');
        }
    }
}