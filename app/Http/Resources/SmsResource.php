<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SmsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "message" => $this->message,
            "date" => date("d/m/Y à H:i", strtotime($this->created_at)),
            "user" => $this->user,
            "salon_id" => $this->salon_id,
        ];
    }
}
