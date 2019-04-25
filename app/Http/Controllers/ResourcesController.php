<?php

namespace App\Http\Controllers;

use App\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ResourcesController extends Controller
{
    /**
     * Display a listing of the resources.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resources = Resource::all();
        return view('resources.index', compact('resources'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('resources.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $resource = new Resource;
        $file = $request->file('file');


        $resource->title = $request->title;
        $resource->mime = $file->getMimeType();
        $resource->filename = $file->getClientOriginalName();
        //TODO: Save the size in a human-readable format
        $resource->size = $file->getClientSize();

        $resource->save();

        $file->storeAs('resources', $resource->filename, 'public');


        return redirect('/resources');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function show(Resource $resource)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function edit(Resource $resource)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Resource $resource)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resource $resource)
    {
        $resource->delete();
        Storage::disk('public')->delete('resources/' .$resource->filename);

        return redirect('/resources');
    }

     /**
     * Download the specified resource
     *
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function download(Resource $resource)
    {
        return Storage::disk('public')->download('resources/' .$resource->filename);
    }
}
