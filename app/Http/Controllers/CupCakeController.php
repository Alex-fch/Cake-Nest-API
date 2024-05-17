<?php

namespace App\Http\Controllers;

use App\Http\Resources\CupCakeResource;
use App\Models\CupCake;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CupCakeController extends Controller
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

    public function AllCupCake()
    {
        return CupCakeResource::collection(CupCake::all());
    }

    public function getCupCake($id)
    {
        //Récupérer le cupcake en BDD
        $cupCake = CupCake::find($id);

        //Si le cupcake n'éxiste pas, renvoyer un message d'érreur
        if ($cupCake === null) {
            return response()->json(['message' => 'CupCake not found'], 404);
        }

        return CupCakeResource::make($cupCake);
    }

    public function createCupCake(Request $request)
    {
        //Validation des données
        $validator = Validator::make($request->all(), $this->rules, $this->messages);

        //Vérifier si la validation a échoué
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // Code d'erreur HTTP 422 pour données non valides
        }

        //Si la validation a réussi, créer le cupcake
        $cupCake = CupCake::create([
            'name' => $request->title,
            'image' => $request->imageSource,
            'quantity' => $request->quantity,
            'is_available' => $request->isAvailable,
            'is_advertised' => $request->isAdvertised,
            'price' => $request->price,
        ]);

        return CupCakeResource::make($cupCake);
    }

    public function updateCupCake(Request $request)
    {
        //Récupérer le cupcake en BDD
        $cupCake = CupCake::find($request->id);

        //Si le cupcake n'éxiste pas, renvoyer un message d'érreur
        if ($cupCake === null) {
            return response()->json(['message' => 'CupCake not found'], 404);
        }

        //Sinon, mettre à jour les informations du cupcake
        $cupCake->update([
            'name' => $request->title,
            'image' => $request->imageSource,
            'quantity' => $request->quantity,
            'is_available' => $request->isAvailable,
            'is_advertised' => $request->isAdvertised,
            'price' => $request->price,
        ]);

        return CupCakeResource::make($cupCake);
    }

    public function deleteCupCake($id)
    {
        $cupCake = CupCake::find($id);
        $cupCake->delete();
    }
}
