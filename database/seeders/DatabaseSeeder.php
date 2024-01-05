<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $mainUser = User::factory()
            ->hasStraightConnections(120)
            ->hasSentRequests(20)
            ->hasReceivedRequests(45)
            ->create([
                'name' => 'Main User',
                'email' => 'root@root.com',
            ]);

        $commonUser = User::factory()->create();
        $connectedUsers = $mainUser->connections()->take(25)->add($mainUser);

        foreach ($connectedUsers as $connectedUser) {
            $commonUser->straightConnections()->attach($connectedUser);
        }
    }
}
