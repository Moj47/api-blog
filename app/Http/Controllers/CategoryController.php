<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories=Category::all();
        return CategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate=Validator::make($request->all(),[
            'title'=>'required|max:55'
        ]);
        if($validate->fails())
        return $this->errorResponse(400,$validate->messages());

        $category=Category::create([
            'title'=>$request->title
        ]);
        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validate=Validator::make($request->all(),[
            'title'=>'required|max:55'
        ]);
        if($validate->fails())
        return $this->errorResponse(400,$validate->messages());

        $category->update([
            'title'=>$request->title
        ]);
        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if($category->posts()->count())
        {
            return $this->errorResponse(403,'This category has some posts');
        }
        $category->delete();
        return $this->successResponse($category,200,'Category Deleted Succesfully');
    }
}
