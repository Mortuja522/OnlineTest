<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Blog;
use Symfony\Contracts\Service\Attribute\Required;



class BlogController extends Controller
{
    //
    public function create(Request $request){

        $validator = Validator::make($request->all(), [
            'title'=>'required|max:250',
            'slug'=>'required',
            'description'=>'required',
            'image'=>'Required|image|mimes:jpg,bmp,png'
            
            
        ]);

        if ($validator->fails()) {
            return response()->
            json([
                'message'=>'validation fails',
                'errors'=>$validator->errors()
            ],422);
        }  

        $image_name=time().'.'.$request->image->extension();
        $request->image->move(public_path('/uploads/blog_images'),$image_name);

        $blog=Blog::create([

            'title'=>$request->title,
            'slug'=>$request->slug,
            'description'=>$request->description,
            'image'=> $image_name,
            'user_id'=>$request->user()->id

        ]);
        $blog->load('user:id,email');
        return response()->
            json([
                'message'=>'Blog Successfully Created',
                'data'=>$blog
                
            ],200);

    }

    public function update($id,Request $request){

        $blog=Blog::with(['user'])->where('id',$id)->first();

        if($blog){
            if($blog->user_id==$request->user()->id){

                $validator = Validator::make($request->all(), [
                    'title'=>'required',
                    'slug'=>'required',
                    'description'=>'required'
                    
                    
                ]);
        
                if ($validator->fails()) {
                    return response()->
                    json([
                        'message'=>'validation fails',
                        'errors'=>$validator->errors()
                    ],422);
                } 

                $blog->update([

                    'title'=>$request->title,
                    'slug'=>$request->slug,
                    'description'=>$request->description        
                ]);

                return response()->
                    json([
                        'message'=>'Blog Successfully updated',
                        'data'=>$blog
                        
                    ],200);


            }
            else{
                return response()->
                json([
                    'message'=>'Access denied',
                    
                ],403);
            }

        } 
        else
        {

            return response()->
            json([
                'message'=>'No Blog Found',
                
            ],400);

        }



    }

    public function delete($id,Request $request){

        $blog=Blog::where('id',$id)->first();
        if($blog){
            if($blog->user_id==$request->user()->id){

                $blog->delete();

                return response()->
                    json([
                        'message'=>'Blog Successfully deleted'         
                    ],200);

            }else{
                return response()->
                json([
                    'message'=>'Access denied',
                    
                ],403);

            }

        }else{

            return response()->
            json([
                'message'=>'No Blog Found',
                
            ],400);

        }



    }

    public function list(Request $request){

        $blog_query=Blog::with('user');
        $blogs=$blog_query->get();

        return response()->
        json([
            'message'=>'Blog & Auth user Successfully fetched',
            'data'=>$blogs
            
        ],200);


    }

    public function imageshow($id)
    {

            $blog_images=Blog::select('image','user_id')->where('id',$id)->get();

            return response()->
            json([
                'message'=>'Image Successfully fetched',
                'data'=>$blog_images
                ],200);


    }




}
