<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileEntriesController extends Controller
{
    public function uploadFile(Request $request) {
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();

        $path = hash( 'sha256', time());

        if(\Storage::disk('uploads')->put($path.'/'.$filename,  \File::get($file))) {
            $input['filename'] = $filename;
            $input['mime'] = $file->getClientMimeType();
            $input['path'] = $path;
            $input['size'] = $file->getClientSize();
            $file = \App\FileEntry::create($input);

            return response()->json([
                'success' => true,
                'id' => $file->id
            ], 200);
        }
        return response()->json([
            'success' => false
        ], 500);
    }

    public function index() {
        $files = \App\FileEntry::all();
        return view('files.index', ['files' => $files]);
    }

    public function create() {
        return view('files.create');
    }
}
