<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->get();
        return view('orders_index', compact('orders'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'total' => 'required|numeric|min:0',
            // Ajoutez d'autres règles de validation si besoin
        ]);

        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $validated['total'],
        ]);

        // Rediriger ou retourner une réponse
        return redirect()->back()->with('success', 'Commande ajoutée avec succès !');
    }
    public function show(Order $order)
    {
        // Utiliser la Gate pour vérifier l'accès
        Gate::authorize('view-order', $order);
        return view('orders_show', compact('order'));
    }
}
