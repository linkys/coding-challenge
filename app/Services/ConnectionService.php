<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class ConnectionService
{
    public function getSuggestionsForUser(User $user): Builder
    {
        $connectedUsersIds = $user->connections()->pluck('id');
        $sentRequestUsersIds = $user->sentRequests()->pluck('to_user_id');

        return User::query()
            ->whereNotIn('id', $connectedUsersIds)
            ->whereNotIn('id', $sentRequestUsersIds)
            ->where('id', '!=', $user->id);
    }

    public function getPaginatedConnectionsForUser(User $user): LengthAwarePaginator
    {
        $perPage = config('pagination.items_per_page');
        $connections = $user->connections();
        $page = LengthAwarePaginator::resolveCurrentPage();
        $slicedConnections = $connections->slice(($page - 1) * $perPage, $perPage);

        return new LengthAwarePaginator(
            $slicedConnections,
            $connections->count(),
            $perPage,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
    }

    public function getCommonConnectionsForUsers(User $user, User $otherUser): Builder
    {
        $userConnections = $user->connections()->pluck('id')->toArray();
        $otherUserConnections = $otherUser->connections()->pluck('id')->toArray();

        $commonConnectionsIds = array_intersect($userConnections, $otherUserConnections);

        return User::query()
            ->whereIn('id', $commonConnectionsIds);
    }
}
