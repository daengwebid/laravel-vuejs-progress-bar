<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function uploadFile(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|mimes:jpg,png,jpeg'
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs(
                'public', $filename
            );
            return response()->json(['Upload Success']);
        }
    }

    public function readFile()
    {
        $files = scandir(storage_path('app/public'));
        $allFile = array_diff($files, ['.', '..', '.gitignore']);
        return response()->json($allFile, 200);
    }
}
