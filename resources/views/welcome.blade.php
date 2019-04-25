@extends('layouts.app')


@section('content')
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                    @endauth
                </div>
            @endif

            <div class="content">
                <div class="title m-b-md">
                    Funda Wande Media
                </div>

                <div class="links">
                    <a class="btn btn-info text-white" href="/resources">View Resources</a>
                </div>
            </div>
        </div>
    </body>
@endsection



