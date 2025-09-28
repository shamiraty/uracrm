<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    //
    public function create()
{
    return view('posts.create');
}

// public function store(Request $request)
// {
//     // Dump all request data and stop execution to inspect the incoming request
//     dd($request->all());
//     $request->validate([
//         'image_path' => 'required|string',
//         'caption' => 'required|string',
//         'description' => 'required|string'
//     ]);

//     $post = new Post([
//         'image_path' => $request->image_path,
//         'caption' => $request->caption,
//         'description' => $request->description
//     ]);
//     $post->save();

//     return redirect('/carousel')->with('success', 'Post has been added');
// }
public function store(Request $request)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  // Validate the image
        'caption' => 'required|string',
        'description' => 'required|string'
    ]);

    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = time() . '-' . $file->getClientOriginalName(); // Create a unique filename

        // Move the file to the specified directory
        $file->move(public_path('slides'), $filename);

        // Create a new post instance and save it to the database
        $post = new Post([
            'image_path' => 'slides/' . $filename,  // Save the relative path to the image
            'caption' => $request->caption,
            'description' => $request->description
        ]);
        $post->save();

        // Redirect after successful creation
        return redirect('/carousel')->with('success', 'Post has been added');
    } else {
        // Redirect or handle the case where no image is uploaded
        return back()->withErrors(['image' => 'An image must be uploaded.']);
    }
}


public function edit($id)
{
    $post = Post::find($id);
    return view('posts.edit', compact('post'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'image_path' => 'required|string',
        'caption' => 'required|string',
        'description' => 'required|string'
    ]);

    $post = Post::find($id);
    $post->image_path = $request->get('image_path');
    $post->caption = $request->get('caption');
    $post->description = $request->get('description');
    $post->save();

    return redirect('/carousel')->with('success', 'Post has been updated');
}

}
