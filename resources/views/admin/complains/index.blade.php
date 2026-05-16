@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <div class="d-flex justify-content-between">
                        <h1 class="m-0">Complains</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" action="{{route('admin.complains.fetch')}}" class="form" id="fetch-complain" enctype="multipart/form-data">
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
                                <h3 class="card-title">Select Filter</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="issue">Complain Issue</label>
                                            <select id="issue" name="issue" class="form-control">
                                                <option value="">Select Complain Issue</option>
                                                @foreach($issues as $issue)
                                                    <option value="{{$issue->id}}">{{$issue->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="solution_stage">Solution Stage</label>
                                            <select id="solution_stage" name="solution_stage" class="form-control">
                                                <option value="">Select Solution Stage</option>
                                                @foreach($solutions as $solution)
                                                    <option value="{{$solution->id}}" @if($status_id == $solution->id) selected @endif>{{$solution->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="start_date" name="start_date" placeholder="Start Date">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="end_date" name="end_date" placeholder="End Date">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary" id="btnsubmit" name="btnsubmit">Search</button>
                            </div>
                        </div>
                    </form>
                    <div id="complain_result">
                        @include('admin.complains.list', ['complains' => $complains])
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer')
<script>
    $(document).ready(function() {
        $("#start_date").datepicker({
            'format': 'dd/mm/yyyy',
            'autoclose': true
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('#end_date').datepicker('setStartDate', minDate);
            $(this).valid();
        });
        $("#end_date").datepicker({
            'format': 'dd/mm/yyyy',
            'autoclose': true
        }).on('changeDate', function (selected) {
            var maxDate = new Date(selected.date.valueOf());
            $('#start_date').datepicker('setEndDate', maxDate);
            $(this).valid();
        });
        $('#dataTableSupport').DataTable({
            "buttons": ["csv", "excel"],
            "destroy": true, 
            "paging": true,
            "lengthChange": false,
            "ordering": true,
            "info": true,
            "responsive": true,
        }).buttons().container().appendTo('#dataTableSupport_wrapper .col-md-6:eq(0)');
        $('#fetch-complain').validate({
            rules:{
                start_date:{
                    required:function(){
                        if($('#end_date').val()!='') {
                            return true;
                        }
                        return false;
                    },
                },
                end_date:{
                    required:function(){
                        if($('#start_date').val()!='') {
                            return true;
                        }
                        return false;
                    },
                }
            },
            messages:{
                start_date:{
                    required: "Please select start date."
                },
                end_date:{
                    required: "Please select end date."
                }
            },
            submitHandler: function (form) {
                $('.loader').show();
                $.ajax({
                    url: "{{ route('admin.complains.fetch') }}",
                    method: "POST",
                    headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                      'issue_id' : $("#issue").val(),
                      'solution_id' : $("#solution_stage").val(),
                      'start_date' : $("#start_date").val(),
                      'end_date' : $("#end_date").val(),
                    },
                    success: function (data) {
                        $('.loader').hide();
                        $("#complain_result").html('');
                        $('#complain_result').append(data);
                        $('#dataTableSupport').DataTable({
                            "buttons": ["csv", "excel"],
                            "destroy": true, 
                            "paging": true,
                            "lengthChange": false,
                            "ordering": true,
                            "info": true,
                            "responsive": true,
                        }).buttons().container().appendTo('#dataTableSupport_wrapper .col-md-6:eq(0)');
                    },
                });
            }
        });
    });
</script>
@endsection