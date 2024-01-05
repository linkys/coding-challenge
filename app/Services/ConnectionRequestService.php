<?php

namespace App\Services;

use App\Dto\Connection\StoreDto;
use App\Models\ConnectionRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class ConnectionRequestService
{
    public function getSentRequestsForUser(User $user): Builder
    {
        return ConnectionRequest::query()
            ->with('receiver')
            ->where('from_user_id', $user->id);
    }

    public function getReceivedRequestsForUser(User $user): Builder
    {
        return ConnectionRequest::query()
            ->with('sender')
            ->where('to_user_id', $user->id);
    }

    public function store(StoreDto $dto, User $user): ConnectionRequest
    {
        return ConnectionRequest::create([
            'from_user_id' => $user->id,
            'to_user_id' => $dto->user->id,
        ]);
    }

    public function accept(ConnectionRequest $connectionRequest, User $user): bool
    {
        $connectionRequest->sender->straightConnections()->attach($user);

        return (bool) $connectionRequest->delete();
    }
}
