<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Models\Comment;
use App\Models\Product;
use Spatie\Image\Image;
use App\Models\Category;
use App\Models\Nutrient;
use App\Models\Pnutrient;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::select('id', 'name')->get();
        $products = Product::all();

        return view('admin.products.index', compact('categories', 'products'));
    }

    public function comments($id)
    {
        $product = Product::findOrFail($id);
        $comments = Comment::where('product_id', $id)->where('status', 1)->orderBy('created_at', 'desc')->paginate(50);

        return view('admin.products.comments', compact('product', 'comments'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'category' => 'nullable|numeric',
            'word' => 'nullable|string'
        ]);

        $categories = Category::select('id', 'name')->get();
        $products = null;
        if ($request->category) {
            $products = Product::where('category_id', $request->category)->paginate(50);
        }
        if ($request->word) {
            $products = Product::where('name', 'like', "%$request->word%")->orderByRaw("length(name) asc")->paginate(50);
        }

        return view('admin.products.index', compact('categories', 'products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        $category = Category::find($product->category_id);

        return view('admin.products.show', compact('category', 'product'));
    }

    public function create()
    {
        $categories = Category::select('id', 'name')->get();
        $nutrients = Cache::remember('nutrients', 30 * 24 * 60 * 60, function () {
            return Nutrient::all();
        });

        return view('admin.products.create', compact('categories', 'nutrients'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::select('id', 'name')->get();
        $nutrients = Cache::remember('nutrients', 30 * 24 * 60 * 60, function () {
            return Nutrient::all();
        });
        $product_nutrients = Pnutrient::where('product_id', $id)->get();

        return view('admin.products.edit', compact('product', 'categories', 'nutrients', 'product_nutrients'));
    }

    public function store(Request $request)
    {
        $images_count = $request->images_count;
        $validate = [
            'name' => 'required|string|max:60',
            'price' => 'required|numeric',
            'category' => 'required|numeric',
            'inventory' => 'required|numeric',
            'unit' => 'required|string',
            'pack_weight' => 'nullable|numeric',
            'pack_unit' => 'nullable|string',
            'min_order' => 'required|numeric',
            'type' => 'required|string|in:khorde,omde',
            'discount_type' => 'nullable|string',
            'discount_code' => 'nullable|string',
            'discount_amount' => 'nullable|numeric',
            'begin_date_d' => 'nullable|numeric|min:1|max:31',
            'begin_date_m' => 'nullable|numeric|min:1|max:12',
            'begin_date_y' => 'nullable|numeric',
            'expire_date_d' => 'nullable|numeric|min:1|max:31',
            'expire_date_m' => 'nullable|numeric|min:1|max:12',
            'expire_date_y' => 'nullable|numeric',
            'description' => 'required|string',
            'meta_description' => 'required|string|max:160',
            'keywords' => 'nullable|array',
            'keywords.*' => 'required|string',
            'images_count' => 'required|numeric',
            'nutrients' => 'required|array',
            'nutrients.*' => 'nullable|string',
            'links' => 'nullable|array',
            'links.*' => 'nullable|string',
            'other' => 'nullable|string'
        ];

        if ($request->type == 'omde') {
            if ($request->unit != 'kg') {
                session()->flash('message_danger', 'واحد فروش عمده نمی تواند به غیر از کیلوگرم باشد');
                return back();
            }
            if ($request->discount_type) {
                session()->flash('message_danger', 'فروش عمده نمی تواند تخفیف داشته باشد.');
                return back();
            }
        }

        for ($i = 0; $i < $images_count; $i++) {
            $validate["images_$i"] = 'nullable|image|mimes:png,jpg,jpeg,webp,gif|max:4096';
        }
        $request->validate($validate);
        $product_images = [];
        for ($i = 0; $i < $images_count; $i++) {
            $image = $request->hasFile("images_$i");
            if ($image) {
                $uploadedFile = $request->file("images_$i");
                $originalPath = $uploadedFile->getPathname();
                $name = time() . '_' . rand(1000, 10000) . '.webp';
                $path = "/images/products/$name";
                Image::load($originalPath)
                    ->width(1024)
                    ->format('webp')
                    ->optimize()
                    ->save(public_path($path));
                $product_images[] = $path;
            }
        }
        $discount_begin = null;
        $discount_expire = null;
        $discounted_price = 0;
        $discount_type = null;
        $discount_code = null;
        $discount_amount = 0;
        if ($request->expire_date_y && $request->begin_date_y) {
            $discount_begin = Helper::jalali_to_gregorian($request->begin_date_y, $request->begin_date_m, $request->begin_date_d, '-');
            $discount_expire = Helper::jalali_to_gregorian($request->expire_date_y, $request->expire_date_m, $request->expire_date_d, '-');
            $discount_expire_time = strtotime($discount_expire);
            if ($discount_expire_time < time() || !$request->discount_type || !$request->discount_amount) {
                $discount_begin = null;
                $discount_expire = null;
            }
        }
        if ($request->discount_type && $request->discount_amount && $discount_expire) {
            if ($request->discount_type == 'public_percent' || $request->discount_type == 'code_percent') {
                if ($request->discount_amount > 99 || $request->discount_amount < 0) {
                    session()->flash('message_danger', 'درصد تخفیف یا نوع درصد استباه است.');
                    return back();
                }
                if ($request->discount_type == 'code_percent' && !$request->discount_code) {
                    session()->flash('message_danger', 'کد تخفیف را وارد نمایید.');
                    return back();
                }
                $percent = 100 - $request->discount_amount;
                $discounted_price = round($request->price * $percent / 100);
                $discount_type = $request->discount_type;
                $discount_amount = $request->discount_amount;
                if ($request->discount_type == 'code_percent') {
                    $discount_code = $request->discount_code;
                }
            } elseif ($request->discount_type == 'public_constant' || $request->discount_type == 'code_constant') {
                if ($request->discount_type == 'code_constant' && !$request->discount_code) {
                    session()->flash('message_danger', 'کد تخفیف را وارد نمایید.');
                    return back();
                }
                $discounted_price = round($request->price - $request->discount_amount);
                $discount_type = $request->discount_type;
                $discount_amount = $request->discount_amount;
                if ($request->discount_type == 'code_constant') {
                    $discount_code = $request->discount_code;
                }
            }
        }
        $unit = $request->unit;
        if ($unit == 'pack') {
            $unit = $request->pack_weight . "_" . $request->pack_unit;
        }

        $other = $request->other;
        if ($request->type == 'omde' && $request->other != null) {
            $other = json_decode($request->other, true);
            ksort($other);
            $other = json_encode($other);
        }

        $product = Product::create([
            'name' => $request->name,
            'images' => $product_images,
            'price' => $request->price,
            'discounted_price' => $discounted_price,
            'description' => $request->description,
            'category_id' => $request->category,
            'inventory' => $request->inventory,
            'unit' => $unit,
            'min_order' => $request->min_order,
            'type' => $request->type,
            'discount_type' => $discount_type,
            'discount_code' => $discount_code,
            'discount_begin' => $discount_begin,
            'discount_expire' => $discount_expire,
            'discount_amount' => $discount_amount,
            'meta_description' => $request->meta_description,
            'keywords' => $request->keywords,
            'links' => $request->links,
            'other' => $request->other
        ]);
        $nutrients_value = array_filter($request->nutrients, fn($value) => !is_null($value) && $value !== '');
        $nutrients_id = array_keys($nutrients_value);
        $nutrients = Nutrient::find($nutrients_id);
        foreach ($nutrients_value as $key => $value) {
            $value_arr = explode('|', $value);
            $nutrient_amount = $value_arr[0];
            $nutrient_percent = isset($value_arr[1]) ? $value_arr[1] : null;
            $nutrient = $nutrients->firstWhere('id', $key);
            $pnutrient = new Pnutrient();
            $pnutrient->product_id = $product->id;
            $pnutrient->nutrient_id = $nutrient->id;
            $pnutrient->nutrient_name = $nutrient->name;
            $pnutrient->amount = $nutrient_amount;
            if ($nutrient_percent) {
                $pnutrient->percent = $nutrient_percent;
            }
            $pnutrient->save();
        }

        session()->flash('message_success', 'محصول جدید با موفقیت ایجاد شد.');
        return back();
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $images_count = $request->images_count;
        $validate = [
            'name' => 'required|string|max:60',
            'price' => 'required|numeric',
            'category' => 'required|numeric',
            'inventory' => 'required|numeric',
            'unit' => 'required|string',
            'pack_weight' => 'nullable|numeric',
            'pack_unit' => 'nullable|string',
            'min_order' => 'required|numeric',
            'type' => 'required|string|in:khorde,omde',
            'discount_type' => 'nullable|string',
            'discount_code' => 'nullable|string',
            'discount_amount' => 'nullable|numeric',
            'begin_date_d' => 'nullable|numeric|min:1|max:31',
            'begin_date_m' => 'nullable|numeric|min:1|max:12',
            'begin_date_y' => 'nullable|numeric',
            'expire_date_d' => 'nullable|numeric|min:1|max:31',
            'expire_date_m' => 'nullable|numeric|min:1|max:12',
            'expire_date_y' => 'nullable|numeric',
            'description' => 'required|string',
            'meta_description' => 'required|string|max:160',
            'keywords' => 'nullable|array',
            'keywords.*' => 'required|string',
            'images_count' => 'required|numeric',
            'deleted_images' => 'nullable|array',
            'deleted_images.*' => 'required|numeric',
            'nutrients' => 'required|array',
            'nutrients.*' => 'nullable|string',
            'links' => 'nullable|array',
            'links.*' => 'nullable|string',
            'other' => 'nullable|string'
        ];

        if ($request->type == 'omde') {
            if ($request->unit != 'kg') {
                session()->flash('message_danger', 'واحد فروش عمده نمی تواند به غیر از کیلوگرم باشد');
                return back();
            }
            if ($request->discount_type) {
                session()->flash('message_danger', 'فروش عمده نمی تواند تخفیف داشته باشد.');
                return back();
            }
        }

        for ($i = 0; $i < $images_count; $i++) {
            $validate["images_$i"] = 'nullable|image|mimes:png,jpg,jpeg,webp,gif|max:4096';
        }
        $request->validate($validate);
        $deleted_images = $request->deleted_images;
        $product_images = $product->images;
        if ($deleted_images) {
            foreach ($deleted_images as $index) {
                File::delete(public_path($product_images[$index]));
                unset($product_images[$index]);
            }
        }
        for ($i = 0; $i < $images_count; $i++) {
            $image = $request->hasFile("images_$i");
            if ($image) {
                $uploadedFile = $request->file("images_$i");
                $originalPath = $uploadedFile->getPathname();
                $name = time() . '_' . rand(1000, 10000) . '.webp';
                $path = "/images/products/$name";
                Image::load($originalPath)
                    ->width(1024)
                    ->format('webp')
                    ->optimize()
                    ->save(public_path($path));

                if (isset($product_images[$i])) {
                    File::delete(public_path($product_images[$i]));
                }
                $product_images[$i] = $path;
            }
        }
        $discount_begin = null;
        $discount_expire = null;
        $discounted_price = 0;
        $discount_type = null;
        $discount_code = null;
        $discount_amount = 0;
        if ($request->expire_date_y && $request->begin_date_y) {
            $discount_begin = Helper::jalali_to_gregorian($request->begin_date_y, $request->begin_date_m, $request->begin_date_d, '-');
            $discount_expire = Helper::jalali_to_gregorian($request->expire_date_y, $request->expire_date_m, $request->expire_date_d, '-');
            $discount_expire_time = strtotime($discount_expire);
            if ($discount_expire_time < time() || !$request->discount_type || !$request->discount_amount) {
                $discount_begin = null;
                $discount_expire = null;
            }
        }
        if ($request->discount_type && $request->discount_amount && $discount_expire) {
            if ($request->discount_type == 'public_percent' || $request->discount_type == 'code_percent') {
                if ($request->discount_amount > 99 || $request->discount_amount < 0) {
                    session()->flash('message_danger', 'درصد تخفیف یا نوع درصد استباه است.');
                    return back();
                }
                if ($request->discount_type == 'code_percent' && !$request->discount_code) {
                    session()->flash('message_danger', 'کد تخفیف را وارد نمایید.');
                    return back();
                }
                $percent = 100 - $request->discount_amount;
                $discounted_price = round($request->price * $percent / 100);
                $discount_type = $request->discount_type;
                $discount_amount = $request->discount_amount;
                if ($request->discount_type == 'code_percent') {
                    $discount_code = $request->discount_code;
                }
            } elseif ($request->discount_type == 'public_constant' || $request->discount_type == 'code_constant') {
                if ($request->discount_type == 'code_constant' && !$request->discount_code) {
                    session()->flash('message_danger', 'کد تخفیف را وارد نمایید.');
                    return back();
                }
                $discounted_price = round($request->price - $request->discount_amount);
                $discount_type = $request->discount_type;
                $discount_amount = $request->discount_amount;
                if ($request->discount_type == 'code_constant') {
                    $discount_code = $request->discount_code;
                }
            }
        }
        $nutrients_value = array_filter($request->nutrients);
        $nutrients_id = array_keys($nutrients_value);
        $nutrients = Nutrient::find($nutrients_id);
        Pnutrient::where('product_id', $product->id)->delete();
        foreach ($nutrients_value as $key => $value) {
            $value_arr = explode('|', $value);
            $nutrient_amount = trim($value_arr[0]);
            $nutrient_percent = isset($value_arr[1]) ? Helper::en_digits(trim($value_arr[1])) : null;
            $nutrient = $nutrients->firstWhere('id', $key);
            $pnutrient = new Pnutrient();
            $pnutrient->product_id = $product->id;
            $pnutrient->nutrient_id = $nutrient->id;
            $pnutrient->nutrient_name = $nutrient->name;
            $pnutrient->amount = $nutrient_amount;
            if ($nutrient_percent) {
                $pnutrient->percent = $nutrient_percent;
            }
            $pnutrient->save();
        }
        $unit = $request->unit;
        if ($unit == 'pack') {
            $unit = $request->pack_weight . "_" . $request->pack_unit;
        }
        $other = $request->other;
        if ($request->type == 'omde' && $request->other != null) {
            $other = json_decode($request->other, true);
            ksort($other);
            $other = json_encode($other);
        }

        Product::where('id', $product->id)->update([
            'name' => $request->name,
            'images' => array_values($product_images),
            'price' => $request->price,
            'discounted_price' => $discounted_price,
            'description' => $request->description,
            'category_id' => $request->category,
            'inventory' => $request->inventory,
            'unit' => $unit,
            'min_order' => $request->min_order,
            'type' => $request->type,
            'discount_type' => $discount_type,
            'discount_code' => $discount_code,
            'discount_begin' => $discount_begin,
            'discount_expire' => $discount_expire,
            'discount_amount' => $discount_amount,
            'meta_description' => $request->meta_description,
            'keywords' => $request->keywords,
            'links' => $request->links,
            'other' => $other
        ]);

        session()->flash('message_success', 'محصول مورد نظر با موفقیت ویرایش شد.');
        // return to_route('admin.products.index')->withFragment('#row_' . $product->id);
        return back();
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        Pnutrient::where('product_id', $id)->delete();
        Comment::where('product_id', $id)->delete();
        $images = $product->images;
        Product::where('id', $id)->delete();
        foreach ($images as $image) {
            File::delete(public_path($image));
        }
        session()->flash('message_success', 'محصول مورد نظر با موفقیت حذف شد.');
        return back();
    }

    public function inventory(Request $request)
    {
        $request->validate([
            'category' => 'nullable|numeric',
            'word' => 'nullable|string'
        ]);

        $categories = Category::select('id', 'name')->get();
        $products = Product::query();
        if ($request->category) {
            $products = $products->where('category_id', $request->category);
        }
        if ($request->word) {
            $products = $products->where('name', 'like', "%$request->word%")->orderByRaw("length(name) asc");
        }

        $products = $products->paginate(50);

        return view('admin.products.inventory', compact('categories', 'products'));
    }

    public function save_inventory(Request $request, $id)
    {
        $request->validate([
            'inventory' => 'required|numeric|min:0'
        ]);

        Product::where('id', $id)->update([
            'inventory' => $request->inventory
        ]);

        session()->flash('message_success', 'موجودی محصول مورد نظر با موفقیت ذخیره شد.');
        return back()->withFragment('#row_' . $id);
    }
}
