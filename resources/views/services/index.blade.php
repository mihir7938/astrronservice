@extends('layouts.app')
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <div class="d-flex justify-content-between">
                        <h1 class="m-0">Home</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                @php
                    $bgClasses = [
                        'bg-primary',
                        'bg-danger',
                        'bg-secondary',
                        'bg-success',
                        'bg-warning',
                        'bg-info',
                        'bg-dark',
                        'bg-primary',
                        'bg-success',
                    ];
                @endphp
                @foreach($solutions as $key => $solution)
                    <div class="col-lg-3 col-6">
                        <div class="small-box {{ $bgClasses[$key % count($bgClasses)] }}">
                            <div class="inner">
                                <h3>{{ $solution->complains_count }}</h3>
                                <p>{{ $solution->name }}</p>
                            </div>
                            <a href="{{route('services.complains')}}?status={{$solution->id}}" class="small-box-footer py-3">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
@section('footer')
@endsection