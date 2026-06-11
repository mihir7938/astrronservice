<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Services\UploadImageService;
use App\Services\UserService;
use App\Services\ComplainIssueService;
use App\Services\SolutionStageService;
use App\Services\ProductService;
use App\Services\ComplainService;
use App\Services\ComplainPhotosService;
use App\Services\WhatsappService;
use App\Models\User;
use App\Models\Complain;
use App\Models\ComplainPhoto;
use App\Models\ComplainIssueProduct;
use App\Models\ComplainReceiveProduct;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller {

	private $imageService, $userService, $complainIssueService, $solutionStageService, $productService, $complainService, $complainPhotosService, $whatsappService;

    public function __construct(
        UploadImageService $imageService,
        UserService $userService,
        ComplainIssueService $complainIssueService,
        SolutionStageService $solutionStageService,
        ProductService $productService,
        ComplainService $complainService,
        ComplainPhotosService $complainPhotosService,
        WhatsappService $whatsappService
    )
    {
        $this->imageService = $imageService;
        $this->userService = $userService;
        $this->complainIssueService = $complainIssueService;
        $this->solutionStageService = $solutionStageService;
        $this->productService = $productService;
        $this->complainService = $complainService;
        $this->complainPhotosService = $complainPhotosService;
        $this->whatsappService = $whatsappService;
    }

    public function index(Request $request)
    {
        $all_complains = Complain::count(); 
        $solutions = $this->solutionStageService->getAllSolutionStages();
        return view('admin.index')->with('all_complains', $all_complains)->with('solutions', $solutions);
    }
    public function getComplains(Request $request)
    {
        $issues = $this->complainIssueService->getAllComplainIssues();
        $solutions = $this->solutionStageService->getAllSolutionStages();
        $status_id = "";
        if( $request->has('status') ) {
            $status_id = $request->input('status');
            $complains = $this->complainService->getComplainsByStatus($status_id);
        } else {
            $complains = $this->complainService->getAllComplains();
        }
        return view('admin.complains.index')->with('issues', $issues)->with('solutions', $solutions)->with('complains', $complains)->with('status_id', $status_id);
    }
    public function fetchComplainsByFilter(Request $request)
    {
        $complains = $this->complainService->getAllComplainsByFilter($request);
        return view('admin.complains.list')->with('complains', $complains)->render();
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
            return view('admin.complains.edit')->with('complain', $complain)->with('issues', $issues)->with('solutions', $solutions)->with('products', $products)->with('users', $users);
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.complains');
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
            $data['company_address'] = $request->company_address;
            $data['estimation_cost'] = $request->estimation_cost;
            $data['solution_id'] = $request->solution_status;
            $data['message'] = $request->message;
            if($request->has('video')){
                $filepath = public_path('assets/' . $complain->complain_video);
                $this->imageService->deleteFile($filepath);
                $videofilename = $this->imageService->uploadFile($request->video, "assets/complain");
                $data['complain_video'] = '/complain/'.$videofilename;
            }
            if($request->has('bill')){
                $billfilepath = public_path('assets/' . $complain->bill);
                $this->imageService->deleteFile($billfilepath);
                $billfilename = $this->imageService->uploadFile($request->bill, "assets/complain");
                $data['bill'] = '/complain/'.$billfilename;
            }
            $data['followup_remarks_1'] = $request->followup_remarks_1;
            $data['followup_date_1'] = NULL;
            if($request->followup_date_1) {
                $data['followup_date_1'] = date("Y-m-d", strtotime(str_replace('/', '-', $request->followup_date_1)));
            }
            $data['followup_remarks_2'] = $request->followup_remarks_2;
            $data['followup_date_2'] = NULL;
            if($request->followup_date_2) {
                $data['followup_date_2'] = date("Y-m-d", strtotime(str_replace('/', '-', $request->followup_date_2)));
            }
            $data['followup_remarks_3'] = $request->followup_remarks_3;
            $data['followup_date_3'] = NULL;
            if($request->followup_date_3) {
                $data['followup_date_3'] = date("Y-m-d", strtotime(str_replace('/', '-', $request->followup_date_3)));
            }
            $data['followup_remarks_4'] = $request->followup_remarks_4;
            $data['followup_date_4'] = NULL;
            if($request->followup_date_4) {
                $data['followup_date_4'] = date("Y-m-d", strtotime(str_replace('/', '-', $request->followup_date_4)));
            }
            $data['followup_remarks_5'] = $request->followup_remarks_5;
            $data['followup_date_5'] = NULL;
            if($request->followup_date_5) {
                $data['followup_date_5'] = date("Y-m-d", strtotime(str_replace('/', '-', $request->followup_date_5)));
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
            return redirect()->route('admin.complains');
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.complains');
        }
    }
    public function deleteComplain(Request $request, $id)
    {
        try{
            $complain = $this->complainService->getComplainById($id);
            if(!$complain){
                throw new BadRequestException('Invalid Request id.');
            }
            $complain->deleted_by = Auth::user()->id;
            $complain->save();
            $this->complainService->delete($complain);
            $request->session()->put('message', 'Complain has been deleted successfully.');
            $request->session()->put('alert-type', 'alert-success');
            return redirect()->route('admin.complains');
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.complains');
        }
    }
    public function deleteImage(Request $request)
    {
        $photo = ComplainPhoto::find($request->id);
        if (!$photo) {
            return response()->json(['error' => 'Image not found'], 404);
        }
        $path = public_path('assets/' . $photo->image);
        if (file_exists($path)) {
            $this->imageService->deleteFile($path);
        }
        $photo->delete();
        return response()->json(['success' => true]);
    }
    public function deletedComplains()
    {
        $complains = Complain::onlyTrashed()->get();
        return view('admin.complains.deleted')->with('complains', $complains);
    }
    public function issues(Request $request)
    {
        $issues = $this->complainIssueService->getAllComplainIssues();
        return view('admin.issues.index')->with('issues', $issues);
    }
    public function addIssue(Request $request)
    {
        return view('admin.issues.add');
    }
    public function saveIssue(Request $request)
    {
        $data = $request->all();
        $data['name'] = $request->issue;
        $this->complainIssueService->create($data);
        $request->session()->put('message', 'Complain Issue has been added successfully.');
        $request->session()->put('alert-type', 'alert-success');
        return redirect()->route('admin.issues');
    }
    public function editIssue(Request $request, $id)
    {
        try{
            $issue = $this->complainIssueService->getComplainIssueById($id);
            if(!$issue){
                throw new BadRequestException('Invalid Request id');
            }
            return view('admin.issues.edit')->with('issue', $issue);
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.issues');
        }
    }
    public function updateIssue(Request $request)
    {
        try{
            $issue = $this->complainIssueService->getComplainIssueById($request->id);
            if(!$issue){
                throw new BadRequestException('Invalid Request id');
            }
            $data['name'] = $request->issue;
            $this->complainIssueService->update($issue, $data);
            $request->session()->put('message', 'Complain Issue has been updated successfully.');
            $request->session()->put('alert-type', 'alert-success');
            return redirect()->route('admin.issues');
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.issues');
        }
    }
    public function deleteIssue(Request $request, $id)
    {
        try{
            $issue = $this->complainIssueService->getComplainIssueById($id);
            if(!$issue){
                throw new BadRequestException('Invalid Request id.');
            }
            $this->complainIssueService->delete($issue);
            $request->session()->put('message', 'Complain Issue has been deleted successfully.');
            $request->session()->put('alert-type', 'alert-success');
            return redirect()->route('admin.issues');
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.issues');
        }
    }
    public function solutions(Request $request)
    {
        $solutions = $this->solutionStageService->getAllSolutionStages();
        return view('admin.solutions.index')->with('solutions', $solutions);
    }
    public function addSolution(Request $request)
    {
        return view('admin.solutions.add');
    }
    public function saveSolution(Request $request)
    {
        $data = $request->all();
        $data['name'] = $request->solution_stage;
        $this->solutionStageService->create($data);
        $request->session()->put('message', 'Solution Stage has been added successfully.');
        $request->session()->put('alert-type', 'alert-success');
        return redirect()->route('admin.solutions');
    }
    public function editSolution(Request $request, $id)
    {
        try{
            $solution_stage = $this->solutionStageService->getSolutionStageById($id);
            if(!$solution_stage){
                throw new BadRequestException('Invalid Request id');
            }
            return view('admin.solutions.edit')->with('solution_stage', $solution_stage);
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.solutions');
        }
    }
    public function updateSolution(Request $request)
    {
        try{
            $solution_stage = $this->solutionStageService->getSolutionStageById($request->id);
            if(!$solution_stage){
                throw new BadRequestException('Invalid Request id');
            }
            $data['name'] = $request->solution_stage;
            $this->solutionStageService->update($solution_stage, $data);
            $request->session()->put('message', 'Solution Stage has been updated successfully.');
            $request->session()->put('alert-type', 'alert-success');
            return redirect()->route('admin.solutions');
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.solutions');
        }
    }
    public function deleteSolution(Request $request, $id)
    {
        try{
            $solution_stage = $this->solutionStageService->getSolutionStageById($id);
            if(!$solution_stage){
                throw new BadRequestException('Invalid Request id.');
            }
            $this->solutionStageService->delete($solution_stage);
            $request->session()->put('message', 'Solution Stage has been deleted successfully.');
            $request->session()->put('alert-type', 'alert-success');
            return redirect()->route('admin.solutions');
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.solutions');
        }
    }
    public function products(Request $request)
    {
        $products = $this->productService->getAllProducts();
        return view('admin.products.index')->with('products', $products);
    }
    public function addProduct(Request $request)
    {
        return view('admin.products.add');
    }
    public function saveProduct(Request $request)
    {
        $data = $request->all();
        $data['name'] = $request->name;
        $this->productService->create($data);
        $request->session()->put('message', 'Product has been added successfully.');
        $request->session()->put('alert-type', 'alert-success');
        return redirect()->route('admin.products');
    }
    public function editProduct(Request $request, $id)
    {
        try{
            $product = $this->productService->getProductById($id);
            if(!$product){
                throw new BadRequestException('Invalid Request id');
            }
            return view('admin.products.edit')->with('product', $product);
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.products');
        }
    }
    public function updateProduct(Request $request)
    {
        try{
            $product = $this->productService->getProductById($request->id);
            if(!$product){
                throw new BadRequestException('Invalid Request id');
            }
            $data['name'] = $request->name;
            $this->productService->update($product, $data);
            $request->session()->put('message', 'Product has been updated successfully.');
            $request->session()->put('alert-type', 'alert-success');
            return redirect()->route('admin.products');
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.products');
        }
    }
    public function deleteProduct(Request $request, $id)
    {
        try{
            $product = $this->productService->getProductById($id);
            if(!$product){
                throw new BadRequestException('Invalid Request id.');
            }
            $this->productService->delete($product);
            $request->session()->put('message', 'Product has been deleted successfully.');
            $request->session()->put('alert-type', 'alert-success');
            return redirect()->route('admin.products');
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.products');
        }
    }
    public function getUsers()
    {
        $users = $this->userService->getAllUsers();
        return view('admin.users.index')->with('users', $users);
    }
    public function fetchUsers(Request $request)
    {
        $users = $this->userService->getUsersByFilter($request);
        return view('admin.users.result')->with('users', $users)->render();
    }
    public function addUser()
    {
        return view('admin.users.add');
    }
    public function saveUser(RegisterRequest $request)
    {
        $user = $this->userService->create($request);
        $request->session()->put('message', 'User has been added successfully.');
        $request->session()->put('alert-type', 'alert-success');
        return redirect()->route('admin.users');
    }
    public function editUser(Request $request, $id)
    {
        try{
            $user = $this->userService->getUserById($id);
            if(!$user){
                throw new BadRequestException('Invalid Request id');
            }
            return view('admin.users.edit')->with('user', $user);
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.users');
        }
    }
    public function updateUser(Request $request)
    {
        try{
            $user = $this->userService->getUserById($request->id);
            if(!$user){
                throw new BadRequestException('Invalid Request id');
            }
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['role_id'] = $request->type;
            if($user->isUser() || $user->isService()) {
                $data['status'] = $request->active;
            }
            $this->userService->update($user, $data);
            $request->session()->put('message', 'User has been updated successfully.');
            $request->session()->put('alert-type', 'alert-success');
            return redirect()->route('admin.users');
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.users');
        }
    }
    public function deleteUser(Request $request, $id)
    {
        try{
            $user = $this->userService->getUserById($id);
            if(!$user){
                throw new BadRequestException('Invalid Request id.');
            }
            $this->userService->delete($user);
            $request->session()->put('message', 'User has been deleted successfully.');
            $request->session()->put('alert-type', 'alert-success');
            return redirect()->route('admin.users');
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.users');
        }
    }
}