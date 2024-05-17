<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //auth()->loginUsingId($this->id);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->when(
                $this->resource->is(auth()->user()),
                $this->email
            ),
            'orders' => OrderResource::collection($this->whenLoaded('orders')),
            'registeredAt' => $this->created_at,

        ];
    }
}
