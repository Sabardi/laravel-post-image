<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//return type View
use Illuminate\View\View;

//return type redirectResponse
use Illuminate\Http\RedirectResponse;
//import Facade "Storage"
use Illuminate\Support\Facades\Storage;

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
        // $image = $request->file('image');
        // $image->storeAs('public/asset/img', $image->hashName());

        //upload image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = $image->hashName();
            $destination = 'gambar';
            $image = $image->move($destination, $imageName);

        }
        //create post
        Post::create([
            'image'     => $imageName,
            'title'     => $request->title,
            'content'   => $request->content
        ]);

        //redirect to index
        return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

//     /**
//      * Display the specified resource.
//      */
public function show(string $id): View
    {
        //get post by ID
        $post = Post::findOrFail($id);

        //render view with post
        return view('posts.show', compact('post'));
    }

//     /**
//      * Show the form for editing the specified resource.
//      */

        public function edit(string $id): View
        {
            //get post by ID
            $post = Post::findOrFail($id);

            //render view with post
            return view('posts.edit', compact('post'));
        }

//     /**
//      * Update the specified resource in storage.
//      */
        public function update(Request $request, $id): RedirectResponse
        {
            //validate form
            $this->validate($request, [
                'image'     => 'image|mimes:jpeg,jpg,png|max:2048',
                'title'     => 'required|min:5',
                'content'   => 'required|min:10'
            ]);


            //get post by ID
            $post = Post::findOrFail($id);


            //check if image is uploaded
            if ($request->hasFile('image')) {

                //upload new image
                $image = $request->file('image');
                $imageName = $image->hashName();
                $destination = 'gambar';

                //delete old image
                $image = $image->move($destination, $imageName);

                //update post with new image
                $post->update([
                    'image'     => $imageName,
                    'title'     => $request->title,
                    'content'   => $request->content
                ]);

            } else {

                //update post without image
                $post->update([
                    'title'     => $request->title,
                    'content'   => $request->content
                ]);
            }

            //redirect to index
            return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Diubah!']);
        }

//     /**
//      * Remove the specified resource from storage.
//      */


        public function destroy($id): RedirectResponse{
            //get post by ID
            $post = Post::findOrFail($id);

            //delete image
            Storage::delete('public/posts/'. $post->image);

            //delete post
            $post->delete();

            //redirect to index
            return redirect()->route('posts.index')->with(['success' => 'Data Berhasil Dihapus!']);
        }
}
