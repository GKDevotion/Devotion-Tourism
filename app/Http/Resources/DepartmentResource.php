<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
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
            "industry_id" => $this->industry_id,
            "industry_name" => $this->industry->name,
            "industry_slug" => $this->industry->slug,
            "company_id" => $this->company_id,
            "company_name" => $this->company->name,
            "company_slug" => $this->company->slug,
            "name" => $this->name,
            "slug" => $this->slug,
            "sort_order" => $this->sort_order,
            "status" => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i'),
        ];
    }
}
