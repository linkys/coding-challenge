<?php

namespace App\Http\Controllers;

use App\Dto\Connection\StoreDto;
use App\Http\Requests\Connection\StoreRequest;
use App\Http\Resources\ConnectionRequestResource;
use App\Models\ConnectionRequest;
use App\Services\ConnectionRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function __construct(
        public ConnectionRequestService $connectionRequestService,
    ) {
        $this->middleware('auth');
    }

    public function showSent(): AnonymousResourceCollection
    {
        $connectionRequests = $this->connectionRequestService->getSentRequestsForUser(Auth::user());

        return ConnectionRequestResource::collection(
            $connectionRequests->paginate(config('pagination.items_per_page'))
        );
    }

    public function showReceived(): AnonymousResourceCollection
    {
        $connectionRequests = $this->connectionRequestService->getReceivedRequestsForUser(Auth::user());

        return ConnectionRequestResource::collection(
            $connectionRequests->paginate(config('pagination.items_per_page'))
        );
    }

    public function store(StoreRequest $request): JsonResponse
    {
        $connectionRequest = $this->connectionRequestService->store(StoreDto::fromRequest($request), Auth::user());

        return response()->json([
            'message' => __('Request has been created.'),
            'request' => ConnectionRequestResource::make($connectionRequest),
        ]);
    }

    public function destroy(ConnectionRequest $connectionRequest): JsonResponse
    {
        $connectionRequest->delete();

        return response()->json([
            'message' => __('Request has been removed.'),
        ]);
    }

    public function accept(ConnectionRequest $connectionRequest): JsonResponse
    {
        $this->connectionRequestService->accept($connectionRequest, Auth::user());

        return response()->json([
            'message' => __('Request has been accepted.'),
        ]);
    }
}
