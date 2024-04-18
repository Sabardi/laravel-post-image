<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//return type View
use Illuminate\View\View;

//return type redirectResponse
use Illuminate\Http\RedirectResponse;

use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(){
         //get posts
         $posts = Post::latest()->paginate(5);

         //render view with posts
         return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(){
        //
        return view('posts.create');
    }

//     /**
//      * Store a newly created resource in storage.
//      */
    public function store(Request $request) : RedirectResponse
    {
        //validate form
        $this->validate($request, [
            'image'     => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'title'     => 'required|min:5',
            'content'   => 'required|min:10'
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/asset/img', $image->hashName());

        //create post
        Post::create([
            'image'     => $image->hashName(),
            'title'     => $request->title,
            'content'   => $request->content
        ]);

        //redirect to index
        return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

//     /**
//      * Display the specified resource.
//      */
//     public function show(string $id)
//     {
//         //
//     }

//     /**
//      * Show the form for editing the specified resource.
//      */
//     public function edit(string $id)
//     {
//         //
//     }

//     /**
//      * Update the specified resource in storage.
//      */
//     public function update(Request $request, string $id)
//     {
//         //
//     }

//     /**
//      * Remove the specified resource from storage.
//      */
//     public function destroy(string $id)
//     {
//         //
//     }
}
