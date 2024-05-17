<?php

namespace App\Http\Controllers;

use App\Http\Resources\CupCakeResource;
use App\Models\CupCake;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function Pest\Laravel\json;

class OtherCupcakeController extends Controller
{
    //Définition des règles de validation
    public $rules = [
        'title' => 'required|string|max:255',
        'imageSource' => 'required|string|max:255',
        'quantity' => 'required|numeric',
        'isAvailable' => 'required|boolean',
        'isAdvertised' => 'required|boolean',
        'price' => 'required|numeric',
    ];

    //Message d'erreur personnalisés
    public $messages = [
        'required' => 'The :attribute field is required.',
        'string' => 'The :attribute field must be a string.',
        'size' => 'The :attribute must be exactly :size.',
        'numeric' => 'The :attribute field must be a numeric.',
        'boolean' => 'The :attribute field must be a boolean.'

    ];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CupCakeResource::collection(CupCake::all());
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

        //Si la validation a réussi, créer le cupcake
        $cupCake = CupCake::create([
            'name' => $validated['title'],
            'image' => $validated['imageSource'],
            'quantity' => $validated['quantity'],
            'is_available' => $validated['isAvailable'],
            'is_advertised' => $validated['isAdvertised'],
            'price' => $validated['price'],
        ]);

        return CupCakeResource::make($cupCake);
    }

    /**
     * Display the specified resource.
     */
    public function show(CupCake $cupCake)
    {
        return CupCakeResource::make($cupCake);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CupCake $cupCake)
    {
        //Validation des données
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        //Vérifier si la validation a échoué
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Code d'erreur HTTP 422 pour données non valides
        }

        $validated = $validator->validated();

        //Sinon, mettre à jour les informations du cupcake
        $cupCake->update([
            'name' => $validated['title'],
            'image' => $validated['imageSource'],
            'quantity' => $validated['quantity'],
            'is_available' => $validated['isAvailable'],
            'is_advertised' => $validated['isAdvertised'],
            'price' => $validated['price'],
        ]);

        return CupCakeResource::make($cupCake);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CupCake $cupCake)
    {
        $cupCake->delete();
    }
}
