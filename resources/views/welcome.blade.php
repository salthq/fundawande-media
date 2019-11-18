@extends('layouts.app')


@section('content')
<div class="container">

    <div class="row justify-content-center align-items-center">
        <div class="col-md-6 mx-auto text-center">
            <h1 class=" py-3">Funda Wande Media</h1>
            <p>This app is used to store and manage the media resources which are used on the Funda Wande learning app.
            </p>
            <p class="pb-3">For more info about managing media, please view <a target="_blank" href="">this help
                    article. </a></p>
            <a class="btn btn-lg primary-btn-fill" href="/resources">View Resources</a>
            <a target="_blank" class="btn btn-lg btn-secondary" href="https://funda.fundawande.org">View Funda Wande</a>
        </div>
    </div>

    <div class="row mt-3 mt-md-5">
        <div class="col-md-6 mx-auto text-center">
            <h4 class="text-left">Total storage used</h4>
            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow="{{$storage_percentage}}" aria-valuemin="0"
                    aria-valuemax="100" style="width: {{$storage_percentage}}%">{{$storage_percentage}}%</div>
            </div>
        </div>
    </div>
</div>
@endsection