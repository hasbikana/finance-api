<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\CategoryRepository;
use App\Repositories\NotificationRepository;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(
        protected CategoryRepository $categoryRepo,
        protected NotificationRepository $notificationRepo
    ) {}

    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        $this->createDefaultCategories($user);

        return ['user' => $user, 'token' => $token];
    }

    public function login(string $email, string $password): ?array
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return ['user' => $user, 'token' => $token];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    public function getProfile(User $user): User
    {
        return $user;
    }

    public function updateProfile(User $user, array $data): User
    {
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        return $user->fresh();
    }

    public function updatePassword(User $user, string $currentPassword, string $newPassword): bool
    {
        if (!Hash::check($currentPassword, $user->password)) {
            return false;
        }

        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        return true;
    }

    protected function createDefaultCategories(User $user): void
    {
        $defaults = ['Gaji', 'Investasi', 'Makanan', 'Transportasi', 'Belanja', 'Hiburan', 'Tagihan'];

        foreach ($defaults as $name) {
            $this->categoryRepo->findByUser($user->id)->firstWhere('name', $name)
                ?? \App\Models\Category::create(['user_id' => $user->id, 'name' => $name]);
        }
    }
}
