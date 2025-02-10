<?php

use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\CancelPage;
use App\Livewire\CartPage;
use App\Livewire\CategoriesPage;
use App\Livewire\CheakOutPage;
use App\Livewire\HomePage;
use App\Livewire\MyOdersDetailsPage;
use App\Livewire\MyOrderDetailPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\Productdetailspage;
use App\Livewire\ProductsPage;
use App\Livewire\SuccessPage;
use Illuminate\Support\Facades\Route;

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

Route::get('/',HomePage::class);
Route::get('/categories',CategoriesPage::class);
Route::get('/products',ProductsPage::class);
Route::get('/cart',CartPage::class);
Route::get('/products/{name}',Productdetailspage::class);


Route::middleware('guest')->group(function(){

Route::get('/login',LoginPage::class)->name('login');
Route::get('/register',RegisterPage::class);
Route::get('/forgot',ForgotPassword::class)->name('password.request');
Route::get('/reset/{token}',ResetPasswordPage::class)->name('password.reset');
    
});


Route::middleware('auth')->group(function(){

    Route::get('/logout',function(){
        auth()->logout();
        return redirect('/');
    });

Route::get('/cheakout',CheakOutPage::class);
Route::get('/my-orders',MyOrdersPage::class);
Route::get('/my-orders/{order_id}',MyOrderDetailPage::class)->name('my-order-show');
Route::get('/success',SuccessPage::class)->name('success');
Route::get('/cancel',CancelPage::class)->name('cancel');

});



