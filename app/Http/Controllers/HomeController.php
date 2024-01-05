<?php

namespace App\Http\Controllers;

use App\Services\ConnectionRequestService;
use App\Services\ConnectionService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __construct(
        public ConnectionService $connectionService,
        public ConnectionRequestService $connectionRequestService,
    ) {
        $this->middleware('auth');
    }

    public function index(): View
    {
        $user = Auth::user();

        $counts = [
            'suggestionsCount' => $this->connectionService->getSuggestionsForUser(Auth::user())->count(),
            'sentRequestsCount' => $this->connectionRequestService->getSentRequestsForUser(Auth::user())->count(),
            'receivedRequestsCount' => $this->connectionRequestService->getReceivedRequestsForUser(Auth::user())->count(),
            'connectionsCount' => $user->connections()->count(),
        ];

        return view('home', compact('counts'));
    }
}
