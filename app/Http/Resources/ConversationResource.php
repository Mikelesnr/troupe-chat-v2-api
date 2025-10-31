<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

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
                ])
            ),
            'messages' => $this->whenLoaded(
                'messages',
                fn() =>
                $this->messages->map(fn($m) => [
                    'id' => $m->id,
                    'sender_id' => $m->sender_id,
                    'content' => $m->content,
                    'sent_at' => $m->created_at,
                ])
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
