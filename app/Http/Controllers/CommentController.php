<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Resources\CommentResource;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    use ApiResponser;
   public function store(Request $request)
   {
        $validate=Validator::make($request->all(),[
            'title'=>'string|max:50|required',
            'text'=>'required|max:250|string',
            'user_id'=>'required|integer|exists:users,id',
            'post_id'=>'required|integer|exists:posts,id',
            'category_id'=>'required|integer|exists:categories,id',
        ]);
        if($validate->fails())
        return $this->errorResponse(400,$validate->messages());

        $comment=Comment::create([
            'title'=>$request->title,
            'text'=>$request->text,
            'user_id'=>$request->user_id,
            'post_id'=>$request->post_id,
            'category_id'=>$request->category_id,
        ]);
        return new CommentResource($comment);
   }
   public function delete(Comment $comment)
   {
    $comment->delete();
    return $this->successResponse(new CommentResource($comment),200,'Deleted SuccesFully');
   }
}
