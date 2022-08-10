<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use Illuminate\Support\Facades\Validator;
use App\Models\Comment;

class CommentController extends Controller
{
    //
    public function comment($blog_id,Request $request){
        $blog=Blog::where('id',$blog_id)->first();

        if($blog){

            $validator = Validator::make($request->all(), [
                'massage'=>'required',
                
                
            ]);
    
            if ($validator->fails()) {
                return response()->
                json([
                    'message'=>'validation fails',
                    'errors'=>$validator->errors()
                ],422);
            }  



            $comment=Comment::create([

                'message'=>$request->message,
                'blog_id'=>$blog->id,
                'user_id'=>$request->user()->id

            ]);
            $comment->load('user');

            return response()->
                    json([
                        'message'=>'Comment Successfully Created',
                        'data'=>$comment
                        
                    ],200);



        }else{
            return response()->json([
                'message'=>'No Blog Found', 
            ],400);
            


        }


        
    }
}
