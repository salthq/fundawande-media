@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="m-0">Resources</h3>
                        <a href="{{url('/resources/create')}}" class="btn primary-btn-fill">Add resource</a>
                    </div>
                    <div class="card-body">
                        @if ($resources->count())

                            {{-- When I have some time I need to find a better way to filter the resource data before sending to the Vue component --}}
                            @php
                                $resource_array = [];

                                foreach ($resources as $key => $resource) {
                                    $filtered_resource = new stdClass();
                                    $filtered_resource->Title = $resource->title;
                                    $filtered_resource->Filename = $resource->filename;
                                    $filtered_resource->Mime = $resource->mime;
                                    $filtered_resource->Size = $resource->size;
                                    $filtered_resource->ID = $resource->id;
                                    $filtered_resource->Created = $resource->created_at->format('Y-m-d');


                                    array_push($resource_array,$filtered_resource);
                                }
                            @endphp

                            <resource-table :resource_array="{{ json_encode($resource_array) }} ">
                                    {{method_field('DELETE')}}
                                    @csrf
                            </resource-table>
                        @else
                            You have no resources yet!
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
