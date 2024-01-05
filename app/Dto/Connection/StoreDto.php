<?php
namespace App\Dto\Connection;

use App\Http\Requests\Connection\StoreRequest;
use App\Models\User;

class StoreDto
{
    public function __construct(
        public User $user,
    ) {}

    public static function fromRequest(StoreRequest $request): self
    {
        $data = $request->validated();

        return new self(
            user: User::find($data['user_id']),
        );
    }
}
