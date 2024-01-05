<?php

namespace App\Http\Resources;

use App\Models\ConnectionRequest;
use Illuminate\Http\Resources\Json\JsonResource;

class ConnectionRequestResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var ConnectionRequest $connectionRequest */
        $connectionRequest = $this->resource;

        return [
            'id' => $connectionRequest->id,
            'sender' => UserResource::make($this->whenLoaded('sender')),
            'receiver' => UserResource::make($this->whenLoaded('receiver')),
            'created_at' => $connectionRequest->created_at,
            'updated_at' => $connectionRequest->updated_at,
        ];
    }
}
