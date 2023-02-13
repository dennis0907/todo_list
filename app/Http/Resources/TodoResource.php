<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TodoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type_id' => $this->type_id,
            'type_name' => $this->type->name,
            'title' => $this->title,
            'content' => $this->content,
            'finished' => $this->finished,
            'time_left' => $this->timeleft,
            'created_at' => $this->created_at,
            'updated_at' => (string)$this->updated_at,
            'user_id' => $this->user_id
        ];
    }
}
