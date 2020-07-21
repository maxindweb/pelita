<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Article;

class ArticleController extends Controller
{
    public function index()
    {
        return response()->json(Article::all(), 200);
    }

    public function all()
    {
        return response()->json(Article::where('is_publsih' ,'=', true)->get(5));
    }

    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
           'image'     => 'required|mime:jpeg,png,svg,webp,jpg',
            'name'      => 'required',
            'alt'       => 'required'
        ]);

        if($request->hasFile('image')){
            foreach ($request->file('image') as $image) {
                //
            }
        }

        $image = Image::create([
            // 'path' => 
        ]);
    }

    public function uploadThumbnail(Request $request, $name=null)
    {
        $validator = Validator::make($request->all(),[
            'image'     => 'required',
            'caption'   => 'required',
            'alt'       => 'required'
        ]);

        if($validator->fail()){
            return response()->json([
                'error' => $validator->errors()
            ]);
        }

        if($request->hasFile('image')){
            $image = $request->file('image');
            
            if(is_null($name)){
                $name = $image->getClientOriginalName() . "." . $image->getClientOriginalExtension() ;
            }

            $image->move(public_path('image'),$name);

            return '/image/'.$name;
        }
    }

    public function store(Request $request)
    {

        $validator = Validaor::make($request->all(),[
            'title'         => 'required|unique:article',
            'desvription'   => 'required',
            'slug'          => 'required',
            'body'          => 'required',
        ]);

        if($validator->fail()){
            return response()->json([
                'error'  => $validator->errors()
            ]);
        }

        $article = Article::create([
            'title'         => $request->title,
            'description'   => $request->description,
            'slug'          => $request->slug | str::slug(),
            'thumbnail'     => $this->uploadThumbnail($request),
            'body'          => $request->body,
            'category_id'   => $request->category_id,
        ]);

        $article->tags()->attach($request->tags);

        return response()->json([
            'status'    => (bool)$article,
            'message'   => $article ? 'Success Created Article' : 'Error Creating Article'
        ]);
    }

    public function published(Request $request)
    {
        $article = Article::updateOrCreate(
            ['title'        => $request->title],
            ['slug'         => str::slug($request->title) ],
            ['thumbnail'    => $this->uploadThumbnail($request)],
            ['body'         => $request->body],
            ['category_id'  => $request->tag_id],
            ['published'    => true],
            ['published_at' => Carbon::now()]
        );

        $article->tags()->attach($request->tags);

        return response()->json([
            'status' => (bool) $article ,
            'message' => $article ? 'Article Published' : 'Error Publishing Article'
        ]);
    }

    public function archive(Request $request)
    {
        $status->is_publish = false;

        return response()->json([
            'message' => $status ? 'Article Archive' : 'Error Archive Article'
        ]);
    }

    public function update(Request $request)
    {
        $data = Article::find($id);
        $data->title        = $request['title'];
        $data->slug         = $request['slug'];
        $data->thumbnail    = $this->uploadThumbnail($request);
        $data->body         = $request['body'];
        $data->category_id  = $request['category_id'];
        $data->save();

        return response()->json([
            'status'    => $data,
            'mesaage'   => $data ? 'Success Updated Article' : 'Error Updating Article'
        ]);
    }

    public function delete()
    {
        $status = Article::delete();

        return response()->json([
            'status'    => (bool)$status,
            'message'   => $status ? 'Success Deleted Article' : 'Error Deleting Article'
        ]);
    }
}