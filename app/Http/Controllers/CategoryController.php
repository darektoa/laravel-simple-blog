<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::get();

        return response()->json($categories, 200);
    }

    public function create(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required'
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

        $category = Category::create([
            'name' => $request->name
        ]);

        $respon = [
            'status' => 'success',
            'msg' => 'Success create new category',
            'errors' => null,
            'content' => [
                'status_code' => 200,
                'category name' => $category->name
            ]
        ];
        return response()->json($respon, 200);
    }

    public function update(Request $request, Category $category)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required'
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

        $category->update([
            'name' => $request->name
        ]);

        $respon = [
            'status' => 'success',
            'msg' => 'Success updated category',
            'errors' => null,
            'content' => [
                'status_code' => 200,
                'category name' => $category->name
            ]
        ];
        return response()->json($respon, 200);
    }

    public function delete(Category $category)
    {
        $category->delete();

        $respon = [
            'status' => 'success',
            'msg' => 'Success deleted category',
            'errors' => null,
            'content' => [
                'status_code' => 200,
                'category name' => $category->name
            ]
        ];
        return response()->json($respon, 200);
    }
}
