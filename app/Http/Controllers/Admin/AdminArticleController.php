<?php

namespace App\Http\Controllers\Admin;

use App\Models\Article;
use Spatie\Image\Image;
use App\Models\Acomment;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class AdminArticleController extends Controller
{
    public function index()
    {
        $categories = Category::select('id', 'name')->get();
        $articles = Article::paginate(50);

        return view('admin.articles.index', compact('categories', 'articles'));
    }

    public function store(Request $request)
    {
        $images_count = $request->images_count;
        $validate = [
            'title' => 'required|string',
            'content' => 'required|string',
            'category' => 'required|string',
            'type' => 'required|string',
            'meta_description' => 'required|string',
            'keywords' => 'required|array',
            'keywords.*' => 'required|string',
            'published' => 'required|string',
            'auther' => 'required|string',
            'images_count' => 'required|numeric',
        ];
        for($i=0; $i<$images_count; $i++) {
            $validate["images_$i"] = 'nullable|image|mimes:png,jpg,jpeg,webp|max:4096';
        }
        $request->validate($validate);
        $content = $request->content;
        $article_images = [];
        for($i=0; $i<$images_count; $i++) {
            if($request->hasFile("images_$i")) {

                $uploadedFile = $request->file("images_$i");
                $originalPath = $uploadedFile->getPathname();
                $name = time(). '_'. rand(1000, 10000). '.webp';
                $path = "/images/articles/$name";
                Image::load($originalPath)
                    ->width(1024)
                    ->format('webp')
                    ->optimize()
                    ->save(public_path($path));
                $article_images["__image_$i"] = $path;
            }
        }
        $content = str_replace(array_keys($article_images), $article_images, $content);
        $content = str_replace("\r\n\r\n", "\r\n", $content);

        Article::create([
            'title' => $request->title,
            'content' => $content,
            'category' => $request->category,
            'type' => $request->type,
            'meta_description' => $request->meta_description,
            'keywords' => $request->keywords,
            'published' => $request->published == '1' ? now() : null,
            'auther' => $request->auther,
            'images' => array_values($article_images),
        ]);

        session()->flash('message_success', 'مقاله با موفقیت ذخیره/منتشر شد.');
        return back();

    }

    public function create()
    {
        $categories = Category::select('id', 'name')->get();
        return view('admin.articles.create', compact('categories'));
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        $categories = Category::select('id', 'name')->get();

        return view('admin.articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);
        $images_count = $request->images_count;
        $validate = [
            'title' => 'required|string',
            'content' => 'required|string',
            'category' => 'required|string',
            'type' => 'required|string',
            'meta_description' => 'required|string',
            'keywords' => 'required|array',
            'keywords.*' => 'required|string',
            'published' => 'required|string',
            'auther' => 'required|string',
            'images_count' => 'required|numeric',
            'deleted_images' => 'nullable|array',
            'deleted_images.*' => 'required|numeric',
        ];
        for($i=0; $i<$images_count; $i++) {
            $validate["images_$i"] = 'nullable|image|mimes:png,jpg,jpeg,webp|max:4096';
        }
        $request->validate($validate);
        $content = $request->content;
        $deleted_images = $request->deleted_images;
        $article_images = $article->images;
        $replase = [];
        if($deleted_images) {
            foreach($deleted_images as $index) {
                      File::delete(public_path($article_images[$index]));
                      unset($article_images[$index]);
            }
        }

        for($i=0; $i<$images_count; $i++) {
            if($request->hasFile("images_$i")) {

                $uploadedFile = $request->file("images_$i");
                $originalPath = $uploadedFile->getPathname();
                $name = time(). '_'. rand(1000, 10000). '.webp';
                $path = "/images/articles/$name";
                Image::load($originalPath)
                    ->width(1024)
                    ->format('webp')
                    ->optimize()
                    ->save(public_path($path));
                $replase["__image_$i"] = $path;
                if(isset($article_images[$i])) {
                    File::delete(public_path($article_images[$i]));
                }
                $article_images[$i] = $path;
            }
        }
        $content = str_replace(array_keys($replase), $replase, $content);

        $article->update([
            'title' => $request->title,
            'content' => $content,
            'category' => $request->category,
            'type' => $request->type,
            'meta_description' => $request->meta_description,
            'keywords' => $request->keywords,
            'published' => $article->published ?? $request->published == '1' ? now() : null,
            'auther' => $request->auther,
            'images' => array_values($article_images),
        ]);

        session()->flash('message_success', 'مقاله با موفقیت ویرایش شد.');
        return back();
    }

    public function delete($id)
    {
        $article = Article::findOrFail($id);
        $images = $article->images;
        $article->delete();
        foreach($images as $image) {
            File::delete(public_path($image));
        }
        session()->flash('message_success', 'مقاله مورد نظر با موفقیت حذف شد.');
        return back();
    }

    public function search(Request $request)
    {
        $request->validate([
            'category' => 'nullable|string',
            'word' => 'nullable|string'
        ]);

        $categories = Category::select('id', 'name')->get();
        $articles = null;
        $articles = Article::query();
        if($request->category) {
            $articles = $articles->where('category', $request->category);
        }
        if($request->word) {
            $articles = $articles->where('title', 'like', "%$request->word%")->orderByRaw("length(name) asc");
        }

        $articles = $articles->paginate(50);

        return view('admin.articles.index', compact('categories', 'articles'));
    }

    public function comments(Request $request)
    {
        $comments = new Acomment();
        if($request->status === '0') {
            $comments = $comments->where('status', 0);
        }elseif($request->status === '1') {
            $comments = $comments->where('status', 1);
        }
        if($request->article_id) {
            $comments = $comments->where('article_id', $request->article_id);
        }

        $comments = $comments->orderBy('created_at', 'desc')->paginate(50);
        $articles = Article::select('id', 'title')->orderBy('category', 'asc')->get();

        return view('admin.articles.comments', compact('comments', 'articles'));
    }

    public function show_comment($id)
    {
        $comment = Acomment::findOrFail($id);
        $article = Article::findOrFail($comment->article_id);

        return view('admin.articles.show_comments', compact('comment', 'article'));
    }

    public function confirm_comment($id)
    {
        $comment = Acomment::findorFail($id);
        $article = Article::findOrFail($comment->article_id);
        if($comment->status == 0) {
            Acomment::where('id', $id)->update([
                'status' => 1
            ]);
            $old_likes_count = (int) $article->likes_count;
            $old_likes = (double) $article->likes;
            $new_likes_count = $old_likes_count + 1;
            $new_likes = round(($old_likes * $old_likes_count + (int) $comment->like) / $new_likes_count, 1);

            Article::where('id', $article->id)->update([
                'likes' => $new_likes,
                'likes_count' => $new_likes_count
            ]);
        }

        session()->flash('message_success', 'کامنت مورد نظر تایید شد.');
        return back();
    }

    public function answer_comment(Request $request, $id)
    {
        $request->validate([
            'answer' => 'required|string'
        ]);
        $comment = Acomment::findorFail($id);
        $article = Article::findOrFail($comment->article_id);
        if($comment->status == 0) {
            Acomment::where('id', $id)->update([
                'status' => 1,
                'answer' => $request->answer
            ]);
            $old_likes_count = (int) $article->likes_count;
            $old_likes = (double) $article->likes;
            $new_likes_count = $old_likes_count + 1;
            $new_likes = round(($old_likes * $old_likes_count + (int) $comment->like) / $new_likes_count, 1);

            Article::where('id', $article->id)->update([
                'likes' => $new_likes,
                'likes_count' => $new_likes_count
            ]);
        }else {
            Acomment::where('id', $id)->update([
                'answer' => $request->answer
            ]);
        }

        session()->flash('message_success', 'پاسخ شما ثبت و کامنت مورد نظر تایید شد.');
        return back();
    }

    public function delete_comment($id)
    {
        Acomment::where('id', $id)->delete();
        session()->flash('success_message', 'کامنت مورد نظر با موفقیت حذف شد.');

        return back();
    }

    public function test()
    {
        return view('admin.articles.test');
    }
}
