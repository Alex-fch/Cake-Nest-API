<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CupCakeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //Vérifier si il y a une table pivot
        $pivotData = $this->pivot ? $this->pivot->toArray() : [];

        return [
            'id' => $this->id,
            'title' => $this->name,
            'imageSource' => $this->image,
            'quantity' => $pivotData ? $pivotData['quantity'] : $this->quantity, //Si table pivot, mettre la quantité de la table pivot
            'isAvailable' => $this->is_available,
            'isAdvertised' => $this->is_advertised,
            'price' => $pivotData ? $pivotData['price'] : $this->price, //Si table pivot, mettre le prix de la table pivot
        ];
    }
}
