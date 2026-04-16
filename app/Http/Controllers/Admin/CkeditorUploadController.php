<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CkeditorUploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|max:2048'
        ]);

        $path = $request->file('upload')
            ->store('ckeditor', 'public');

        return response()->json([
            'url' => asset('storage/' . $path)
        ]);
    }
}
