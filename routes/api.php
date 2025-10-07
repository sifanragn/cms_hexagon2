<?php

use App\Http\Controllers\Api\ApiAboutTeamController;
use App\Http\Controllers\Api\ApiCareerController;
use App\Http\Controllers\Api\ApiCategoryController;
use App\Http\Controllers\Api\ApiClientsController;
use App\Http\Controllers\Api\ApiCompanyController;
use App\Http\Controllers\Api\ApiContactController;
use App\Http\Controllers\Api\ApiGalleryController;
use App\Http\Controllers\Api\ApiJobController;
use App\Http\Controllers\Api\ApiMainController;
use App\Http\Controllers\Api\ApiMessagesController;
use App\Http\Controllers\Api\ApiNewsController;
use App\Http\Controllers\Api\ApiPortofolioController;
use App\Http\Controllers\Api\ApiProfileSettingController;
use App\Http\Controllers\Api\ApiReviewController;
use App\Http\Controllers\Api\ApiServiceController;
use App\Http\Controllers\Api\ApiValueController;
use App\Http\Controllers\Api\ApiVisionMissionController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\CompanyProfileController;
use App\Http\Controllers\ContactController;
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
use App\Http\Controllers\AboutTeamController;
use App\Http\Controllers\Api\ApiDetailPricingController;
use App\Http\Controllers\Api\ApiPricingController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\MessagesController;
use Illuminate\Support\Facades\Route;

// ======================= API PUBLIC ==========================
Route::get('/teams', [ApiAboutTeamController::class, 'index']);
Route::get('/teams/{id}', [ApiAboutTeamController::class, 'show']);
Route::get('/careers', [ApiCareerController::class, 'index']);
Route::get('/careers/{id}', [ApiCareerController::class, 'show']);
Route::get('/clients', [ApiClientsController::class, 'index']);
Route::get('/clients/{id}', [ApiClientsController::class, 'show']);
Route::get('/company-profiles', [ApiCompanyController::class, 'index']);
Route::get('/company-profiles/{id}', [ApiCompanyController::class, 'show']);
Route::get('/galleries', [ApiGalleryController::class, 'index']);
Route::get('/galleries/{id}', [ApiGalleryController::class, 'show']);
Route::get('/main', [ApiMainController::class, 'index']);
Route::get('/main/{id}', [ApiMainController::class, 'show']);
Route::get('/messages', [ApiMessagesController::class, 'index']);
Route::get('/messages/{id}', [ApiMessagesController::class, 'show']);
Route::get('/news', [ApiNewsController::class, 'index']);
Route::get('/news/{id}', [ApiNewsController::class, 'show']);
Route::get('/portfolios', [ApiPortofolioController::class, 'index']);
Route::get('/portfolios/{id}', [ApiPortofolioController::class, 'show']);
Route::get('/profile-setting', [ApiProfileSettingController::class, 'index']);
Route::get('/reviews', [ApiReviewController::class, 'index']);
Route::get('/reviews/{id}', [ApiReviewController::class, 'show']);
Route::get('/service', [ApiServiceController::class, 'index']);
Route::get('/values', [ApiValueController::class, 'index']);
Route::get('/values/{id}', [ApiValueController::class, 'show']);
Route::get('/vision-mission', [ApiVisionMissionController::class, 'index']);
Route::get('/pricings', [ApiPricingController::class, 'index']);
Route::get('/pricings/{id}', [ApiPricingController::class, 'show']);
Route::get('/detail-pricings', [ApiDetailPricingController::class, 'index']);
Route::get('/detail-pricings/{id}', [ApiDetailPricingController::class, 'show']);
Route::get('/detail-pricings/type/{type}', [ApiDetailPricingController::class, 'getByType']);

// ======================= API CRUD (tanpa sanctum) ==========================
Route::prefix('profile')->group(function () {
    Route::patch('/', [ProfileSettingController::class, 'update'])->name('profile.update');
});

// Profile Setting
Route::prefix('profile-setting')->group(function () {
    Route::post('/', [CompanyProfileController::class, 'store'])->name('company-profile.store');
    Route::put('/{id}', [CompanyProfileController::class, 'update'])->name('company-profile.update');
    Route::delete('/{id}', [CompanyProfileController::class, 'destroy'])->name('company-profile.destroy');
});

// Contact
Route::prefix('contact')->group(function () {
    Route::post('/update', [ContactController::class, 'update'])->name('contact.update');
});

// Social Accounts
Route::prefix('social-account')->group(function () {
    Route::put('/{socialAccount}/update', [SosmedController::class, 'update'])->name('social.update');
});

// Career
Route::prefix('career')->group(function () {
    Route::post('/career', [CareerController::class, 'store'])->name('career.store');
    Route::put('/{id}', [CareerController::class, 'update'])->name('career.update');
    Route::delete('/{id}', [CareerController::class, 'destroy'])->name('career.destroy');
});

// News
Route::prefix('news')->group(function () {
    Route::post('/', [NewsController::class, 'store'])->name('news.store');
    Route::put('/{id}', [NewsController::class, 'update'])->name('news.update');
    Route::delete('/{id}', [NewsController::class, 'destroy'])->name('news.destroy');
});

// About
Route::prefix('about')->group(function () {
    // Clients
    Route::prefix('clients')->group(function () {
        Route::post('/', [ClientsController::class, 'store'])->name('clients.store');
        Route::put('/{client}', [ClientsController::class, 'update'])->name('clients.update');
        Route::delete('/{client}', [ClientsController::class, 'destroy'])->name('clients.destroy');
    });

    // Teams
    Route::prefix('teams')->group(function () {
        Route::post('/', [AboutTeamController::class, 'store'])->name('teams.store');
        Route::put('/{id}', [AboutTeamController::class, 'update'])->name('teams.update');
        Route::delete('/{id}', [AboutTeamController::class, 'destroy'])->name('teams.destroy');
    });

    // Main
    Route::prefix('main')->group(function () {
        Route::post('/', [MainController::class, 'update'])->name('main.update');
    });

    // Vision Mission
    Route::prefix('vision-mission')->group(function () {
        Route::post('/update', [VisionMissionController::class, 'update'])->name('vision-mission.update');
    });

    // Values
    Route::prefix('values')->group(function () {
        Route::post('/update-multiple', [ValuesController::class, 'updateMultiple'])->name('about.values.updateMultiple');
        Route::post('/store', [ValuesController::class, 'store'])->name('about.values.store');
        Route::delete('/delete/{id}', [ValuesController::class, 'destroy'])->name('about.values.delete');
    });

    // Gallery
    Route::prefix('gallery')->group(function () {
        Route::post('/store', [GalleryController::class, 'store'])->name('about.gallery.store');
        Route::put('/update/{id}', [GalleryController::class, 'update'])->name('about.gallery.update');
        Route::delete('/delete/{id}', [GalleryController::class, 'destroy'])->name('about.gallery.delete');
    });
});

// Portofolio
Route::prefix('portofolio')->group(function () {
    Route::post('/store', [PortofolioController::class, 'store'])->name('portofolio.store');
    Route::put('/update/{id}', [PortofolioController::class, 'update'])->name('portofolio.update');
    Route::delete('/delete/{id}', [PortofolioController::class, 'destroy'])->name('portofolio.delete');
    Route::delete('/delete-photo/{id}', [PortofolioController::class, 'deletePhoto'])->name('portofolio.deletePhoto');
});

// Category
Route::prefix('category')->group(function () {
    Route::post('/store', [CategoryController::class, 'store'])->name('category.store');
    Route::post('/update/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/delete/{id}', [CategoryController::class, 'destroy'])->name('category.delete');
});

// Reviews
Route::prefix('reviews')->group(function () {
    Route::post('/store', [ReviewController::class, 'store'])->name('review.store');
    Route::post('/update/{id}', [ReviewController::class, 'update'])->name('review.update');
    Route::delete('/delete/{id}', [ReviewController::class, 'destroy'])->name('review.destroy');
});

// Services
Route::prefix('services')->group(function () {
    Route::post('/', [ServiceController::class, 'store'])->name('services.store');
    Route::delete('/{id}', [ServiceController::class, 'destroy'])->name('services.destroy');
});

// Messages
Route::prefix('message')->group(function () {
    Route::post('/send', [MessagesController::class, 'store'])->name('message.store');
});
