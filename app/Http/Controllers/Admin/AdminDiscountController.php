<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminDiscountController extends Controller
{
    public function index(Request $request)
    {
        $discounts = new Product();
        if($request->discount_type) {
            $discounts = $discounts->where('discount_type', $request->discount_type);
        }
        if($request->product_id) {
            $discounts = $discounts->where('id', $request->product_id);
        }
        $discounts = $discounts->where('discount_expire', '>=', now())->orderBy('discount_expire', 'desc')->get();
        $products = Product::select('id', 'name')->orderBy('category_id', 'asc')->get();

        return view('admin.discounts.index', compact('products', 'discounts'));
    }

    public function create()
    {
        $products = Product::select('id', 'name')->orderBy('category_id', 'asc')->get();

        return view('admin.discounts.create', compact('products'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);

        return view('admin.discounts.edit', compact('product'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'price' => 'nullable|numeric',
            'product_id' => 'required|numeric',
            'discount_type' => 'nullable|string',
            'discount_code' => 'nullable|string',
            'discount_amount' => 'nullable|numeric',
            'begin_date_d' => 'nullable|numeric|min:1|max:31',
            'begin_date_m' => 'nullable|numeric|min:1|max:12',
            'begin_date_y' => 'nullable|numeric',
            'expire_date_d' => 'nullable|numeric|min:1|max:31',
            'expire_date_m' => 'nullable|numeric|min:1|max:12',
            'expire_date_y' => 'nullable|numeric',
        ]);
        $product = Product::findOrFail($request->product_id);
        $product_price = $request->price > 1 ? $request->price : $product->price;
        $discount_begin = null;
        $discount_expire = null;
        $discounted_price = 0;
        $discount_type = null;
        $discount_code = null;
        $discount_amount = 0;
        if($request->expire_date_y && $request->begin_date_y) {
            $discount_begin = Helper::jalali_to_gregorian($request->begin_date_y, $request->begin_date_m, $request->begin_date_d, '-');
            $discount_expire = Helper::jalali_to_gregorian($request->expire_date_y, $request->expire_date_m, $request->expire_date_d, '-');
            $discount_expire_time = strtotime($discount_expire);
            if($discount_expire_time < time() || !$request->discount_type || !$request->discount_amount) {
                $discount_begin = null;
                $discount_expire = null;
            }
        }
        if($request->discount_type && $request->discount_amount && $discount_expire) {
            if($request->discount_type == 'public_percent' || $request->discount_type == 'code_percent') {
                if($request->discount_amount > 99 || $request->discount_amount < 0) {
                    session()->flash('message_danger', 'درصد تخفیف یا نوع درصد استباه است.');
                    return back();
                }
                if($request->discount_type == 'code_percent' && !$request->discount_code) {
                    session()->flash('message_danger', 'کد تخفیف را وارد نمایید.');
                    return back();
                }
                $percent = 100 - $request->discount_amount;
                $discounted_price = round($product_price * $percent / 100);
                $discount_type = $request->discount_type;
                $discount_amount = $request->discount_amount;
                if($request->discount_type == 'code_percent') {
                    $discount_code = $request->discount_code;
                }
            }elseif($request->discount_type == 'public_constant' || $request->discount_type == 'code_constant') {
                if($request->discount_type == 'code_constant' && !$request->discount_code) {
                    session()->flash('message_danger', 'کد تخفیف را وارد نمایید.');
                    return back();
                }
                $discounted_price = round($product_price - $request->discount_amount);
                $discount_type = $request->discount_type;
                $discount_amount = $request->discount_amount;
                if($request->discount_type == 'code_constant') {
                    $discount_code = $request->discount_code;
                }
            }
        }

        Product::where('id', $request->product_id)->update([
            'price' => $product_price,
            'discounted_price' => $discounted_price,
            'discount_type' => $discount_type,
            'discount_code' => $discount_code,
            'discount_begin' => $discount_begin,
            'discount_expire' => $discount_expire,
            'discount_amount' => $discount_amount,
        ]);

        session()->flash('message_success', 'تخفیف محصول مورد نظر با موفقیت ویرایش شد.');
        return back();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'price' => 'required|numeric',
            'discount_type' => 'nullable|string',
            'discount_code' => 'nullable|string',
            'discount_amount' => 'nullable|numeric',
            'begin_date_d' => 'nullable|numeric|min:1|max:31',
            'begin_date_m' => 'nullable|numeric|min:1|max:12',
            'begin_date_y' => 'nullable|numeric',
            'expire_date_d' => 'nullable|numeric|min:1|max:31',
            'expire_date_m' => 'nullable|numeric|min:1|max:12',
            'expire_date_y' => 'nullable|numeric',
        ]);
        $discount_begin = null;
        $discount_expire = null;
        $discounted_price = 0;
        $discount_type = null;
        $discount_code = null;
        $discount_amount = 0;
        if($request->expire_date_y && $request->begin_date_y) {
            $discount_begin = Helper::jalali_to_gregorian($request->begin_date_y, $request->begin_date_m, $request->begin_date_d, '-');
            $discount_expire = Helper::jalali_to_gregorian($request->expire_date_y, $request->expire_date_m, $request->expire_date_d, '-');
            $discount_expire_time = strtotime($discount_expire);
            if($discount_expire_time < time() || !$request->discount_type || !$request->discount_amount) {
                $discount_begin = null;
                $discount_expire = null;
            }
        }
        if($request->discount_type && $request->discount_amount && $discount_expire) {
            if($request->discount_type == 'public_percent' || $request->discount_type == 'code_percent') {
                if($request->discount_amount > 99 || $request->discount_amount < 0) {
                    session()->flash('message_danger', 'درصد تخفیف یا نوع درصد استباه است.');
                    return back();
                }
                if($request->discount_type == 'code_percent' && !$request->discount_code) {
                    session()->flash('message_danger', 'کد تخفیف را وارد نمایید.');
                    return back();
                }
                $percent = 100 - $request->discount_amount;
                $discounted_price = round($request->price * $percent / 100);
                $discount_type = $request->discount_type;
                $discount_amount = $request->discount_amount;
                if($request->discount_type == 'code_percent') {
                    $discount_code = $request->discount_code;
                }
            }elseif($request->discount_type == 'public_constant' || $request->discount_type == 'code_constant') {
                if($request->discount_type == 'code_constant' && !$request->discount_code) {
                    session()->flash('message_danger', 'کد تخفیف را وارد نمایید.');
                    return back();
                }
                $discounted_price = round($request->price - $request->discount_amount);
                $discount_type = $request->discount_type;
                $discount_amount = $request->discount_amount;
                if($request->discount_type == 'code_constant') {
                    $discount_code = $request->discount_code;
                }
            }
        }

        Product::where('id', $id)->update([
            'price' => $request->price,
            'discounted_price' => $discounted_price,
            'discount_type' => $discount_type,
            'discount_code' => $discount_code,
            'discount_begin' => $discount_begin,
            'discount_expire' => $discount_expire,
            'discount_amount' => $discount_amount,
        ]);

        session()->flash('message_success', 'تخفیف محصول مورد نظر با موفقیت ویرایش شد.');
        return back();
    }

    public function delete($id)
    {
        Product::where('id', $id)->update([
            'discounted_price' => null,
            'discount_type' => null,
            'discount_code' => null,
            'discount_begin' => null,
            'discount_expire' => null,
            'discount_amount' => null,
        ]);

        session()->flash('message_seccess', 'تخفیف محصول مورد نظر حذف شد.');
        return back();
    }
}
