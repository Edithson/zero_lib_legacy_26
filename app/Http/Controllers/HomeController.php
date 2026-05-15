<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(Request $request): View
    {
        // 1. Récupération des paramètres de l'URL
        $search = $request->input('search');
        $sortBy = $request->input('sort', 'recent');
        $categorySlug = $request->input('category');

        // 2. Préparation de la requête de base (livres publiés)
        // On inclut 'downloads' en count pour le tri par popularité
        $query = Book::where('is_published', true)
                     ->with('category')
                     ->withCount('downloads');

        // 3. Application du filtre de recherche
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // 4. Application du filtre de catégorie
        if ($categorySlug) {
            $query->whereHas('category', function($q) use ($categorySlug) {
                $q->where('slug', $categorySlug);
            });
        }

        // 5. Application du tri
        match ($sortBy) {
            'alpha'   => $query->orderBy('title', 'asc'),
            'popular' => $query->orderByDesc('downloads_count'),
            default   => $query->latest(), // 'recent'
        };

        // 6. Pagination avec conservation des paramètres d'URL
        $books = $query->paginate(15)->withQueryString()->fragment('catalogue');

        // On récupère les catégories ayant au moins un livre pour afficher les filtres
        $categories = Category::has('books')->orderBy('name')->get();

        // On reccupère le nombre total de livres et de catégories pour l'afficher dans le titre
        $totalBooks = $query->count();
        $totalCategories = $categories->count();

        return view('custom.pages.home', [
            'books'        => $books,
            'categories'   => $categories,
            'currentSearch'=> $search,
            'currentSort'  => $sortBy,
            'currentCat'   => $categorySlug,
            'totalBooks'   => $totalBooks,
            'totalCategories' => $totalCategories,
        ]);
    }

    public function about(): View
    {
        return view('custom.pages.about.about');
    }

    public function contact(): View
    {
        return view('custom.pages.contact.contact');
    }

    public function show_art($slug): View
    {
        try {
            $book = Book::where('slug', $slug)->firstOrFail();
            return view('custom.pages.articles.show', ['book' => $book]);
        } catch (\Throwable $th) {
            abort(404);
        }
    }
}
