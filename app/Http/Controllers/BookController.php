<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index_admin(Request $request)
    {
        $books = Book::with('category')
            ->when($request->search, fn($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->when($request->category, fn($q) => $q->where('category_id', $request->category))
            ->paginate(15);

        return view('admin.pages.books.index', [
            'books'      => $books,
            'categories' => Category::all(),
            'pageTitle'  => 'Gestion des livres',
            'totalBooks' => Book::count(),
        ]);
    }

    public function create(): View
    {
        return view('admin.pages.books.create', [
            'pageTitle'  => 'Ajouter un livre',
            'categories' => Category::orderBy('name')->get(),
            'totalBooks' => Book::count(),
        ]);
    }

    // -------------------------------------------------------
    // POST /admin/books
    // -------------------------------------------------------
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'author'       => ['nullable', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'price'        => ['nullable', 'integer', 'min:0'],
            'category_id'  => ['nullable', 'exists:categories,id'],
            'nbr_pages'    => ['nullable', 'integer', 'min:1'],
            'publish_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'is_published' => ['boolean'],
            'cover'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'file'         => ['nullable', 'file', 'mimes:pdf', 'max:51200'], // 50 Mo
        ]);

        // --- Couverture (optimisée et stockée publiquement) ---
        $coverPath = null;
        if ($request->hasFile('cover')) {
            $coverPath = $this->optimizeAndStoreCover($request->file('cover'));
        }

        // --- Fichier PDF (stocké dans le disque privé) ---
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('books', 'private');
        }

        Book::create([
            'title'        => $validated['title'],
            'author'       => $validated['author'] ?? null,
            'description'  => $validated['description'] ?? null,
            'price'        => $validated['price'] ?? 0,
            'category_id'  => $validated['category_id'] ?? null,
            'nbr_pages'    => $validated['nbr_pages'] ?? 10,
            'publish_year' => $validated['publish_year'] ?? date('Y'),
            'is_published' => $request->boolean('is_published', true),
            'cover_path'   => $coverPath,
            'file_path'    => $filePath,
            // Le slug est généré automatiquement par le modèle (booted)
        ]);

        return redirect()
            ->route('admin.books.index')
            ->with('success', '« ' . $validated['title'] . ' » a été publié avec succès.');
    }

    // méthodes d'édition
    public function edit(Book $book): View
    {
        return view('admin.pages.books.edit', [
            'book'       => $book,
            'categories' => Category::orderBy('name')->get(),
            'pageTitle'  => 'Modifier le livre',
            'totalBooks' => Book::count(),
        ]);
    }

    // méthodes de mise à jour
    public function update(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'author'       => ['nullable', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'price'        => ['nullable', 'integer', 'min:0'],
            'category_id'  => ['nullable', 'exists:categories,id'],
            'nbr_pages'    => ['nullable', 'integer', 'min:1'],
            'publish_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'is_published' => ['boolean'],
            'cover'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'file'         => ['nullable', 'file', 'mimes:pdf', 'max:51200'], // 50 Mo
        ]);
        try {
            // --- Couverture (optimisée et stockée publiquement) ---
            if ($request->hasFile('cover')) {
                // Supprimer l'ancienne couverture si elle existe
                if ($book->cover_path) {
                    Storage::disk('public')->delete($book->cover_path);
                }
                $book->cover_path = $this->optimizeAndStoreCover($request->file('cover'));
            }

            // --- Fichier PDF (stocké dans le disque privé) ---
            if ($request->hasFile('file')) {
                // Supprimer l'ancien fichier si il existe
                if ($book->file_path) {
                    Storage::disk('private')->delete($book->file_path);
                }
                $book->file_path = $request->file('file')->store('books', 'private');
            }

            $book->update([
                'title'        => $validated['title'],
                'author'       => $validated['author'] ?? null,
                'description'  => $validated['description'] ?? null,
                'price'        => $validated['price'] ?? 0,
                'category_id'  => $validated['category_id'] ?? null,
                'nbr_pages'    => $validated['nbr_pages'] ?? 10,
                'publish_year' => $validated['publish_year'] ?? date('Y'),
                'is_published' => $request->boolean('is_published', true),
                // Le slug est régénéré automatiquement par le modèle (booted)
            ]);

            return redirect()
                ->route('admin.books.index')
                ->with('success', '« ' . $validated['title'] . ' » a été mis à jour avec succès.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la mise à jour du livre.']);
        }
    }

    // méthode de suppression
    public function destroy(Book $book): RedirectResponse
    {
        try {
            // Supprimer les fichiers associés
            if ($book->cover_path) {
                Storage::disk('public')->delete($book->cover_path);
            }
            if ($book->file_path) {
                Storage::disk('private')->delete($book->file_path);
            }

            $book->delete();

            return redirect()
                ->route('admin.books.index')
                ->with('success', '« ' . $book->title . ' » a été supprimé avec succès.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Une erreur est survenue lors de la suppression du livre.']);
        }
    }

    /**
     * Optimise l'image de couverture, la redimensionne et la convertit au format WebP.
     */
    private function optimizeAndStoreCover($file): ?string
    {
        if (!$file) {
            return null;
        }

        try {
            $path = $file->getRealPath();
            $mime = $file->getMimeType();

            switch ($mime) {
                case 'image/jpeg':
                case 'image/jpg':
                    $image = imagecreatefromjpeg($path);
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($path);
                    break;
                case 'image/webp':
                    $image = imagecreatefromwebp($path);
                    break;
                case 'image/avif':
                    $image = imagecreatefromavif($path);
                    break;
                default:
                    return $file->store('covers', 'public');
            }

            if (!$image) {
                return $file->store('covers', 'public');
            }

            $origWidth = imagesx($image);
            $origHeight = imagesy($image);

            // Largeur max 400px, hauteur proportionnelle
            $targetWidth = 400;
            if ($origWidth > $targetWidth) {
                $targetHeight = (int) (($origHeight / $origWidth) * $targetWidth);
            } else {
                $targetWidth = $origWidth;
                $targetHeight = $origHeight;
            }

            $resizedImage = imagecreatetruecolor($targetWidth, $targetHeight);

            // Gestion de la transparence pour WebP
            imagealphablending($resizedImage, false);
            imagesavealpha($resizedImage, true);

            imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $targetWidth, $targetHeight, $origWidth, $origHeight);

            ob_start();
            imagewebp($resizedImage, null, 80);
            $webpContent = ob_get_clean();

            imagedestroy($image);
            imagedestroy($resizedImage);

            $filename = 'covers/' . \Illuminate\Support\Str::random(40) . '.webp';
            Storage::disk('public')->put($filename, $webpContent);

            return $filename;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Erreur lors de l\'optimisation de l\'image de couverture : ' . $e->getMessage());
            return $file->store('covers', 'public');
        }
    }
}
