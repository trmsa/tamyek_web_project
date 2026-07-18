<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Admin\AdminHomeController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminFactsController;
use App\Http\Controllers\Admin\AdminSalesController;
use App\Http\Controllers\Admin\AdminTiketController;
use App\Http\Controllers\Admin\AdminPostalController;
use App\Http\Controllers\Admin\AdminSliderController;
use App\Http\Controllers\Admin\AdminArticleController;
use App\Http\Controllers\Admin\AdminCommentController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminDiscountController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['HtmlMinifier'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware(['guest', 'throttle:10, 1']);
    Route::post('/login', [AuthController::class, 'verify_sms'])->name('verify_sms')->middleware(['guest', 'throttle:5, 1']);
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
    Route::post('/check-verify', [AuthController::class, 'check_verify'])->name('check_verify')->middleware(['guest', 'throttle:10, 1']);
    Route::get('/user', [UserController::class, 'index'])->name('user.index')->middleware('auth');
    Route::get('/user/edit', [UserController::class, 'edit'])->name('user.edit')->middleware('auth');
    Route::post('/user/update', [UserController::class, 'update'])->name('user.update')->middleware('auth');
    Route::post('/user/update-mobile', [UserController::class, 'update_mobile'])->name('user.update_mobile')->middleware('auth');
    Route::get('/user/shoping-cart', [UserController::class, 'show_shoping_cart'])->name('cart.index')->middleware('auth');
    Route::post('/user/shoping-cart', [UserController::class, 'add_shoping_cart'])->name('cart.store')->middleware('auth');
    Route::get('/user/shoping-cart/delete', [UserController::class, 'delete_shoping_cart'])->name('cart.delete')->middleware('auth');
    Route::get('/user/records', [UserController::class, 'records'])->name('user.records')->middleware('auth');
    Route::get('/user/records/show', [UserController::class, 'show_record'])->name('user.records.show')->middleware('auth');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/discounts', [ProductController::class, 'discounts'])->name('discounts');
    Route::get('/category', [ProductController::class, 'category'])->name('category');
    Route::get('/category/{id}', [ProductController::class, 'products_category'])->name('products_category');
    Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
    Route::get('/tiket', [ContactController::class, 'tiket'])->middleware('auth')->name('tiket.index');
    Route::post('/tiket', [ContactController::class, 'store'])->middleware('auth')->name('tiket.store');
    Route::delete('/tiket', [ContactController::class, 'delete'])->middleware('auth')->name('tiket.delete');
    Route::get('/pupolar', [ProductController::class, 'pupolar'])->name('pupolar');
    Route::get('/bestselling', [ProductController::class, 'bestselling'])->name('bestselling');
    Route::get('/most-visited', [ProductController::class, 'most_visited'])->name('most_visited');
    Route::get('/rules', [HomeController::class, 'rules'])->name('rules');
    Route::get('/about', [HomeController::class, 'about'])->name('about');
    Route::get('/search', [ProductController::class, 'search'])->name('search');
    Route::get('/favorits', [UserController::class, 'favorits'])->name('favorits.index')->middleware('auth');
    Route::get('/favorits/update', [UserController::class, 'update_favorits'])->name('favorits.update')->middleware('auth');
    Route::post('/comments/{product_id}', [CommentController::class, 'store'])->name('comments.store')->middleware('auth');
    Route::post('/pay', [PayController::class, 'pay'])->name('pay')->middleware('auth');
    Route::post('/pay/nuts', [PayController::class, 'pay_nuts'])->name('pay_nuts')->middleware('auth');
    Route::get('/payback-zarinpal', [PayController::class, 'payback_zarinpal'])->name('payback_zarinpal');
    Route::any('/payback-refah', [PayController::class, 'payback_refah'])->name('payback_refah');
    Route::get('/api_pay', [PayController::class, 'api_pay'])->name('api_pay');
    Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
    Route::post('/articles/comments/{article_id}', [ArticleController::class, 'comments'])->name('articles.comments');
    Route::get('/articles/{id}', [ArticleController::class, 'show'])->name('articles.show');
    Route::get('/nuts', [ProductController::class, 'nuts'])->name('nuts');
    Route::get('/sitemap.xml', [SitemapController::class, 'index']);
    Route::get('/sitemap-statics.xml', [SitemapController::class, 'statics']);
    Route::get('/sitemap-products.xml', [SitemapController::class, 'products']);
    Route::get('/sitemap-category.xml', [SitemapController::class, 'category']);
    Route::get('/sitemap-articles.xml', [SitemapController::class, 'articles']);
});

//admin routes
Route::prefix('kloaminmnb')->middleware(['auth', 'admin', 'throttle:10, 1', 'HtmlMinifier'])->group(function () {
    Route::get('/', [AdminHomeController::class, 'index'])->name('admin.home');
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/search', [AdminUserController::class, 'search'])->name('admin.users.search');
    Route::get('/users/{id}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::post('/users/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::get('/users/records/{id}', [AdminUserController::class, 'records'])->name('admin.users.records');
    Route::get('/users/records/products/{id}', [AdminUserController::class, 'record_products'])->name('admin.users.record_products');
    Route::delete('/users/{id}', [AdminUserController::class, 'delete'])->name('admin.users.delete');
    Route::get('/sales', [AdminSalesController::class, 'index'])->name('admin.sales.index');
    Route::post('/sales/send', [AdminSalesController::class, 'send'])->name('admin.sales.send');
    Route::get('/sales/{id}', [AdminSalesController::class, 'show'])->name('admin.sales.show');
    Route::get('/tikets', [AdminTiketController::class, 'index'])->name('admin.tikets.index');
    Route::post('/tikets', [AdminTiketController::class, 'store'])->name('admin.tikets.store');
    Route::get('/tikets/{id}', [AdminTiketController::class, 'show'])->name('admin.tikets.show');
    Route::delete('/tikets/{id}', [AdminTiketController::class, 'delete'])->name('admin.tikets.delete');
    Route::get('/category', [AdminCategoryController::class, 'index'])->name('admin.category.index');
    Route::post('/category', [AdminCategoryController::class, 'store'])->name('admin.category.store');
    Route::get('/category/create', [AdminCategoryController::class, 'create'])->name('admin.category.create');
    Route::get('/category/{id}/edit', [AdminCategoryController::class, 'edit'])->name('admin.category.edit');
    Route::put('/category/{id}', [AdminCategoryController::class, 'update'])->name('admin.category.update');
    Route::delete('/category/{id}', [AdminCategoryController::class, 'delete'])->name('admin.category.delete');
    Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products.index');
    Route::post('/products', [AdminProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
    Route::get('/products/inventory', [AdminProductController::class, 'inventory'])->name('admin.products.inventory');
    Route::get('/products/search', [AdminProductController::class, 'search'])->name('admin.products.search');
    Route::put('/products/{id}/inventory', [AdminProductController::class, 'save_inventory'])->name('admin.products.save_inventory');
    Route::get('/products/{id}', [AdminProductController::class, 'show'])->name('admin.products.show');
    Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
    Route::get('/products/{id}/comments', [AdminProductController::class, 'comments'])->name('admin.products.comments');
    Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}', [AdminProductController::class, 'delete'])->name('admin.products.delete');
    Route::get('/comments', [AdminCommentController::class, 'index'])->name('admin.comments.index');
    Route::post('/comments/{id}/answer', [AdminCommentController::class, 'answer'])->name('admin.comments.answer');
    Route::get('/comments/{id}', [AdminCommentController::class, 'show'])->name('admin.comments.show');
    Route::post('/comments/{id}', [AdminCommentController::class, 'confirm'])->name('admin.comments.confirm');
    Route::delete('/comments/{id}', [AdminCommentController::class, 'delete'])->name('admin.comments.delete');
    Route::get('/discounts', [AdminDiscountController::class, 'index'])->name('admin.discounts.index');
    Route::get('/discounts/create', [AdminDiscountController::class, 'create'])->name('admin.discounts.create');
    Route::get('/discounts/{id}/edit', [AdminDiscountController::class, 'edit'])->name('admin.discounts.edit');
    Route::post('/discounts', [AdminDiscountController::class, 'store'])->name('admin.discounts.store');
    Route::put('/discounts/{id}', [AdminDiscountController::class, 'update'])->name('admin.discounts.update');
    Route::delete('/discounts/{id}', [AdminDiscountController::class, 'delete'])->name('admin.discounts.delete');
    Route::get('/facts', [AdminFactsController::class, 'index'])->name('admin.facts.index');
    Route::get('/facts/users/city', [AdminFactsController::class, 'users_city'])->name('admin.facts.users.city');
    Route::get('/facts/users/sales', [AdminFactsController::class, 'users_sales'])->name('admin.facts.users.sales');
    Route::get('/facts/sales', [AdminFactsController::class, 'sales_index'])->name('admin.facts.sales.index');
    Route::get('/facts/products/inventory', [AdminFactsController::class, 'products_inventory'])->name('admin.facts.products.inventory');
    Route::get('/facts/products/sales', [AdminFactsController::class, 'products_sales'])->name('admin.facts.products.sales');
    Route::get('/facts/products/likes', [AdminFactsController::class, 'products_likes'])->name('admin.facts.products.likes');
    Route::get('/postal', [AdminPostalController::class, 'index'])->name('admin.postal.index');
    Route::post('/postal', [AdminPostalController::class, 'store'])->name('admin.postal.store');
    Route::get('/sliders', [AdminSliderController::class, 'index'])->name('admin.sliders.index');
    Route::post('/sliders', [AdminSliderController::class, 'store'])->name('admin.sliders.store');
    Route::get('/sliders/create', [AdminSliderController::class, 'create'])->name('admin.sliders.create');
    Route::get('/sliders/{id}/edit', [AdminSliderController::class, 'edit'])->name('admin.sliders.edit');
    Route::put('/sliders/{id}', [AdminSliderController::class, 'update'])->name('admin.sliders.update');
    Route::delete('/sliders/{id}', [AdminSliderController::class, 'delete'])->name('admin.sliders.delete');
    Route::get('/articles', [AdminArticleController::class, 'index'])->name('admin.articles.index');
    Route::post('/articles', [AdminArticleController::class, 'store'])->name('admin.articles.store');
    Route::get('/articles/create', [AdminArticleController::class, 'create'])->name('admin.articles.create');
    Route::get('/articles/{id}/edit', [AdminArticleController::class, 'edit'])->name('admin.articles.edit');
    Route::put('/articles/{id}', [AdminArticleController::class, 'update'])->name('admin.articles.update');
    Route::delete('/articles/{id}', [AdminArticleController::class, 'delete'])->name('admin.articles.delete');
    Route::post('/articles/search', [AdminArticleController::class, 'search'])->name('admin.articles.search');
    Route::get('/articles/comments', [AdminArticleController::class, 'comments'])->name('admin.articles.comments');
    Route::post('/articles/comments/answer/{id}', [AdminArticleController::class, 'answer_comment'])->name('admin.articles.answer_comment');
    Route::get('/articles/comments/{id}', [AdminArticleController::class, 'show_comment'])->name('admin.articles.show_comment');
    Route::post('/articles/comments/{id}', [AdminArticleController::class, 'confirm_comment'])->name('admin.articles.confirm_comment');
    Route::delete('/articles/comments/{id}', [AdminArticleController::class, 'delete_comment'])->name('admin.articles.delete_comment');
    Route::get('/articles/test', [AdminArticleController::class, 'test']);
    Route::post('/download-file', [AdminUserController::class, 'download_file'])->name('admin.user.download_file');
    Route::delete('/clear-trash', [AdminUserController::class, 'clear_trash'])->name('admin.clear_trash');
    Route::delete('/clear-tokens', [AdminUserController::class, 'clear_tokens'])->name('admin.clear_tokens');
    Route::get('/sessions', [AdminUserController::class, 'sessions'])->name('admin.sessions');
    Route::get('/tokens', [AdminUserController::class, 'tokens'])->name('admin.tokens');
    Route::delete('/session', [AdminUserController::class, 'delete_session'])->name('admin.delete_session');
    Route::delete('/token', [AdminUserController::class, 'delete_token'])->name('admin.delete_token');
    Route::get('/app-version', [AdminUserController::class, 'app_version'])->name('admin.app_version');
    Route::post('/app-version/update', [AdminUserController::class, 'change_app_version'])->name('admin.change_app_version');
});
