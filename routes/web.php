<?php

use App\Http\Controllers\AboutTeamController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\CostumRegisterController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DetailPricingController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\SosmedController;
use App\Http\Controllers\ProfileSettingController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\PortofolioController;
use App\Http\Controllers\ValuesController;
use App\Http\Controllers\VisionMissionController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\PricingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect halaman utama ke login
Route::get('/', fn() => redirect('/login'));

// Registrasi manual (tanpa auto-login)
Route::post('/register', [CostumRegisterController::class, 'store'])->middleware('guest');

// Halaman login (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => view('auth.login'))->name('login');
});

// Halaman utama untuk user yang sudah login & verifikasi email
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');


    // About
    Route::get('/about/main', fn() => view('about.main'))->name('about.main');
    // Route::get('/about/teams', fn() => view('about.teams'))->name('about.teams');



    Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');



    // About Clients
    Route::get('/about/clients', [ClientsController::class, 'index'])->name('about.clients');
    Route::post('/about/clients', [ClientsController::class, 'store'])->name('clients.store');
    Route::put('/about/clients/{client}', [ClientsController::class, 'update'])->name('clients.update');
    Route::delete('/about/clients/{client}', [ClientsController::class, 'destroy'])->name('clients.destroy');


    Route::get('/about/teams', [AboutTeamController::class, 'index'])->name('about.teams');
    Route::post('/about/teams', [AboutTeamController::class, 'store'])->name('teams.store');
    Route::put('/about/teams/{id}', [AboutTeamController::class, 'update'])->name('teams.update');
    Route::delete('/about/teams/{id}', [AboutTeamController::class, 'destroy'])->name('teams.destroy');


    // Profile Setting Page
    Route::get('/profile-setting', [ProfileSettingController::class, 'index'])->name('profile-setting');

    // Company Address

    Route::post('/profile-setting', [CompanyProfileController::class, 'store'])->name('company-profile.store');
    Route::put('/profile-setting/{id}', [CompanyProfileController::class, 'update'])->name('company-profile.update');
    Route::delete('/profile-setting/{id}', [CompanyProfileController::class, 'destroy'])->name('company-profile.destroy');

    // Contact (Phone & Email Update)
    Route::post('/contact/update', [ContactController::class, 'update'])->name('contact.update');

    // Social Accounts (Update Only)
    Route::put('/social-account/{socialAccount}/update', [SosmedController::class, 'update'])->name('social.update');

    // User Profile (Laravel Fortify)
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});


Route::prefix('career')->group(function () {
    Route::get('/', [CareerController::class, 'index'])->name('career.index');
    Route::post('/career', [CareerController::class, 'store'])->name('career.store');
    Route::get('/{id}', [CareerController::class, 'show'])->name('career.show');
    Route::get('/career/{id}/edit', [CareerController::class, 'edit'])->name('career.edit');
    Route::put('/{id}', [CareerController::class, 'update'])->name('career.update');
    Route::delete('/{id}', [CareerController::class, 'destroy'])->name('career.destroy');
});

//Route news
Route::prefix('news')->group(function () {
    Route::get('/', [NewsController::class, 'index'])->name('news.index');
    Route::post('/news', [NewsController::class, 'store'])->name('news.store');
    Route::get('/{id}', [NewsController::class, 'show'])->name('news.show');
    Route::get('/news/{id}/edit', [NewsController::class, 'edit'])->name('news.edit');
    Route::put('/{id}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('/{id}', [NewsController::class, 'destroy'])->name('news.destroy');
});

Route::prefix('about')->group(function () {
    Route::get('/main', [MainController::class, 'index'])->name('about.main');
    Route::post('/main', [MainController::class, 'update'])->name('main.update');

    Route::get('/vision-mission', [VisionMissionController::class, 'index'])->name('about.vision-mission');
    Route::post('/vision-mission/update', [VisionMissionController::class, 'update'])->name('vision-mission.update');

    Route::get('/values', [ValuesController::class, 'index'])->name('about.values');
    Route::post('/values/update-multiple', [ValuesController::class, 'updateMultiple'])->name('about.values.updateMultiple');
    Route::post('/values/store', [ValuesController::class, 'store'])->name('about.values.store');
    Route::delete('/values/delete/{id}', [ValuesController::class, 'destroy'])->name('about.values.delete');

    Route::get('/gallery', [GalleryController::class, 'index'])->name('about.gallery');
    Route::post('/gallery/store', [GalleryController::class, 'store'])->name('about.gallery.store');
    Route::post('/gallery/update/{id}', [GalleryController::class, 'update'])->name('about.gallery.update');
    Route::delete('/gallery/delete/{id}', [GalleryController::class, 'destroy'])->name('about.gallery.delete');
});

Route::prefix('portofolio')->name('portofolio.')->group(function () {
    Route::get('/', [PortofolioController::class, 'index'])->name('index');
    Route::post('/store', [PortofolioController::class, 'store'])->name('store');
    Route::put('/update/{id}', [PortofolioController::class, 'update'])->name('update');
    Route::get('/show/{id}', [PortofolioController::class, 'show'])->name('show');
    Route::delete('/delete/{id}', [PortofolioController::class, 'destroy'])->name('delete');
    Route::delete('/delete-photo/{id}', [PortofolioController::class, 'deletePhoto'])->name('deletePhoto');
});

Route::prefix('category')->name('category.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::post('/store', [CategoryController::class, 'store'])->name('store');
    Route::post('/update/{id}', [CategoryController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [CategoryController::class, 'destroy'])->name('delete');
});

Route::prefix('reviews')->group(function () {
    Route::get('/', [ReviewController::class, 'index'])->name('review.index');
    Route::post('/store', [ReviewController::class, 'store'])->name('review.store');
    Route::post('/update/{id}', [ReviewController::class, 'update'])->name('review.update');
    Route::delete('/delete/{id}', [ReviewController::class, 'destroy'])->name('review.destroy');
});
Route::get('/services', [ServiceController::class, 'index'])->name('services.all');
Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
Route::get('/services/create', [ServiceController::class, 'create'])->name('services.create');
Route::delete('/services/{id}', [ServiceController::class, 'destroy'])->name('services.destroy');


//MESSAGES
Route::get('/message', [MessagesController::class, 'index'])->name('message.index');
Route::post('/message/send', [MessagesController::class, 'store'])->name('message.store');

// routes/web.php

// Pricing Routes
Route::prefix('pricing')->name('pricing.')->group(function () {
    Route::get('/', [PricingController::class, 'index'])->name('index');
    Route::post('/store', [PricingController::class, 'store'])->name('store');
    Route::get('/{id}', [PricingController::class, 'show'])->name('show');
    Route::put('/{id}', [PricingController::class, 'update'])->name('update');
    Route::delete('/{id}', [PricingController::class, 'destroy'])->name('destroy');

});

Route::prefix('detail-pricings')->name('detail-pricings.')->group(function () {
    Route::get('/', [DetailPricingController::class, 'index'])->name('index');
    Route::get('/{type}', [DetailPricingController::class, 'getByType'])->name('type'); 
    Route::post('/', [DetailPricingController::class, 'store'])->name('store');
    Route::put('/{id}', [DetailPricingController::class, 'update'])->name('update');
    Route::delete('/{id}', [DetailPricingController::class, 'destroy'])->name('destroy');
});


