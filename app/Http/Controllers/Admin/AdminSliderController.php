<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slider;
use Spatie\Image\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class AdminSliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::all();

        return view('admin.sliders.index', compact('sliders'));
    }

    public function edit($id)
    {
        $slider = Slider::findOrFail($id);

        return view('admin.sliders.edit', compact('slider'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:60',
            'text' => 'nullable|string|max:500',
            'for' => 'required|string',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,webp,gif|max:4096'
        ]);
        $slider = Slider::findOrFail($id);
        if($request->hasFile('image')) {

            $uploadedFile = $request->file("image");
            $extention = $uploadedFile->getClientOriginalExtension();
            $name = time(). '_'. rand(1000, 10000). ".$extention";
            $path = "/images/sliders/$name";
            $uploadedFile->move(public_path('images/sliders'), $name);
            File::delete(public_path($slider->image));
            Slider::where('id', $id)->update([
                'title' => $request->title,
                'text' => $request->text,
                'for' => $request->for,
                'image' => $path
            ]);
        }else {
            Slider::where('id', $id)->update([
                'title' => $request->title,
                'text' => $request->text,
                'for' => $request->for,
            ]);
        }
        session()->flash('message_success', 'اسلایدر با موفقیت ویرایش شد.');

        return back();
    }

    public function create()
    {
        return view('admin.sliders.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:60',
            'text' => 'nullable|string|max:500',
            'for' => 'required|string',
            'image' => 'required|image|mimes:png,jpg,jpeg,webp,gif|max:4096'
        ]);
        $uploadedFile = $request->file("image");
        $extention = $uploadedFile->getClientOriginalExtension();
        $name = time(). '_'. rand(1000, 10000). ".$extention";
        $path = "/images/sliders/$name";
        $uploadedFile->move(public_path('images/sliders'), $name);
        Slider::create([
            'title' => $request->title,
            'text' => $request->text,
            'for' => $request->for,
            'image' => $path
        ]);
        session()->flash('message_success', 'اسلایدر جدید با موفقیت ایجاد شد.');

        return back();
    }

    public function delete($id)
    {
        $slider = Slider::findorFail($id);
        Slider::where('id', $id)->delete();
        File::delete(public_path($slider->image));
        session()->flash('message_success', 'اسلایدر با موفقیت حذف شد');

        return back();
    }
}
