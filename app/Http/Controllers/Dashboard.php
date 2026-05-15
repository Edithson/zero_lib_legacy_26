<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Contact;
use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Dashboard extends Controller
{
    public function index(): View
    {
        // 1. Indicateurs Clés de Performance (KPIs)
        $totalBooks = Book::count();
        $totalDownloads = Download::count();
        $totalUsers = User::count();

        // On suppose que la colonne 'is_read' existe bien d'après ta migration précédente
        $unreadMessages = Contact::where('is_read', false)->count();

        // 2. Activités Récentes (Les 5 derniers téléchargements)
        $recentDownloads = Download::with(['book', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // 3. Messages Récents (Les 5 derniers contacts)
        $recentContacts = Contact::latest()
            ->take(5)
            ->get();

        return view('admin.pages.dashboard', [
            'pageTitle'       => 'Tableau de bord',
            'totalBooks'      => $totalBooks,
            'totalDownloads'  => $totalDownloads,
            'totalUsers'      => $totalUsers,
            'unreadMessages'  => $unreadMessages,
            'recentDownloads' => $recentDownloads,
            'recentContacts'  => $recentContacts,
        ]);
    }
}
