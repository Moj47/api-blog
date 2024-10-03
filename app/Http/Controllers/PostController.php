<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use Illuminate\Support\Str;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use League\CommonMark\Normalizer\SlugNormalizer;

class PostController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts=Post::all();
        return  PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate=Validator::make($request->all(),[
            'title'=>'required|string|max:25',
            'text'=>'required|string|max:1200',
            'image'=>'nullable|image',
            'category_id'=>'required|integer|exists:categories,id'
        ]);

        if($validate->fails())
            return $this->errorResponse(400,$validate->messages());

        $slug=Str::slug($request->title);

        if($count=Post::where('slug', 'LIKE', "%{$slug}%")->count())
        {
            $slug=$slug.$count+1;
        }
        $imageName=Carbon::now()->microsecond.'.'.$request->image->extension();
        $request->image->storeAs('img/posts',$imageName,'public');
            $post=Post::create([
                'title'=>$request->title,
                'text'=>$request->text,
                'slug'=>$slug,
                'image'=>$imageName,
                'category_id'=>$request->category_id,
            ]);
            return new PostResource($post);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
