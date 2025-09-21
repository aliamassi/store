<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuController extends Controller
{
    // Simple in-memory catalog (you can swap to DB later)
    private array $catalog = [
        'hot-drinks' => [
            ['id' => 'hd-1', 'name' => 'Espresso', 'price' => 2.50, 'image' => '/images/products/espresso.jpeg'],
            ['id' => 'hd-2', 'name' => 'Cappuccino', 'price' => 3.25, 'image' => '/images/products/cappuccino.jpeg'],
            ['id' => 'hd-3', 'name' => 'Latte', 'price' => 3.50, 'image' => '/images/products/latte.jpg'],
            ['id' => 'hd-4', 'name' => 'Tea', 'price' => 1.80, 'image' => '/images/products/tea.jpeg'],
        ],
        'waffles' => [
            ['id' => 'wf-1', 'name' => 'Classic Waffle', 'price' => 4.50, 'image' => '/images/products/waffle-classic.jpeg'],
            ['id' => 'wf-2', 'name' => 'Chocolate Chip', 'price' => 4.90, 'image' => '/images/products/waffle-choco.jpeg'],
            ['id' => 'wf-3', 'name' => 'Strawberry', 'price' => 5.20, 'image' => '/images/products/waffle-strawberry.jpeg'],
        ],
        'ice-cream' => [
            ['id' => 'ic-1', 'name' => 'Vanilla Scoop', 'price' => 2.20, 'image' => '/images/products/vanilla-ice-cream.jpeg'],
            ['id' => 'ic-2', 'name' => 'Chocolate Scoop', 'price' => 2.20, 'image' => '/images/products/chocolate-ice-cream.jpeg'],
            ['id' => 'ic-3', 'name' => 'Mint Scoop', 'price' => 2.40, 'image' => '/images/products/mint-ice-cream.jpeg'],
        ],
        'juices' => [
            ['id' => 'js-1', 'name' => 'Orange Juice', 'price' => 2.80, 'image' => '/images/products/orange.jpeg'],
            ['id' => 'js-2', 'name' => 'Apple Juice', 'price' => 2.60, 'image' => '/images/products/apple.jpeg'],
            ['id' => 'js-3', 'name' => 'Mango Juice', 'price' => 3.00, 'image' => '/images/products/mango.jpeg'],
        ],
    ];

    public function index(Request $request)
    {
        $cart = $this->getCart();
        $total = $this->cartTotal($cart);

        $waLink = $this->buildWhatsappLink($cart, $total);

        return view('menu', [
            'catalog' => $this->catalog,
            'cart' => $cart,
            'cartTotal' => $total,
            'waLink' => $waLink,
        ]);
    }

    public function Cart(Request $request)
    {
        $cart = $this->getCart();
        $total = $this->cartTotal($cart);

        $waLink = $this->buildWhatsappLink($cart, $total);

        return view('cart', [
            'catalog' => $this->catalog,
            'cart' => $cart,
            'cartTotal' => $total,
            'waLink' => $waLink,
        ]);
    }

    private function buildWhatsappLink(array $cart, float $total): string
    {
        $phone = env('WHATSAPP_PHONE'); // example: 97259XXXXXXX

        $lines = [];
        $lines[] = "Hello! I'd like to order:";
        foreach ($cart as $line) {
            $lineTotal = number_format($line['price'] * $line['qty'], 2);
            $lines[] = "- {$line['name']} x {$line['qty']} = \${$lineTotal}";
        }
        $lines[] = "Total: $" . number_format($total, 2);

        $text = implode("\n", $lines);

        if ($phone) {
            // requires correct phone format
            return "https://wa.me/{$phone}?text=" . rawurlencode($text);
        }

        // fallback if no phone configured
        return "https://api.whatsapp.com/send?text=" . rawurlencode($text);
    }


    public function add(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|string',
        ]);

        $product = $this->findProductById($data['id']);
        if (!$product) {
            return back()->with('error', 'Product not found.');
        }

        $cart = $this->getCart();

        if (!isset($cart[$product['id']])) {
            $cart[$product['id']] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'qty' => 0,
            ];
        }
        $cart[$product['id']]['qty']++;
        $total = $this->cartTotal($cart);
        session(['cart' => $cart]);
        return back()->with([
            'success' => $product['name'] . ' added to cart.',
            'showCartBar' => true,
            'total' => "$$total",
            'count' => array_sum(array_map(fn($i) => $i['qty'], $cart)),
        ]);
    }

    public function remove(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|string',
        ]);

        $cart = $this->getCart();
        if (isset($cart[$data['id']])) {
            $cart[$data['id']]['qty']--;
            if ($cart[$data['id']]['qty'] <= 0) {
                unset($cart[$data['id']]);
            }
            session(['cart' => $cart]);
        }
        return response()->json([
            'status' => true,
            'qty' => $cart[$data['id']]['qty'],
            'message' => 'Successfully removed'
        ]);
    }

    public function removeLine(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|string',
        ]);

        $cart = $this->getCart();
        if (isset($cart[$data['id']])) {
            unset($cart[$data['id']]);
            session(['cart' => $cart]);
        }
        return response()->json([
            'status' => true,
            'message' => 'Successfully removed'
        ]);
    }

    public function clear()
    {
        session()->forget('cart');
        return back();
    }

    // ---------- helpers ----------
    private function getCart(): array
    {
        return session('cart', []);
    }

    private function cartTotal(array $cart): float
    {
        return array_reduce($cart, fn($c, $item) => $c + ($item['price'] * $item['qty']), 0.0);
    }

    private function findProductById(string $id): ?array
    {
        foreach ($this->catalog as $cat => $items) {
            foreach ($items as $p) {
                if ($p['id'] === $id) return $p;
            }
        }
        return null;
    }
}
