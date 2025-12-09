<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyMeetingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $statusArr = [
            0 => 'De-Active',
            1 => 'Active',
            2 => 'Hold',
            3 => 'On Going',
            4 => 'Completed'
        ];

        return [
            "id" => $this->id,
            "admin_id" => $this->admin_id,
            "user_id" => $this->user_id,
            "company_id" => $this->company_id,
            "industry_id" => $this->industry_id,
            "communication_type_id" => $this->communication_type_id,
            "status" => $statusArr[$this->status],
            "status_val" => $this->status,
            "segment_id" => $this->segment_id,
            "title" => $this->title,
            "date" => $this->date,
            "description" => $this->description,
            "short_description" => $this->short_description,
            "follow_up_detail" => $this->follow_up_detail,
            "follow_up_date" => $this->follow_up_date,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i'),
        ];
    }
}
