<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Cms\FileManagerController;
use App\Http\Controllers\Cms\FileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\Post\CategoryController as PostCategoryController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ContactController;
use App\Models\Cms\Setting;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

$segments = Setting::getSegments('Post');
Route::get('/'.$segments['posts'].'/{id}/{slug}', [PostController::class, 'show'])->name('posts.show');
// Only authenticated users can post comments.
Route::post('/'.$segments['posts'].'/{id}/{slug}/comments', [PostController::class, 'saveComment'])->name('posts.comments')->middleware('auth');
Route::put('/'.$segments['posts'].'/comments/{comment}', [PostController::class, 'updateComment'])->name('posts.comments.update')->middleware('auth');
Route::delete('/'.$segments['posts'].'/comments/{comment}', [PostController::class, 'deleteComment'])->name('posts.comments.delete')->middleware('auth');
Route::get('/'.$segments['posts'].'/'.$segments['categories'].'/{id}/{slug}', [PostCategoryController::class, 'index'])->name('posts.categories');

Route::put('/memberships/licences', [MembershipController::class, 'updateLicences'])->name('memberships.licences.update');
Route::get('/memberships/create', [MembershipController::class, 'create'])->name('memberships.create');
Route::get('/memberships/edit', [MembershipController::class, 'edit'])->name('memberships.edit');
Route::get('/memberships/applicants', [MembershipController::class, 'applicants'])->name('memberships.applicants');
Route::get('/memberships/members', [MembershipController::class, 'members'])->name('memberships.members');
Route::get('/memberships/associated', [MembershipController::class, 'members'])->name('memberships.associated');
Route::put('/memberships/member-list', [MembershipController::class, 'updateMemberList'])->name('memberships.memberList.update');
Route::put('/memberships/cancellation', [MembershipController::class, 'cancellation'])->name('memberships.cancellation');
// Note: Call the route memberships.applicants.edit instead of memberships.applicants.checkout to fit the item-list component logic.
Route::get('/memberships/applicants/{membership}/checkout', [MembershipController::class, 'checkoutApplicant'])->name('memberships.applicants.edit');
Route::put('/memberships/applicants/{membership}', [MembershipController::class, 'vote'])->name('memberships.applicants.vote');
Route::put('/memberships', [MembershipController::class, 'update'])->name('memberships.update');
Route::post('/memberships', [MembershipController::class, 'store'])->name('memberships.store');
Route::post('/memberships/items', [MembershipController::class, 'createItem'])->name('memberships.items.create');
Route::delete('/memberships/items/{id}', [MembershipController::class, 'deleteItem'])->name('memberships.items.delete');
route::post('/memberships/payment', [MembershipController::class, 'payment'])->name('memberships.payment');

Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/profile/token', [TokenController::class, 'update'])->name('profile.token');

Route::get('/cms/filemanager', [FileManagerController::class, 'index'])->name('cms.filemanager.index');
Route::post('/cms/filemanager', [FileManagerController::class, 'upload']);
Route::delete('/cms/filemanager', [FileManagerController::class, 'destroy'])->name('cms.filemanager.destroy');

Route::get('/expired', function () {
    return view('cms.filemanager.expired');
})->name('expired');

Route::middleware(['guest'])->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
});

Route::prefix('admin')->group(function () {

    Route::middleware(['admin'])->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('admin');

        // Files
        Route::get('/files', [FileController::class, 'index'])->name('admin.files.index');
        Route::delete('/files', [FileController::class, 'massDestroy'])->name('admin.files.massDestroy');
        Route::get('/files/batch', [FileController::class, 'batch'])->name('admin.files.batch');
        Route::put('/files/batch', [FileController::class, 'massUpdate'])->name('admin.files.massUpdate');

        Route::group([], __DIR__.'/admin/users.php');
        Route::group([], __DIR__.'/admin/memberships.php');
        Route::group([], __DIR__.'/admin/posts.php');
        Route::group([], __DIR__.'/admin/menus.php');
        Route::group([], __DIR__.'/admin/cms.php');
    });
});

Route::get('/autocomplete', [SearchController::class, 'autocomplete'])->name('autocomplete');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/{pages?}', [SiteController::class, 'index'])->name('site.index');
Route::get('/{pages}/{id}/{slug}', [SiteController::class, 'show'])->name('site.show');

