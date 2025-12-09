<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            "id" => $this->id,
            "continent_id" => $this->continent_id,
            "continent_name" => $this->continent->name,
            "country_id" => $this->country_id,
            "country_name" => $this->country->name,
            "state_id" => $this->state_id,
            "state_name" => $this->state->name,
            "name" => $this->name,
            "slug" => $this->slug,
            "latitude" => $this->latitude,
            "longitude" => $this->longitude,
            "status" => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i'),
        ];
    }
}
