<?php

namespace App\Livewire\Front;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\Book;
use App\Models\Category;

class Catalog extends Component
{
    use WithPagination;

    #[Url(keep: true)]
    public $search = '';

    #[Url(keep: true)]
    public $category = '';

    #[Url(keep: true)]
    public $sort = 'recent';

    // Réinitialiser la pagination lors du changement de filtre
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatingSort()
    {
        $this->resetPage();
    }

    public function updatedPage()
    {
        $this->dispatch('scroll-to-catalog');
    }

    public function selectCategory($slug)
    {
        $this->category = $slug;
        $this->resetPage();
        $this->dispatch('scroll-to-catalog');
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->category = '';
        $this->sort = 'recent';
        $this->resetPage();
        $this->dispatch('scroll-to-catalog');
    }

    public function render()
    {
        // 1. Récupérer la version de cache (pour les catégories)
        $version = \Illuminate\Support\Facades\Cache::rememberForever('catalog_cache_version', fn() => time());

        // Récupérer les catégories actives via le cache
        $categoriesData = \Illuminate\Support\Facades\Cache::remember('catalog_categories_' . $version, now()->addMinutes(60), function () {
            return Category::has('books')->orderBy('name')->get()->toArray();
        });

        $categories = collect(array_map(function ($catData) {
            return (new Category)->forceFill($catData);
        }, $categoriesData));

        // 2. Requête dynamique des livres
        $isDefault = empty($this->search) && empty($this->category) && $this->sort === 'recent';
        $page = $this->getPage();

        if ($isDefault) {
            // Utiliser le cache pour la page par défaut du catalogue
            $cacheKey = 'catalog_' . $version . '_default_p' . $page;
            $booksData = \Illuminate\Support\Facades\Cache::remember($cacheKey, now()->addMinutes(60), function () {
                $query = Book::where('is_published', true)
                             ->with('category')
                             ->withCount('downloads')
                             ->latest();
                $paginator = $query->paginate(15);
                return [
                    'items' => collect($paginator->items())->map(fn($item) => $item->toArray())->toArray(),
                    'total' => $paginator->total(),
                    'perPage' => $paginator->perPage(),
                    'currentPage' => $paginator->currentPage(),
                ];
            });

            $items = array_map(function ($itemData) {
                $book = (new Book)->forceFill(array_filter($itemData, fn($k) => $k !== 'category', ARRAY_FILTER_USE_KEY));
                if (isset($itemData['category']) && $itemData['category']) {
                    $book->setRelation('category', (new Category)->forceFill($itemData['category']));
                }
                return $book;
            }, $booksData['items']);

            $books = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $booksData['total'],
                $booksData['perPage'],
                $booksData['currentPage'],
                [
                    'path' => url('/'),
                    'fragment' => 'catalogue'
                ]
            );
            $books->withQueryString();
        } else {
            // Requête dynamique en temps réel pour la recherche et les filtres
            $query = Book::where('is_published', true)
                         ->with('category')
                         ->withCount('downloads');

            if ($this->search) {
                $query->where(function ($q) {
                    $q->where('title', 'like', "%{$this->search}%")
                      ->orWhere('author', 'like', "%{$this->search}%")
                      ->orWhere('description', 'like', "%{$this->search}%");
                });
            }

            if ($this->category) {
                $query->whereHas('category', function ($q) {
                    $q->where('slug', $this->category);
                });
            }

            match ($this->sort) {
                'alpha'   => $query->orderBy('title', 'asc'),
                'popular' => $query->orderByDesc('downloads_count'),
                default   => $query->latest(),
            };

            $books = $query->paginate(15)->fragment('catalogue');
            $books->withQueryString();
        }

        return view('livewire.front.catalog', [
            'books' => $books,
            'categories' => $categories,
        ]);
    }
}
