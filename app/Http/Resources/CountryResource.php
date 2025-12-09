<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
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
            "name" => $this->name,
            "slug" => $this->slug,
            "iso3" => $this->iso3,
            "numeric_code" => $this->numeric_code,
            "iso2" => $this->iso2,
            "phone_code" => $this->phone_code,
            "capital" => $this->capital,
            "currency" => $this->currency,
            "currency_name" => $this->currency_name,
            "currency_symbol" => $this->currency_symbol,
            "tld" => $this->tld,
            "latitude" => $this->latitude,
            "longitude" => $this->longitude,
            "status" => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i'),
        ];
    }
}
