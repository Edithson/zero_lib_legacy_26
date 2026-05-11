<?php

use Livewire\Volt\Volt;
use App\Livewire\Front\Storefront;
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

// Route::view('/', 'welcome');

Route::get('/', HomeController::class . '@index')->name('home');
Route::get('/about', HomeController::class . '@about')->name('about');
Route::get('/contact', HomeController::class . '@contact')->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::post('/newsletter/subscribe', [NewslatterController::class, 'store'])
     ->name('newsletter.subscribe');

// Proteger les routes d'administration avec une middleware d'authentification
Route::middleware(['auth'])->group(function () {

    Route::get('/admin', Dashboard::class . '@index')->name('admin.dashboard');

    // routes pour les livres
    Route::get('/admin/books', [BookController::class, 'index_admin'])->name('admin.books.index');
    Route::get('/admin/books/create', [BookController::class, 'create'])->name('admin.books.create');
    Route::post('/admin/books/store', [BookController::class, 'store'])->name('admin.books.store');
    Route::get('/admin/books/{book}/edit', [BookController::class, 'edit'])->name('admin.books.edit');
    Route::put('/admin/books/{book}', [BookController::class, 'update'])->name('admin.books.update');
    Route::delete('/admin/books/{book}', [BookController::class, 'destroy'])->name('admin.books.destroy');

    // route pour les catégories
    Route::get('/admin/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('/admin/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/admin/categories/store', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/admin/categories/{category}', [CategoryController::class, 'show'])->name('admin.categories.show');
    Route::get('/admin/categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/admin/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/admin/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

    // routes pour les paramètres du site
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');

    // routes pour les messages de contact
    Route::get('/admin/contacts', [ContactController::class, 'index'])->name('admin.contacts.index');
    Route::get('/admin/contacts/{contact}', [ContactController::class, 'show'])->name('admin.contacts.show');
    Route::delete('/admin/contacts/{contact}', [ContactController::class, 'destroy'])->name('admin.contacts.destroy');

    // routes pour la gestion de la newsletter
    Route::get('/admin/newsletter', [NewslatterController::class, 'index'])->name('admin.newsletter.index');
    Route::delete('/admin/newsletter/{newslatter}', [NewslatterController::class, 'destroy'])->name('admin.newsletter.destroy');
    Route::get('/admin/newsletter/export', [NewslatterController::class, 'export'])->name('admin.newsletter.export');

    // route pour la gestion des utilisateurs
    Route::resource('/admin/users', UserController::class)->names('admin.users')->except(['show']);

    // route pour la gestion des téléchargements
    Route::get('/admin/downloads', [DownloadController::class, 'index'])->name('admin.downloads.index');

});

Route::get('/books/{book}/download', [DownloadController::class, 'download'])->name('books.download');

Route::post('/webhook/notchpay', [WebhookController::class, 'handleNotchPay'])->name('webhook.notchpay');

Route::view('/admin/dashboard', 'admin.pages.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('admin.dashboard');

Route::view('/admin/profile', 'profile')
    ->middleware(['auth'])
    ->name('admin.profile');

// Redirige l'utilisateur vers Google ou Facebook
Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');

// Gère le retour de Google ou Facebook après que l'utilisateur ait cliqué sur "Accepter"
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');

require __DIR__.'/auth.php';
