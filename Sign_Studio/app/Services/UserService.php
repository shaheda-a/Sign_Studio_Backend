<?php

namespace App\Services;

use App\Models\User;
use App\Services\LoginHistoryService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService
{
    /**
     * Get list of all users with optional filtering and pagination.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function getUsers(array $filters = [], int $perPage = 15): mixed
    {
        $query = User::query()->with(['branch', 'department', 'roles']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['branch_id'])) {
            $query->where('branch_id', $filters['branch_id']);
        }

        if (!empty($filters['department_id'])) {
            $query->where('department_id', $filters['department_id']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (!empty($filters['with_trashed']) && $filters['with_trashed'] === 'true') {
            $query->withTrashed();
        }

        if ($perPage === -1) {
            return $query->orderBy('name', 'asc')->get();
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * Create a new user and assign roles.
     *
     * @param array $data
     * @return User
     */
    public function createUser(array $data): User
    {
        $roles = $data['roles'] ?? [];
        unset($data['roles']);

        $data['created_by'] = Auth::id();
        
        $user = User::create($data);

        if (!empty($roles)) {
            $user->syncRoles($roles);
        }

        return $user;
    }

    /**
     * Get user by ID.
     *
     * @param int $id
     * @return User
     */
    public function getUserById(int $id): User
    {
        return User::with(['branch', 'department', 'roles'])->findOrFail($id);
    }

    /**
     * Update an existing user and sync roles.
     *
     * @param int $id
     * @param array $data
     * @return User
     */
    public function updateUser(int $id, array $data): User
    {
        $user = User::findOrFail($id);

        $roles = $data['roles'] ?? null;
        unset($data['roles']);

        // Don't update password if not provided
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        if ($roles !== null) {
            $user->syncRoles($roles);
        }

        return $user;
    }

    /**
     * Soft delete a user.
     *
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id): bool
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }

    /**
     * Restore a soft deleted user.
     *
     * @param int $id
     * @return User
     */
    public function restoreUser(int $id): User
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return $user;
    }

    /**
     * Handle user login.
     *
     * @param string $email
     * @param string $password
     * @param string $device
     * @return array
     * @throws ValidationException
     */
    public function login(string $email, string $password, string $device = 'Web App'): array
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            if ($user) {
                app(LoginHistoryService::class)->logLogin($user->id, 'failed', $device);
            }
            throw ValidationException::withMessages([
                'email' => ['Invalid email or password.'],
            ]);
        }

        if ((int)$user->is_active !== 1) {
            app(LoginHistoryService::class)->logLogin($user->id, 'inactive', $device);
            throw ValidationException::withMessages([
                'email' => ['User account is inactive. Please contact system admin.'],
            ]);
        }

        $token = $user->createToken($device)->plainTextToken;

        app(LoginHistoryService::class)->logLogin($user->id, 'success', $device);

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    /**
     * Handle user logout (revoke token).
     *
     * @param User $user
     * @return void
     */
    public function logout(User $user): void
    {
        $token = $user->currentAccessToken();
        if ($token && method_exists($token, 'delete')) {
            $token->delete();
        } else {
            $user->tokens()->delete();
        }
        app(LoginHistoryService::class)->logLogout($user->id);
    }

    /**
     * Change user password.
     *
     * @param User $user
     * @param string $currentPassword
     * @param string $newPassword
     * @return void
     * @throws ValidationException
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Current password does not match.'],
            ]);
        }

        $user->update([
            'password' => $newPassword,
        ]);
    }
}
