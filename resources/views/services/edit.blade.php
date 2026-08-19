@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <div class="d-flex justify-content-between">
                        <h1 class="m-0">Complain Form (Complain No : {{$complain->complain_number}})</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" action="{{route('services.complains.update.save')}}" class="form" id="edit-complain-form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$complain->id}}" />
                        @include('shared.alert')
                        @if (count($errors) > 0)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Edit Complain (Complain No : {{$complain->complain_number}})</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Contact Name*</label>
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Contact Name" value="{{$complain->contact_name}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone">Contact Number*</label>
                                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Contact Number" value="{{$complain->contact_number}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company_name">Company Name*</label>
                                            <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company Name" value="{{$complain->company_name}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company_address">Company Address*</label>
                                            <input type="text" class="form-control" id="company_address" name="company_address" placeholder="Company Address" value="{{$complain->company_address}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="user">User/Register</label>
                                            <input type="text" class="form-control" id="user" name="user" value="{{$complain->user->name}}" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="assign">Assign*</label>
                                            <select id="assign" name="assign" class="form-control select2">
					                            <option value="">Select Assign</option>
					                            @foreach($users as $user)
                                                    @if($user->isService() || $user->isAdmin())
					                                   <option value="{{$user->id}}" @if($complain->assign_id == $user->id) selected @endif>{{$user->name}} - {{$user->role->name}}</option>
                                                    @endif
					                            @endforeach
					                        </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="priority">Priority</label>
                                            <select id="priority" name="priority" class="form-control">
                                                <option value="">Select Priority</option>
                                                <option value="High" @if($complain->priority == 'High') selected @endif>High</option>
                                                <option value="Medium" @if($complain->priority == 'Medium') selected @endif>Medium</option>
                                                <option value="Low" @if($complain->priority == 'Low') selected @endif>Low</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="complain_issue">Complain Details*</label>
                                            <select id="complain_issue" name="complain_issue" class="form-control">
                                                <option value="">Select</option>
                                                @foreach($issues as $issue)
                                                    <option value="{{$issue->id}}" @if($complain->complain_issue_id == $issue->id) selected @endif>{{$issue->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="solution_status">Solution Status*</label>
                                            <select id="solution_status" name="solution_status" class="form-control">
                                                <option value="">Select</option>
                                                @foreach($solutions as $solution)
                                                    <option value="{{$solution->id}}" @if($complain->solution_id == $solution->id) selected @endif>{{$solution->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="estimation_cost">Estimation Cost</label>
                                            <input type="text" class="form-control" id="estimation_cost" name="estimation_cost" placeholder="Estimation Cost" value="{{$complain->estimation_cost}}">
                                        </div>
                                    </div>
                                </div>
                                @if($complain->issueProducts->count() > 0 || $complain->receiveProducts->count() > 0)
                                    <input type="hidden" id="record_exists" value="1">
                                @else
                                    <input type="hidden" id="record_exists" value="0">
                                @endif
                                <div class="complain_products" @if($complain->solution_id == 1 || $complain->issueProducts->count() > 0 || $complain->receiveProducts->count() > 0) style="display: block;" @else style="display: none;" @endif>
                                    <div id="issue-product-wrapper" class="border border-dark bg-light px-3 py-2 mt-2 mb-3">
                                        @if($complain->issueProducts->count() > 0)
                                            @foreach($complain->issueProducts as $key => $row)
                                                <div class="row issue-product-row">
                                                    <input type="hidden" name="issue_row_id[]" value="{{$row->id}}">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            @if($key == 0)<label class="text-primary field-label">Issue Product</label>@endif
                                                            <select name="issue_product[]" class="form-control border border-primary">
                                                                <option value="">Select</option>
                                                                @foreach($products as $product)
                                                                    <option value="{{$product->id}}" @if($row->product_id == $product->id) selected @endif>{{$product->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            @if($key == 0)<label class="text-primary field-label">Issue Product Number</label>@endif
                                                            <input type="text" class="form-control border border-primary" name="issue_product_number[]" placeholder="Issue Product Number" value="{{$row->product_number}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            @if($key == 0)<label class="text-primary field-label">Issue Date</label>@endif
                                                            <input type="text" class="form-control border border-primary issue_date1" name="issue_date1[]" placeholder="Issue Date" value="{{ $row->issue_date ? \Carbon\Carbon::parse($row->issue_date)->format('d/m/Y') : '' }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                             @if($key == 0)<label class="text-primary field-label">Receive Date</label>@endif
                                                            <input type="text" class="form-control border border-primary receive_date1" name="receive_date1[]" placeholder="Receive Date"  value="{{ $row->receive_date ? \Carbon\Carbon::parse($row->receive_date)->format('d/m/Y') : '' }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <div class="form-group">
                                                            @if($key == 0)<label class="d-block invisible field-label">Buttons</label>@endif
                                                            <div class="d-flex">
                                                                <button type="button" class="btn btn-success issue-add-row mr-1">+</button>
                                                                <button type="button" class="btn btn-danger issue-remove-row">-</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="row issue-product-row">
                                                <input type="hidden" name="issue_row_id[]" value="">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="text-primary field-label">Issue Product</label>
                                                        <select name="issue_product[]" class="form-control border border-primary">
                                                            <option value="">Select</option>
                                                            @foreach($products as $product)
                                                                <option value="{{$product->id}}">{{$product->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="text-primary field-label">Issue Product Number</label>
                                                        <input type="text" class="form-control border border-primary" name="issue_product_number[]" placeholder="Issue Product Number">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="text-primary field-label">Issue Date</label>
                                                        <input type="text" class="form-control border border-primary issue_date1" name="issue_date1[]" placeholder="Issue Date">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="text-primary field-label">Receive Date</label>
                                                        <input type="text" class="form-control border border-primary receive_date1" name="receive_date1[]" placeholder="Receive Date">
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label class="d-block invisible field-label">Buttons</label>
                                                        <div class="d-flex">
                                                            <button type="button" class="btn btn-success issue-add-row mr-1">+</button>
                                                            <button type="button" class="btn btn-danger issue-remove-row">-</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div id="receive-product-wrapper" class="border border-dark bg-light px-3 py-2 mb-3">
                                        @if($complain->receiveProducts->count() > 0)
                                            @foreach($complain->receiveProducts as $key => $row)
                                                <div class="row receive-product-row">
                                                    <input type="hidden" name="receive_row_id[]" value="{{$row->id}}">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            @if($key == 0)<label class="text-primary field-label">Receive Product</label>@endif
                                                            <select name="receive_product[]" class="form-control border border-primary">
                                                                <option value="">Select</option>
                                                                @foreach($products as $product)
                                                                    <option value="{{$product->id}}" @if($row->product_id == $product->id) selected @endif>{{$product->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            @if($key == 0)<label class="text-primary field-label">Receive Product Number</label>@endif
                                                            <input type="text" class="form-control border border-primary" name="receive_product_number[]" placeholder="Receive Product Number" value="{{$row->product_number}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            @if($key == 0)<label class="text-primary field-label">Receive Date</label>@endif
                                                            <input type="text" class="form-control border border-primary receive_date2" name="receive_date2[]" placeholder="Receive Date" value="{{ $row->receive_date ? \Carbon\Carbon::parse($row->receive_date)->format('d/m/Y') : '' }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            @if($key == 0)<label class="text-primary field-label">Issue Date</label>@endif
                                                            <input type="text" class="form-control border border-primary issue_date2" name="issue_date2[]" placeholder="Issue Date" value="{{ $row->issue_date ? \Carbon\Carbon::parse($row->issue_date)->format('d/m/Y') : '' }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <div class="form-group">
                                                            @if($key == 0)<label class="d-block invisible field-label">Buttons</label>@endif
                                                            <div class="d-flex">
                                                                <button type="button" class="btn btn-success receive-add-row mr-1">+</button>
                                                                <button type="button" class="btn btn-danger receive-remove-row">-</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="row receive-product-row">
                                                <input type="hidden" name="receive_row_id[]" value="">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="text-primary field-label">Receive Product</label>
                                                        <select name="receive_product[]" class="form-control border border-primary">
                                                            <option value="">Select</option>
                                                            @foreach($products as $product)
                                                                <option value="{{$product->id}}">{{$product->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="text-primary field-label">Receive Product Number</label>
                                                        <input type="text" class="form-control border border-primary" name="receive_product_number[]" placeholder="Receive Product Number">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="text-primary field-label">Receive Date</label>
                                                        <input type="text" class="form-control border border-primary receive_date2" name="receive_date2[]" placeholder="Receive Date">
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label class="text-primary field-label">Issue Date</label>
                                                        <input type="text" class="form-control border border-primary issue_date2" name="issue_date2[]" placeholder="Issue Date">
                                                    </div>
                                                </div>
                                                <div class="col-md-1">
                                                    <div class="form-group">
                                                        <label class="d-block invisible field-label">Buttons</label>
                                                        <div class="d-flex">
                                                            <button type="button" class="btn btn-success receive-add-row mr-1">+</button>
                                                            <button type="button" class="btn btn-danger receive-remove-row">-</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="message">Message</label>
                                            <textarea class="form-control" id="message" name="message" rows="4" cols="50" placeholder="Message">{{$complain->message}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="image">Image (allowed only JPG,JPEG &amp; PNG files)</label>
                                            <div class="input-group image_div">
                                                <div class="custom-file">             
                                                    <input type="file" class="custom-file-input" id="image" name="image[]" multiple="multiple">
                                                    <label class="custom-file-label" for="image">Choose file</label>
                                                </div>              
                                            </div>
                                            @php
                                                $complain_image = $complain->photos()->get();
                                            @endphp
                                            @if(($complain_image->count() > 0))
                                                @foreach($complain_image as $row)
                                                    <div class="image-box" id="img_{{$row->id}}">
                                                        <a href="{{asset('assets/'.$row->image)}}" data-toggle="lightbox" data-gallery="gallery1">
                                                            <img src="{{asset('assets/'.$row->image)}}" class="mr-2 mt-4 my-2" width="150px" />
                                                        </a>
                                                        <br>
                                                        <button type="button" class="delete-image" data-id="{{$row->id}}">
                                                            <i class="far fa-window-close"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="image">Video (allowed only MP4, AVI, MOV)</label>
                                            <div class="input-group video_div">
                                                <div class="custom-file">             
                                                    <input type="file" class="custom-file-input" id="video" name="video" accept="video/mp4,video/mpeg,video/avi">
                                                    <label class="custom-file-label" for="image">Choose video</label>
                                                </div>              
                                            </div>
                                            @if($complain->complain_video)
                                                <div class="mt-3">
                                                    <a href="{{asset('assets/'.$complain->complain_video)}}" target="_blank" class="btn btn-primary">
                                                        <i class="fas fa-video mr-2"></i>
                                                        View Uploaded Video
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="bill">Upload Bill (allowed only PDF)</label>
                                            <div class="input-group pdf_div">
                                                <div class="custom-file">             
                                                    <input type="file" class="custom-file-input" id="bill" name="bill">
                                                    <label class="custom-file-label" for="bill">Choose file</label>
                                                </div>              
                                            </div>
                                            @if($complain->bill)
                                                <div class="mt-3">
                                                    <a href="{{asset('assets/'.$complain->bill)}}" target="_blank" class="btn btn-primary">
                                                        <i class="fas fa-file-pdf mr-2"></i>
                                                        View Uploaded Bill
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <h5 class="btn btn-outline-primary">1st Followup</h5>
                                        <div class="form-group">
                                            <label for="followup_date_1">Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="text" id="followup_date_1" name="followup_date_1" class="form-control followup_date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" value="{{$complain->followup_date_1 ? Carbon\Carbon::parse($complain->followup_date_1)->format('d/m/Y') : ''}}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="followup_remarks_1">Remarks</label>
                                            <textarea class="form-control" id="followup_remarks_1" name="followup_remarks_1" rows="4" cols="50" placeholder="Followup Remarks">{{$complain->followup_remarks_1}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="btn btn-outline-primary">2nd Followup</h5>
                                        <div class="form-group">
                                            <label for="followup_date_2">Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="text" id="followup_date_2" name="followup_date_2" class="form-control followup_date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" value="{{$complain->followup_date_2 ? Carbon\Carbon::parse($complain->followup_date_2)->format('d/m/Y') : ''}}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="followup_remarks_2">Remarks</label>
                                            <textarea class="form-control" id="followup_remarks_2" name="followup_remarks_2" rows="4" cols="50" placeholder="Followup Remarks">{{$complain->followup_remarks_2}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="btn btn-outline-primary">3rd Followup</h5>
                                        <div class="form-group">
                                            <label for="followup_date_3">Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="text" id="followup_date_3" name="followup_date_3" class="form-control followup_date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" value="{{$complain->followup_date_3 ? Carbon\Carbon::parse($complain->followup_date_3)->format('d/m/Y') : ''}}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="followup_remarks_3">Remarks</label>
                                            <textarea class="form-control" id="followup_remarks_3" name="followup_remarks_3" rows="4" cols="50" placeholder="Followup Remarks">{{$complain->followup_remarks_3}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h5 class="btn btn-outline-primary">4th Followup</h5>
                                        <div class="form-group">
                                            <label for="followup_date_4">Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="text" id="followup_date_4" name="followup_date_4" class="form-control followup_date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" value="{{$complain->followup_date_4 ? Carbon\Carbon::parse($complain->followup_date_4)->format('d/m/Y') : ''}}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="followup_remarks_4">Remarks</label>
                                            <textarea class="form-control" id="followup_remarks_4" name="followup_remarks_4" rows="4" cols="50" placeholder="Followup Remarks">{{$complain->followup_remarks_4}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h5 class="btn btn-outline-primary">5th Followup</h5>
                                        <div class="form-group">
                                            <label for="followup_date_5">Date</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="text" id="followup_date_5" name="followup_date_5" class="form-control followup_date" data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy" value="{{$complain->followup_date_5 ? Carbon\Carbon::parse($complain->followup_date_5)->format('d/m/Y') : ''}}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="followup_remarks_5">Remarks</label>
                                            <textarea class="form-control" id="followup_remarks_5" name="followup_remarks_5" rows="4" cols="50" placeholder="Followup Remarks">{{$complain->followup_remarks_5}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary" id="btnsubmit" name="btnsubmit">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
<script>
    function initializeIssueDatepicker(element) {
        element.find('.issue_date1').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $(this).closest('.issue-product-row').find('.receive_date1').datepicker('setStartDate', minDate);
        });
        element.find('.receive_date1').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true
        }).on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            $(this).closest('.issue-product-row').find('.issue_date1').datepicker('setEndDate', maxDate);
        });
    }
    function initializeReceiveDatepicker(element) {
        element.find('.receive_date2').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $(this).closest('.receive-product-row').find('.issue_date2').datepicker('setStartDate', minDate);
        });
        element.find('.issue_date2').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true
        }).on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            $(this).closest('.receive-product-row').find('.receive_date2').datepicker('setEndDate', maxDate);
        });
    }
    $(function () {
        $('.followup_date').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' });
        initializeIssueDatepicker($('.issue-product-row'));
        initializeReceiveDatepicker($('.receive-product-row'));
        $(document).on('change', '#solution_status', function(){
            if($(this).val() == 1) {
                $(".complain_products").show();
            } else {
                if($("#record_exists").val() == 1) {
                    $(".complain_products").show();
                } else {
                    $(".complain_products").hide();
                }
            }
        });
        $(document).on('click', '.issue-add-row', function () {
            $('.issue_date1').datepicker('destroy');
            $('.receive_date1').datepicker('destroy');
            let clone = $('.issue-product-row:first').clone();
            clone.find('input').val('');
            clone.find('select').prop('selectedIndex', 0);
            clone.find('.field-label').remove();
            $('#issue-product-wrapper').append(clone);
            initializeIssueDatepicker($('.issue-product-row'));
        });
        $(document).on('click', '.issue-remove-row', function () {
            if ($('.issue-product-row').length > 1) {
                $(this).closest('.issue-product-row').remove();
            } else {
                alert('At least one row required.');
            }
        });
        $(document).on('click', '.receive-add-row', function () {
            $('.receive_date2').datepicker('destroy');
            $('.issue_date2').datepicker('destroy');
            let clone = $('.receive-product-row:first').clone();
            clone.find('input').val('');
            clone.find('select').prop('selectedIndex', 0);
            clone.find('.field-label').remove();
            $('#receive-product-wrapper').append(clone);
            initializeReceiveDatepicker($('.receive-product-row'));
        });
        $(document).on('click', '.receive-remove-row', function () {
            if ($('.receive-product-row').length > 1) {
                $(this).closest('.receive-product-row').remove();
            } else {
                alert('At least one row required.');
            }
        });
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox({
                alwaysShowClose: true
            });
        });
        $(document).on('click', '.delete-image', function () {
            let id = $(this).data('id');
            if(confirm('Are you sure you want to delete this image?')) {
                $.ajax({
                    url: "{{ route('services.complains.image.delete') }}",
                    type: "POST",
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function (response) {
                        $('#img_' + id).remove();
                    },
                    error: function () {
                        alert('Something went wrong.');
                    }
                });
            }
        });
        bsCustomFileInput.init();
        $('#edit-complain-form').validate({
            rules:{
                name:{
                    required: true
                },
                phone: {
                    required: true,
                    digits: true,
                    minlength: 10,
                    maxlength: 10
                },
                company_name:{
                    required: true
                },
                company_address:{
                    required: true
                },
                complain_issue:{
                    required: true
                },
                assign: {
                    required: true
                },
                solution_status:{
                    required: true
                },
                estimation_cost: {
                    digits: true
                },
                message: {
                    required: true
                },
                'image[]': {
                    extension: "png|jpg|jpeg",
                    maxsize: 5000000,
                },
                video: {
                    extension: "mp4|avi|mov",
                    maxsize: 5000000,
                },
                bill: {
                    extension: "pdf",
                    maxsize: 1000000,
                }
            },
            messages:{
                name:{
                    required: "Please enter name."
                },
                phone:{
                    required: "Plese enter mobile number.",
                },
                company_name:{
                    required: "Please enter company name."
                },
                company_address:{
                    required: "Please enter company address."
                },
                complain_issue:{
                    required: "Please select complain issue."
                },
                assign: {
                    required: "Please select assign."
                },
                solution_status:{
                    required: "Please select solution status."
                },
                message:{
                    required: "Plese enter message.",
                },
                'image[]': {
                    extension: "Please select valid image.",
                    maxsize: "File size must be less than 5MB."
                },
                video: {
                    extension: "Please select valid video.",
                    maxsize: "Video size must be less than 5MB."
                },
                bill: {
                    extension: "Please select valid file.",
                    maxsize: "PDF File size must be less than 1MB."
                }
            },
            errorPlacement: function(error, element) {
                if (element.attr("name") == "image[]" ) {
                    $(".image_div").after(error);
                } else if (element.attr("name") == "video") {
                    $(".video_div").after(error);
                } else if (element.attr("name") == "bill") {
                    $(".pdf_div").after(error);
                } else {
                    error.insertAfter(element);
                }
            }
        });
    });
</script>
@endsection