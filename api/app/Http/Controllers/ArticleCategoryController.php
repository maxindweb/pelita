<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ArticleCategory;

class ArticleCategoryController extends Controller
{
    public function index()
    {
        return response()->json(ArrticleCategory::with('Article'), 200);
    }

    public function all()
    {
        return response()->json(ArticleCategory::with('Article')->whereHas('Article', function($query){
            $query->where('is_publish', true);
        })->get(), 200);
    }

    public function show(ArticleCategory $category){
        return response()->json(ArticleCategory::with('Article')->where('id', '=', $category->id)->whereHas('Article', function($query){
            $query->where('is_publish', true);
        })->get(), 200);
    }
    

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'category' => 'required|max:100',
            'slug'     => 'required:max:255'
        ]);


        if($validator->fail()){
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $category = ArticleCategory::create([
            'category'  => $request->category,
            'slug'      => $request->slug
        ]);

        return response()->json([
            'status'  => (bool)$category,
            'message' => $category ? 'Success Created Category' : 'Error Creating Category'  
        ]);
    }

    public function update()
    {
        $validator = Validator::make($request->all(),[
            'category' => 'required|max:100',
            'slug'     => 'required:max:255'
        ]);

        if($validator->fail()){
            return response()->json([
                'errors' => $validator->errors()
            ]);
        }

        $category = Category::find($id);
        $category->category = $request['category'];
        $category->slug     = $request['slug'];
        $category->save();

        return response()->json([
            'status'    => $category,
            'message'   => $category ? 'Category Updated' : 'Error Updating Category'
        ]);
    }

    public function destroy(ArticleCategory $category)
    {
        //
    }
}
