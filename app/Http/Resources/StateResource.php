<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StateResource extends JsonResource
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
            "name" => $this->name,
            "slug" => $this->slug,
            "fips_code" => $this->fips_code,
            "numeric_code" => $this->numeric_code,
            "iso2" => $this->iso2,
            "latitude" => $this->latitude,
            "longitude" => $this->longitude,
            "status" => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i'),
        ];
    }
}
