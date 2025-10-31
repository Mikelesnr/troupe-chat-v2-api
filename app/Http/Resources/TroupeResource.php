<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TroupeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'visibility' => $this->visibility,
            'avatar_url' => $this->avatar_url,
            'created_by' => $this->created_by,
            'creator' => $this->whenLoaded('creator', fn() => [
                'id' => $this->creator->id,
                'name' => $this->creator->name,
            ]),
            'interest_tags' => $this->whenLoaded(
                'interestTags',
                fn() =>
                $this->interestTags->map(fn($tag) => [
                    'id' => $tag->id,
                    'name' => $tag->name,
                ])
            ),
            'members' => $this->whenLoaded(
                'members',
                fn() =>
                $this->members->map(fn($member) => [
                    'id' => $member->id,
                    'user_id' => $member->user_id,
                    'joined_at' => $member->created_at,
                ])
            ),
            'messages' => $this->whenLoaded(
                'messages',
                fn() =>
                $this->messages->map(fn($message) => [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'content' => $message->content,
                    'sent_at' => $message->created_at,
                ])
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
