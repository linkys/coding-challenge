<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\ConnectionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class ConnectionController extends Controller
{
    public function __construct(
        public ConnectionService $connectionService,
    ) {
        $this->middleware('auth');
    }

    public function suggestions(): AnonymousResourceCollection
    {
        $suggestions = $this->connectionService->getSuggestionsForUser(Auth::user());

        return UserResource::collection(
            $suggestions->paginate(config('pagination.items_per_page'))
        );
    }

    public function connections(): AnonymousResourceCollection
    {
        $connections = $this->connectionService->getPaginatedConnectionsForUser(Auth::user());

        return UserResource::collection($connections);
    }

    public function removeConnection(User $user): JsonResponse
    {
        $user->straightConnections()->detach(Auth::user());
        $user->inverseConnections()->detach(Auth::user());

        return response()->json([
            'message' => __('Connection has been removed.'),
        ]);
    }

    public function showCommonConnections(User $user): AnonymousResourceCollection
    {
        $connections = $this->connectionService->getCommonConnectionsForUsers(Auth::user(), $user);

        return UserResource::collection(
            $connections->paginate(config('pagination.items_per_page'))
        );
    }
}
