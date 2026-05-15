<?php

use App\Http\Controllers\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\NewslatterController;

// ==========================================
// ROUTES LIBRES (Public & User Type 1)
// ==========================================
Route::get('/', HomeController::class . '@index')->name('home');
Route::get('/about', HomeController::class . '@about')->name('about');
Route::get('/contact', HomeController::class . '@contact')->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/books/{slug}', [HomeController::class, 'show_art'])->name('books.show');

Route::post('/newsletter/subscribe', [NewslatterController::class, 'store'])
     ->name('newsletter.subscribe');

Route::get('/books/{book}/download', [DownloadController::class, 'download'])->name('books.download');

// Redirige l'utilisateur vers Google ou Facebook
Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');

// ==========================================
// ROUTES ADMINISTRATION (Type 1, 2 et 3)
// ==========================================
// On protège ce groupe avec 'auth' ET notre nouveau middleware 'admin'

Route::middleware(['auth'])->group(function () {

    // pour le payement de livre
    Route::post('/webhook/notchpay', [WebhookController::class, 'handleNotchPay'])->name('webhook.notchpay');

    Route::middleware(['admin'])->group(function () {

        Route::get('/admin', Dashboard::class . '@index')->name('admin.dashboard');
        Route::view('/admin/profile', 'profile')->name('admin.profile');

        // Livres
        Route::get('/admin/books', [BookController::class, 'index_admin'])->name('admin.books.index');
        Route::get('/admin/books/create', [BookController::class, 'create'])->name('admin.books.create');
        Route::post('/admin/books/store', [BookController::class, 'store'])->name('admin.books.store');
        Route::get('/admin/books/{book}/edit', [BookController::class, 'edit'])->name('admin.books.edit');
        Route::put('/admin/books/{book}', [BookController::class, 'update'])->name('admin.books.update');
        Route::delete('/admin/books/{book}', [BookController::class, 'destroy'])->name('admin.books.destroy');

        // Catégories
        Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
        Route::get('/admin/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
        Route::post('/admin/categories/store', [CategoryController::class, 'store'])->name('admin.categories.store');
        Route::get('/admin/categories/{category}', [CategoryController::class, 'show'])->name('admin.categories.show');
        Route::get('/admin/categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
        Route::put('/admin/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

        // Messages de contact
        Route::get('/admin/contacts', [ContactController::class, 'index'])->name('admin.contacts.index');
        Route::get('/admin/contacts/{contact}', [ContactController::class, 'show'])->name('admin.contacts.show');
        Route::delete('/admin/contacts/{contact}', [ContactController::class, 'destroy'])->name('admin.contacts.destroy');

        // Gestion de la newsletter
        Route::get('/admin/newsletter', [NewslatterController::class, 'index'])->name('admin.newsletter.index');
        Route::delete('/admin/newsletter/{newslatter}', [NewslatterController::class, 'destroy'])->name('admin.newsletter.destroy');
        Route::get('/admin/newsletter/export', [NewslatterController::class, 'export'])->name('admin.newsletter.export');

        // Gestion des téléchargements
        Route::get('/admin/downloads', [DownloadController::class, 'index'])->name('admin.downloads.index');

        // ==========================================
        // ROUTES SUPER ADMIN (Type 3 Uniquement)
        // ==========================================
        Route::middleware(['superadmin'])->group(function () {

            // Paramètres du site
            Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
            Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

            // Gestion des utilisateurs
            Route::resource('/admin/users', UserController::class)->names('admin.users')->except(['show']);

        });

    });

});

require __DIR__.'/auth.php';
