<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsappService
{
    protected string $token;
    protected string $phoneNumberId;

    public function __construct()
    {
        $this->token = env('WHATSAPP_TOKEN');
        $this->phoneNumberId = env('WHATSAPP_PHONE_ID');
    }

    public function sendOrder(string $to, array $cart, float $total, string $imageUrl): array
    {
        $lines = [];
        foreach ($cart as $line) {
            $lineTotal = number_format($line['price'] * $line['qty'], 2);
            $lines[] = "• {$line['name']} x {$line['qty']} = \${$lineTotal}";
        }

        $caption = "🛒 Order Details:\n\n" .
            implode("\n", $lines) .
            "\n\n📊 Total: $" . number_format($total, 2) . "\n\nThank you 🙏";

        $response = Http::withToken($this->token)
            ->post("https://graph.facebook.com/v21.0/{$this->phoneNumberId}/messages", [
                "messaging_product" => "whatsapp",
                "to" => $to,
                "type" => "image",
                "image" => [
                    "link" => $imageUrl,
                    "caption" => $caption,
                ]
            ]);

        return $response->json();
    }
}
