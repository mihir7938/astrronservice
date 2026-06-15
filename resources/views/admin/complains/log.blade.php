@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <div class="d-flex justify-content-between">
                        <h1 class="m-0">Assign Logs</h1>
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
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Assign By</th>
                                            <th>Assign From</th>
                                            <th>Assign To</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Assign By</th>
                                            <th>Assign From</th>
                                            <th>Assign To</th>
                                            <th>Date</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach($logs as $log)
                                            <tr>
                                                <td>{{$log->users->name}}</td>
                                                <td>{{$log->assignFrom->name}}</td>
                                                <td>{{$log->assignTo->name}}</td>
                                                <td>{{Carbon\Carbon::parse($log->date)->format('d-m-Y H:i:s')}}</td>
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