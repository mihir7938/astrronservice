@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <div class="d-flex justify-content-between">
                        <h1 class="m-0">My Complains</h1>
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
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTableSupport" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>Complain No</th>
                                            <th>Solution Status</th>
                                            <th>Issue</th>
                                            <th>Contact Name</th>
                                            <th>Mobile Number</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Action</th>
                                            <th>Complain No</th>
                                            <th>Solution Status</th>
                                            <th>Issue</th>
                                            <th>Contact Name</th>
                                            <th>Mobile Number</th>
                                            <th>Date</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach($complains as $complain)
                                            <tr>
                                                <td style="width: 50px;text-align: center;">
                                                    <a href="{{route('users.complains.view', ['id' => $complain->id])}}" class="btn btn-outline-primary btn-circle">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                                <td>{{$complain->complain_number}}</td>
                                                <td style="width: 170px;text-align: center;">
                                                    @if($complain->solution)
                                                        <div class="bg-success d-inline-flex px-2 py-1">{{$complain->solution->name}}</div>
                                                    @else
                                                        <div class="bg-danger d-inline-flex px-2 py-1">Pending</div>
                                                    @endif
                                                </td>
                                                <td>{{$complain->issue->name}}</td>
                                                <td>{{$complain->contact_name}}</td>
                                                <td>{{$complain->contact_number}}</td>
                                                <td>{{Carbon\Carbon::parse($complain->complain_date)->format('d-m-Y')}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
    $(document).ready(function() {
        $('#dataTableSupport').DataTable({
            "destroy": true, 
            "paging": true,
            "lengthChange": false,
            "ordering": true,
            "info": true,
            "responsive": true,
        });
    });
</script>
@endsection