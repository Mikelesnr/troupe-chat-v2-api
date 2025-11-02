<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\MessageResource;

class ConversationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'topic' => $this->topic,
            'created_by' => $this->created_by,
            'participants' => $this->whenLoaded(
                'participants',
                fn() =>
                $this->participants->map(fn($p) => [
                    'id' => $p->id,
                    'user_id' => $p->user_id,
                    'joined_at' => $p->created_at,
                    'user' => new UserResource($p->user),
                ])
            ),
            'messages' => $this->whenLoaded(
                'messages',
                fn() =>
                MessageResource::collection($this->messages)
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
