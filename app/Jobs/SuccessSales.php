<?php

namespace App\Jobs;

use App\Models\User;
use App\Helpers\Helper;
use App\Mail\AdminMail;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Log;

class SuccessSales implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected $user;
    protected $transaction_id;

    public function __construct($user, $transaction_id)
    {
        $this->user = $user;
        $this->transaction_id = $transaction_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
       try {
            User::where('id', $this->user->id)->update([
                'shoping_cart' => null,
            ]);
            $transaction = Transaction::where('user_id', $this->user->id)->where('transaction_id', $this->transaction_id);
            $transaction->update([
                'status' => 1,
                'date_payment' => now(),
            ]);
            $sales = $transaction->first();
            $products_id = array_keys($sales->products);
            $products = Product::whereIn('id', $products_id)->get();
            foreach($sales->products as $product_id => $sale) {
                $weight = (double) $sale['weight'];
                $count = (int) $sale['count'];
                $less_inventory = $weight > 0 ? $count * $weight : $count;
                $product = $products->where('id', $product_id)->first();
                $product->inventory -= $less_inventory;
                $product->sales_count += 1;
                $product->total_price_sales += (int) $sale['total_price'];
                $product->save();
            }
            $params = [
                'NAME' => $this->user->name,
                'TRANSACTION' => $this->transaction_id,
            ];
            Helper::sms($params, $this->user->mobile, config('tamyek.sms_template.success_sales'));
            Mail::to(config('tamyek.admin_email'))->send(new AdminMail('فروش جدید'));
       }catch(\Exception $e) {
            Log::alert("transaction_id: " + $this->transaction_id + ", for user_id: " + $this->user->id + ", error when saved data with error " + $e->getMessage());
       }
    }
}
