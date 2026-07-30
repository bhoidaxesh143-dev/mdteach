<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|max:2048'
        ]);

        $file = $request->file('upload');
        $filename = time().'_'.$file->getClientOriginalName();

        // Save to Laravel's default public/uploads folder
        $destination = public_path('uploads');

        if (!file_exists($destination)) {
            mkdir($destination, 0755, true);
        }

        $file->move($destination, $filename);

        return response()->json([
            'url' => asset('uploads/' . $filename)
        ]);
    }
}
