@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <div class="d-flex justify-content-between">
                        <h1 class="m-0">View Complain</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div><label>Complain No :</label> {{$complain->complain_number}}</div>
                                    <div><label>Contact Name :</label> {{$complain->contact_name}}</div>
                                    <div><label>Contact Number :</label> {{$complain->contact_number}}</div>
                                    @if($complain->company)
                                        <div><label>Company Name :</label> {{$complain->company}}</div>
                                    @endif
                                    <div><label>Message :</label> {{$complain->message}}</div>
                                </div>
                                <div class="col-md-6">
                                    <div><label>Complain Date :</label> {{Carbon\Carbon::parse($complain->complain_date)->format('d-m-Y')}}</div>
                                    <div><label>Complain Issue :</label> {{$complain->issue->name}}</div>
                                    <div><label>Solution Status :</label> 
                                        @if($complain->solution)
                                            <div class="bg-success d-inline-flex px-3 py-1">{{$complain->solution->name}}</div>
                                        @else
                                            <div class="bg-danger d-inline-flex px-3 py-1">Pending</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    @php
                                        $complain_image = $complain->photos()->get();
                                    @endphp
                                    @if(($complain_image->count() > 0))
                                        @foreach($complain_image as $row)
                                            <a href="{{asset('assets/'.$row->image)}}" data-toggle="lightbox" data-gallery="gallery1">
                                                <img src="{{asset('assets/'.$row->image)}}" class="mr-2 mt-3 my-2" width="150px" />
                                            </a>
                                        @endforeach
                                    @endif
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
<script>
    $(function () {
        $(document).on('click', '[data-toggle="lightbox"]', function(event) {
            event.preventDefault();
            $(this).ekkoLightbox({
                alwaysShowClose: true
            });
        });
    });
</script>
@endsection