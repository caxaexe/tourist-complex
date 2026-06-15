<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'active', 'role:admin']);
    }

    public function index(Request $request)
    {
        $q = $request->query('q');

        $users = User::query()
            ->with('roles')
            ->when($q, function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%")
                      ->orWhere('full_name', 'like', "%{$q}%");
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'q'));
    }

    public function create()
{
    $roles = Role::orderBy('name')->get(); 
    return view('admin.users.create', compact('roles'));
}

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'full_name'  => 'nullable|string|max:255',
            'email'      => 'required|email|max:255|unique:users,email',
            'phone'      => 'nullable|string|max:50',
            'password'   => 'required|string|min:6|confirmed',
            'salary'     => 'nullable|numeric|min:0',
            'position'   => 'nullable|string|max:255',
            'duties'     => 'nullable|string|max:5000',
            'is_active'  => 'nullable|boolean',
            'roles'      => 'nullable|array',
            'roles.*'    => 'exists:roles,id',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = (bool)($data['is_active'] ?? false);

        $roles = $data['roles'] ?? [];
        unset($data['roles']);

        $user = User::create($data);
        $user->roles()->sync($roles);

        logAudit('created', $user, null, $user->toArray());

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь создан');
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();
        $selectedRoleIds = $user->roles()->pluck('roles.id')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'selectedRoleIds'));
    }

    public function update(Request $request, User $user)
    {
        $old = $user->toArray();

        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'full_name'  => 'nullable|string|max:255',
            'email'      => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone'      => 'nullable|string|max:50',
            'password'   => 'nullable|string|min:6|confirmed',
            'salary'     => 'nullable|numeric|min:0',
            'position'   => 'nullable|string|max:255',
            'duties'     => 'nullable|string|max:5000',
            'is_active'  => 'nullable|boolean',
            'roles'      => 'nullable|array',
            'roles.*'    => 'exists:roles,id',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['is_active'] = (bool)($data['is_active'] ?? false);

        $roles = $data['roles'] ?? [];
        unset($data['roles']);

        $user->update($data);
        $user->roles()->sync($roles);

        logAudit('updated', $user, $old, $user->fresh()->toArray());

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь обновлён');
    }

    public function destroy(User $user)
    {
        // 1) нельзя удалить самого себя
        if ($user->id === auth()->id()) {
            return back()->with('success', 'Нельзя удалить самого себя');
        }

        // 2) нельзя удалить админа
        if ($user->hasRole('admin')) {
            return back()->with('success', 'Нельзя удалить администратора');
        }

        $old = $user->toArray();

        $user->delete();

        logAudit('deleted', $user, $old, null);

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь удалён');
    }
}
