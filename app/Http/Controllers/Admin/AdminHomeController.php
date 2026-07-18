<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Tiket;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class AdminHomeController extends Controller
{
    public function index()
    {
        $sales = Transaction::where('status', 1)->whereNotNull('date_payment')->select('date_send', 'final_price_products')->get();
        $all_sales_count = $sales->count();
        $new_sales_count = $sales->whereNull('date_send')->count();
        $total_price_sales = $sales->sum('final_price_products');
        $users_count = User::count();
        $new_tikets = Tiket::where('owner', 'user')->where('delete_user', 0)->where('read', 0)->count();
        $new_comments = Comment::where('status', 0)->count();

        return view('admin.home', compact('all_sales_count', 'new_sales_count', 'total_price_sales', 'users_count', 'new_tikets', 'new_comments'));
    }
}
