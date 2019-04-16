@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h1 class="m-0">Resources</h1>
                        <a href="{{url('/resources/create')}}" class="btn btn-info text-white">Add resource</a>
                    </div>
                    <div class="card-body">
                        @if ($resources->count())
                            <table class="table">
                                <th>Title</th>
                                <th>Description</th>
                                <th>Resource Name</th>
                                <th>Size</th>
                                @foreach ($resources as $resource)
                                    <tr>
                                        <td>{{ $resource->title }}</td>
                                        <td>{{ $resource->description }}</td>
                                        <td>{{ $resource->filename }}</td>
                                        <td>{{ $resource->size }} Bytes</td>
                                    </tr>
                                @endforeach
                            </table>
                        @else
                            You have no resources yet!
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
