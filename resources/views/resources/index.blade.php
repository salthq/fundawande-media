@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="m-0">Resources</h3>
                        <a href="{{url('/resources/create')}}" class="btn primary-btn-fill">Add resource</a>
                    </div>
                    <div class="card-body p-0">
                        @if ($resources->count())
                            <resource-list :full_resources="{{ $resources }} ">
                                    {{method_field('DELETE')}}
                                    @csrf
                            </resource-list>
                        @else
                            You have no resources yet!
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
