<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Services\ConnectionService;
use Exception;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserResource extends JsonResource
{
    /**
     * @throws Exception
     */
    public function toArray($request): array
    {
        if (Auth::guest()) {
            throw new Exception(__('Need to authenticate.'));
        }

        /** @var User $user */
        $user = $this->resource;

        $commonConnectionsCount = app(ConnectionService::class)
            ->getCommonConnectionsForUsers(Auth::user(), $user)
            ->count();

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'common_connections_count' => $commonConnectionsCount,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }
}
