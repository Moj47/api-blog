<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use Illuminate\Support\Str;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        return  PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Gate::allows('isadmin')) {
            $validate = Validator::make($request->all(), [
                'title' => 'required|string|max:55',
                'text' => 'required|string|max:1200',
                'image' => 'nullable|image',
                'category_id' => 'required|integer|exists:categories,id'
            ]);

            if ($validate->fails())
                return $this->errorResponse(400, $validate->messages());

            $slug = Str::slug($request->title);

            if ($count = Post::where('slug', 'LIKE', "%{$slug}%")->count()) {
                $slug = $slug . $count + 1;
            }
            $imageName = ' ';
            if ($request->image != null) {
                $imageName = Carbon::now()->microsecond . '.' . $request->image->extension();
                $request->image->storeAs('img/posts', $imageName, 'public');
            }
            $post = Post::create([
                'title' => $request->title,
                'text' => $request->text,
                'slug' => $slug,
                'image' => $imageName,
                'category_id' => $request->category_id,
            ]);
            return new PostResource($post);
        } else {
            return $this->errorResponse(403, 'Your not Admin!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        if (Gate::allows('isadmin')) {
            $validate = Validator::make($request->all(), [
                'title' => 'required|string|max:55',
                'text' => 'required|string|max:1200',
                'image' => 'nullable|image',
                'category_id' => 'required|integer|exists:categories,id'
            ]);

            if ($validate->fails())
                return $this->errorResponse(400, $validate->messages());

            $slug = $post->slug;
            if ($post->slug != $request->slug) {
                $slug = Str::slug($request->title);

                if ($count = Post::where('slug', 'LIKE', "%{$slug}%")->count()) {
                    $slug = $slug . $count + 1;
                }
            }
            $imageName = $post->image;
            if ($request->image != null) {
                $file = 'storage/' . 'img/' . 'posts/' . $post->image;
                if (Storage::exists($file)) {
                    Storage::delete($file);
                }
                $imageName = Carbon::now()->microsecond . '.' . $request->image->extension();
                $request->image->storeAs('img/posts', $imageName, 'public');
            }


            $post->update([
                'title' => $request->title,
                'text' => $request->text,
                'slug' => $slug,
                'image' => $imageName,
                'category_id' => $request->category_id,
            ]);
            return new PostResource($post);
        } else {
            return $this->errorResponse(403, 'Your not Admin!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if (Gate::allows('isadmin')) {
            $file = 'storage/' . 'img/' . 'posts/' . $post->image;
            if ($post->comments == null) {
                $post->delete();
                if (Storage::exists($file)) {
                    Storage::delete($file);
                }
                return new PostResource($post);
            } else {
                $post->comments()->delete();
                $post->delete();
                if (Storage::exists($file)) {
                    Storage::delete($file);
                }
                return $this->successResponse(new PostResource($post), 200, 'Post Deleted With comments');
            }
        } else {
            return $this->errorResponse(403, 'Your not Admin!');
        }
    }

    public function comments(Post $post)
    {
        return new PostResource($post->load('comments'));
    }
}
