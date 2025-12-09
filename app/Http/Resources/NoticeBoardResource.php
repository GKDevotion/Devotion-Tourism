<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoticeBoardResource extends JsonResource
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
            // "title" => $this->title,
            "type" => $this->type,
            "description" => $this->description,
            "date" => $this->date,
            "attachement" => $this->getImages( $this->attachement ),
            "notice_by" => $this->notice_by,
            "sort_order" => $this->sort_order,
            "status" => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i')
        ];
    }

    /**
     * @param [type] $imagesObject
     * @return void
     */
    public function getImages($image)
    {
        if( !empty( $image ) ){
            return url('storage/'.$image);
        }

        return null;
    }
}
