<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Admin\Concerns\PaginatesTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Super Admin management of website admin users and their roles (spec §26.7):
 * who can edit content, work leads, only view leads, or export them.
 */
class UserController extends Controller
{
    use PaginatesTables;

    public function index(Request $request): View
    {
        $users = $this->paginateTable(
            $request,
            User::query()->select(['id', 'name', 'email', 'role', 'can_export_leads', 'portal_role', 'created_at'])->orderBy('name'),
            ['name', 'email'],
        );

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create', ['user' => new User(['role' => UserRole::Sales->value])]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::create([
            ...$request->safe()->only(['name', 'email', 'password', 'role']),
            'can_export_leads' => $request->boolean('can_export_leads'),
        ]);

        return redirect()->route('admin.users.index')->with('status', 'User created.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = [
            ...$request->safe()->only(['name', 'email', 'role']),
            'can_export_leads' => $request->boolean('can_export_leads'),
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->validated('password');
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('status', 'User updated.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->withErrors(['user' => 'You cannot delete your own account.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'User deleted.');
    }
}
