<?php

namespace App\Livewire\Front;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Book;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

// On indique qu'on utilise le layout des visiteurs
#[Layout('components.layouts.front')]
class Storefront extends Component
{
    public $books = [];

    public function mount()
    {
        $this->books = Book::where('is_published', true)->get();
    }

    public function buy(int $bookId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $book = Book::findOrFail($bookId);

        $reference = 'CMD-' . strtoupper(Str::random(10));

        $order = Order::create([
            'user_id' => $user->id,
            'reference' => $reference,
            'total_amount' => $book->price,
            'status' => 'pending',
        ]);

        $order->items()->create([
            'book_id' => $book->id,
            'price' => $book->price,
        ]);

        $response = Http::withHeaders([
            'Authorization' => config('services.notchpay.public_key'),
            'Accept'        => 'application/json',
        ])->post('https://api.notchpay.co/payments/initialize', [
            'amount'      => $book->price,
            'currency'    => 'XAF',
            'reference'   => $reference,
            'description' => 'Achat du livre : ' . $book->title,
            'customer'    => [
                'email' => $user->email,
                'name'  => $user->name,
            ],
        ]);

        if ($response->successful() && isset($response['authorization_url'])) {
            return redirect()->away($response['authorization_url']);
        }

        session()->flash('error', 'Impossible d\'initialiser le paiement avec NotchPay.');
    }

    public function render()
    {
        return view('livewire.front.storefront');
    }
}
