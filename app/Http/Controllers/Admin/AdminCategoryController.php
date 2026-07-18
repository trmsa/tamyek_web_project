<?php

namespace App\Http\Controllers\Admin;

use Spatie\Image\Image;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return view('admin.category.index', compact('categories'));
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:60',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:4096',
            'meta_description' => 'required|string|max:199',
        ]);
        $category = Category::findOrFail($id);
        if($request->hasFile('image')) {
            $uploadedFile = $request->file("image");
            $originalPath = $uploadedFile->getPathname();
            $name = time(). '_'. rand(1000, 10000). '.webp';
            $path = "/images/products/$name";
            Image::load($originalPath)
                ->width(400)
                ->format('webp')
                ->optimize()
                ->save(public_path($path));
            File::delete(public_path($category->image));
            Category::where('id', $id)->update([
                'name' => $request->name,
                'meta_description' => $request->meta_description,
                'image' => $path
            ]);
        }else {
            Category::where('id', $id)->update([
                'name' => $request->name,
                'meta_description' => $request->meta_description,
            ]);
        }
        session()->flash('message_success', 'دسته بندی با موفقیت ویرایش شد.');

        return back();
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:60',
            'image' => 'required|image|mimes:png,jpg,jpeg,webp|max:4096',
            'meta_description' => 'required|string|max:199',
        ]);
        $uploadedFile = $request->file("image");
        $originalPath = $uploadedFile->getPathname();
        $name = time(). '_'. rand(1000, 10000). '.webp';
        $path = "/images/products/$name";
        Image::load($originalPath)
            ->width(400)
            ->format('webp')
            ->optimize()
            ->save(public_path($path));
        Category::create([
            'name' => $request->name,
            'meta_description' => $request->meta_description,
            'image' => $path
        ]);
        session()->flash('message_success', 'دسته‌بندی جدید با موفقیت ایجاد شد.');

        return back();
    }

    public function delete($id)
    {
        $category = Category::findorFail($id);
        Category::where('id', $id)->delete();
        File::delete(public_path($category->image));
        session()->flash('message_success', 'دسته‌بندی با موفقیت حذف شد');

        return back();
    }
}
