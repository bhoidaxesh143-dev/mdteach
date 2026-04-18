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

    $path = $request->file('upload')->store('uploads', 'public');

    return response()->json([
        'url' => asset('storage/' . $path)
    ]);
}
}
