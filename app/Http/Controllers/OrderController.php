<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Models\CupCake;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    //Définition des règles de validation
    public $rules = [
        'userId' => 'required|numeric',
        'cupcakes.*.id' => 'required|numeric',
        'cupcakes.*.quantity' => 'required|numeric'
    ];
    //Message d'erreur personnalisés
    public $messages = [
        'required' => 'The :attribute field is required.',
        'numeric' => 'The :attribute field must be a numeric.',
    ];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::with('user', 'cupcakes')->get();

        return OrderResource::collection($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validation des données
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        //Vérifier si la validation a échoué
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Code d'erreur HTTP 422 pour données non valides
        }

        $validated = $validator->validated();

        //Créer la commande
        $order = Order::create(['user_id' => $validated['userId']]);

        //Tableau de cupCakes
        $cupcakes = $validated['cupcakes'];

        if (isset($cupcakes)) {
            foreach ($cupcakes as $cupcake) {
                $order->cupcakes()->attach($cupcake['id'], ['quantity' => $cupcake['quantity'], 'price' => CupCake::find($cupcake['id'])->price]);
            }
        }

        //Récuperer la commande créée
        $newOrder = Order::with('user', 'cupcakes')->find($order->id);

        return OrderResource::make($newOrder);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order = Order::with('user', 'cupcakes')->find($order->id);

        return OrderResource::make($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //Validation des données
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        //Vérifier si la validation a échoué
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Code d'erreur HTTP 422 pour données non valides
        }

        $validated = $validator->validated();

        // Mise à jour de l'objet principal (Order)
        $order->update(['user_id' => $validated['userId']]);

        $cupcakes = $validated['cupcakes'];
        // Mise à jour des relations many-to-many (cupcakes)
        if (isset($cupcakes)) {
            // Supprimer les cupcakes existants associés à la commande
            $order->cupcakes()->detach();

            // Ajouter les nouveaux cupcakes associés à la commande
            foreach ($validated['cupcakes'] as $cupcake) {
                $order->cupcakes()->attach($cupcake['id'], ['quantity' => $cupcake['quantity'], 'price' => CupCake::find($cupcake['id'])->price]);
            }
        }

        //Récuperer la commande créée
        $newOrder = Order::with('user', 'cupcakes')->find($order->id);

        return OrderResource::make($newOrder);

        //return $order;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        // Supprimer les cupcakes associés à la commande
        $order->cupcakes()->detach();

        // Supprimer la commande elle-même
        $order->delete();

        // Retourner une réponse appropriée
        return response()->json(['message' => 'Order deleted successfully']);
    }
}
