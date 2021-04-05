<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Article, Category, User};
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\{ArticleResource, ArticleCollection};

class ArticleController extends Controller
{
    public function index()
    {
        return new ArticleCollection(Article::all());
    }

    public function create(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'desc' => 'required',
            'category_id' => 'required',
            'thumbnail' => ['required','image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        if ($validate->fails()){
            $response = [
                'status' => 'error',
                'msg' => 'Validator error',
                'errors' => $validate->errors(),
                'content' => null
            ];
            return response()->json($response, 400);
        }

        $extension = $request->file('thumbnail')->extension();
        $thumbnailName = date('dmyHis').'.'.$extension;
        Storage::putFileAs('articles', $request->file('thumbnail'), $thumbnailName);

        $article = auth()->user()->articles()->create([
            'title' => $request->title,
            'desc' => $request->desc,
            'category_id' => $request->category_id,
            'thumbnail' => $thumbnailName,
        ]);

        $respon = [
            'status' => 'success',
            'msg' => 'Success create new category',
            'errors' => null,
            'content' => [
                'status_code' => 200,
                'data' => $article
            ]
        ];
        return response()->json($respon, 200);
    }

    public function update(Request $request, Article $article)
    {
        if(strtoupper(auth()->user()->role->name) !== "ADMIN"){
            if($article->user->id !== auth()->user()->id){
                $respon = [
                    'status' => 'error',
                    'msg' => 'You are not the author of this post',
                    'errors' => "Unauthorized action.",
                    'content' => [
                        'status_code' => 403
                    ]
                ];
                return response()->json($respon, 403);
            }
        }

        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'desc' => 'required',
            'category_id' => 'required',
        ]);

        if ($validate->fails()){
            $response = [
                'status' => 'error',
                'msg' => 'Validator error',
                'errors' => $validate->errors(),
                'content' => null
            ];
            return response()->json($response, 400);
        }

        if($request->file('thumbnail')){
            \Storage::delete($article->thumbnail);
            $thumbnail = $request->file('thumbnail')->store("images/articles");
        } else{
            $thumbnail = $article->thumbnail;
        }

        $article->update([
            'title' => $request->title,
            'desc' => $request->desc,
            'category_id' => $request->category_id,
            'thumbnail' => $thumbnail,
        ]);

        $respon = [
            'status' => 'success',
            'msg' => 'Success updated category',
            'errors' => null,
            'content' => [
                'status_code' => 200,
                'data' => $article
            ]
        ];
        return response()->json($respon, 200);
    }

    public function delete(Article $article)
    {
        if(strtoupper(auth()->user()->role->name) !== "ADMIN"){
            if($article->user->id !== auth()->user()->id){
                $respon = [
                    'status' => 'error',
                    'msg' => 'You are not the author of this post',
                    'errors' => "Unauthorized action.",
                    'content' => [
                        'status_code' => 403
                    ]
                ];
                return response()->json($respon, 403);
            }
        }

        \Storage::delete($article->thumbnail);
        $article->delete();

        $respon = [
            'status' => 'success',
            'msg' => 'Success deleted Article',
            'errors' => null,
            'content' => [
                'status_code' => 200,
                'deleted article' => $article
            ]
        ];
        return response()->json($respon, 200);
    }
}
