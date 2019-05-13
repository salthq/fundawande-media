@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-center">Add Resources</h2>
                    <div class="card-body">
                        <upload-resources>
                            {{ csrf_field() }}
                        </upload-resources>
                    </div>
                </div>
            </div>
        </div>

        <div class="row my-3">
            <div class="col text-center">
                <a href="/resources" class="primary-text ">Go back to resource list</a>
            </div>
        </div>

    </div>
@endsection
