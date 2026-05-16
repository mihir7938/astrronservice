@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Edit User</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" action="{{route('admin.users.update.save')}}" class="form" id="edit-users-form" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{$user->id}}" />
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
                                <h3 class="card-title">Mobile Number : {{$user->phone}}</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Name*</label>
                                            <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{$user->name}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{$user->email}}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="type">User Type*</label>
                                            <select id="type" name="type" class="form-control">
                                                <option value="">Select</option>
                                                <option value="1" @if($user->role_id == '1') selected @endif>Admin</option>
                                                <option value="2" @if($user->role_id == '2') selected @endif>Service</option>
                                                <option value="3" @if($user->role_id == '3') selected @endif>Customer</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        @if($user->isUser() || $user->isService())
                                            <div class="form-group">
                                                <label for="active">Active</label>
                                                <div class="group">
                                                    <input type="radio" id="yes" name="active" value="1" @if($user->status == 1) checked @endif>
                                                    <label for="yes">Yes</label>
                                                    <span class="mx-2"></span>
                                                    <input type="radio" id="no" name="active" value="0" @if($user->status == 0) checked @endif>
                                                    <label for="no">No</label>
                                                </div>
                                            </div>
                                        @endif
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
        $('#edit-users-form').validate({
            rules:{
                name:{
                    required: true
                },
                email: {
                    alphanumeric: true
                },
                type:{
                    required: true
                }
            },
            messages:{
                name:{
                    required: "Please enter name."
                },
                email:{
                    email: "Please provide a valid email."
                },
                type:{
                    required: "Plese select type.",
                }
            },
            errorPlacement: function(error, element) {
                if (element.hasClass('select2-hidden-accessible')) {
                    error.insertAfter(element.next('.select2-container'));
                } else {
                    error.insertAfter(element);
                }
            }
        });
    });
</script>
@endsection