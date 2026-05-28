@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <div class="d-flex justify-content-between">
                        <h1 class="m-0">Complain Form</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" action="{{route('complain.save')}}" class="form" id="add-complains-form" enctype="multipart/form-data">
                        @csrf
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
                                <h3 class="card-title">Add Complain</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Contact Name*</label>
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Contact Name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone">Contact Number*</label>
                                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Contact Number">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company_name">Company Name*</label>
                                            <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company Name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="company_address">Company Address*</label>
                                            <input type="text" class="form-control" id="company_address" name="company_address" placeholder="Company Address">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="complain_issue">Complain Details*</label>
                                            <select id="complain_issue" name="complain_issue" class="form-control">
                                                <option value="">Select</option>
                                                @foreach($issues as $issue)
                                                    <option value="{{$issue->id}}">{{$issue->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="message">Message* (Complain History)</label>
                                            <textarea class="form-control" id="message" name="message" rows="4" cols="50" placeholder="Message"></textarea>
                                        </div>
                                    </div>
                                    @if(Auth::check() && Auth::user()->isAdmin())
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="assign">Assign</label>
                                                <select id="assign" name="assign" class="form-control select2">
                                                    <option value="">Select Assign</option>
                                                    @foreach($users as $user)
                                                        @if($user->isService() || $user->isAdmin())
                                                           <option value="{{$user->id}}">{{$user->name}} - {{$user->role->name}}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="upload_image">Upload Image (allowed only JPG,JPEG &amp; PNG files)</label>
                                            <div class="input-group image_div">
                                                <div class="custom-file">             
                                                    <input type="file" class="custom-file-input" id="upload_image" name="upload_image[]" multiple="multiple">
                                                    <label class="custom-file-label" for="upload_image">Choose file</label>
                                                </div>              
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="upload_video">Upload Video (allowed only MP4, AVI, MOV)</label>
                                            <div class="input-group video_div">
                                                <div class="custom-file">             
                                                    <input type="file" class="custom-file-input" id="upload_video" name="upload_video" accept="video/mp4,video/avi,video/mov">
                                                    <label class="custom-file-label" for="upload_video">Choose video</label>
                                                </div>              
                                            </div>
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
    $(function () {
        bsCustomFileInput.init();
        $('#add-complains-form').validate({
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
                message: {
                    required: true
                },
                'upload_image[]': {
                    extension: "png|jpg|jpeg",
                    maxsize: 5000000,
                },
                upload_video: {
                    extension: "mp4|avi|mov",
                    maxsize: 5000000,
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
                message:{
                    required: "Plese enter message.",
                },
                'upload_image[]': {
                    extension: "Please select valid image.",
                    maxsize: "File size must be less than 5MB."
                },
                upload_video: {
                    extension: "Please select valid video.",
                    maxsize: "Video size must be less than 5MB."
                }
            },
            errorPlacement: function(error, element) {
                if (element.attr("name") == "upload_image[]" ) {
                    $(".image_div").after(error);
                } else if (element.attr("name") == "upload_video") {
                    $(".video_div").after(error);
                } else if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2-container'));
                } else {
                    error.insertAfter(element);
                }
            }
        });
    });
</script>
@endsection