<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleNotchPay(Request $request)
    {
        // 1. Validation de la signature NotchPay (si configurée dans l'environnement)
        $hashKey = config('services.notchpay.hash_key');
        if ($hashKey) {
            $signature = $request->header('x-notch-signature');
            $computed = hash_hmac('sha256', $request->getContent(), $hashKey);
            if (!$signature || !hash_equals($computed, $signature)) {
                Log::warning("⚠️ Tentative d'accès suspecte : Signature NotchPay invalide.");
                return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 400);
            }
        } else {
            Log::warning("⚠️ Signature NotchPay non configurée. En production, vous devez renseigner NOTCHPAY_HASH_KEY dans votre fichier .env.");
        }

        // 2. Récupérer toutes les données envoyées par NotchPay
        $payload = $request->all();

        // 3. On vérifie que c'est bien un événement de paiement réussi
        if (isset($payload['event']) && $payload['event'] === 'payment.complete') {

            $data = $payload['data'];

            // 4. Retrouver la commande grâce à la référence (ex: CMD-A1B2C3D4E5)
            $order = Order::where('reference', $data['reference'])->first();

            // 5. Si la commande existe et n'est pas encore payée
            if ($order && $order->status !== 'paid') {

                // On la marque comme payée
                $order->update(['status' => 'paid']);

                // On garde une trace dans la table payments
                Payment::create([
                    'order_id'       => $order->id,
                    'aggregator'     => 'notchpay',
                    'transaction_id' => $data['id'] ?? 'unknown',
                    'amount'         => $data['amount'],
                    'status'         => 'successful',
                ]);

                // Ici, tu pourrais déclencher l'envoi de l'email avec le PDF !
                Log::info("Commande {$order->reference} validée avec succès !");
            }
        }

        // 6. Toujours répondre un code 200 à NotchPay pour dire "Message bien reçu"
        return response()->json(['status' => 'success']);
    }
}
