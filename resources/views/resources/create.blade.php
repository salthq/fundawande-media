@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header">
                    <h2>Add Resources</h2>
                    <div class="card-body">
                        <upload-resources post_url="resources/"></upload-resources>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
