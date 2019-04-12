<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FileEntriesController extends Controller
{
    public function uploadFile(Request $request)
    {
        $file = Input::file('file');
        $filename = $file->getClientOriginalName();

        if (Storage::disk('uploads')->put($filename, File::get($file))) {
            $input['filename'] = $filename;
            $input['mime'] = $file->getClientMimieType();
            $input['size'] = $file->getClientSize();
            $file = FileEntry::create($input);

            return response()->json([
                'success' => true,
                'id' => $file->id
            ], 200);
        }

        return response()->json([
            'success' => false
        ], 500);
    }
}
