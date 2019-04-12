@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-header">
                    <h2>Add Files</h2>
                    <div class="card-body">
                        <upload-files post_url="files/upload-file"></upload-files>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
