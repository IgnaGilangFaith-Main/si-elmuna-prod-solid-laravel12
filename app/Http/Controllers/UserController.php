<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    // Menampilkan daftar user
    public function index()
    {
        $user = Auth::user();
        if ($user->can('admin-user')) {
            $data = User::latest()->paginate(10);
        } else {
            $data = User::where('id', $user->id)->latest()->paginate(1);
        }

        // $data = User::orderBy('id', 'desc')->paginate(10);

        return view('users.index', [
            'data' => $data,
        ]);
    }

    // Menampilkan form tambah user
    public function create()
    {
        $permission = Permission::get();

        return view('users.create', ['permission' => $permission]);
    }

    // Menyimpan user baru
    public function store(UserStoreRequest $request)
    {
        $request->validated();

        $email_verified_at = $request->email_verified_at ? Carbon::now() : null;

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'email_verified_at' => $email_verified_at,
        ];

        $newUser = User::create($data);
        $newUser->syncPermissions($request->permission ?? []);
        sweetalert()->success('User berhasil ditambahkan!');

        return redirect('/user');
    }

    // Menampilkan form edit user
    public function edit($id)
    {
        $user = User::findOrFail($id);
        Gate::authorize('edit', $user);
        $permissions = Permission::get();
        $userPermissions = $user->getPermissionNames()->toArray();
        $data = $user;

        return view('users.edit', [
            'data' => $data,
            'permissions' => $permissions,
            'userPermissions' => $userPermissions,
        ]);
    }

    // Mengupdate data user
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'new_password' => 'nullable|min:6|same:new_password_confirmation|required_with:new_password_confirmation',
            'new_password_confirmation' => 'required_with:new_password',
        ], [
            'name.required' => 'Nama wajib diisi!',
            'email.required' => 'Email wajib diisi!',
            'email.email' => 'Email harus berformat email!',
            'email.unique' => 'Email sudah terdaftar, silahkan gunakan email lain!',
            'new_password.required_with' => 'Password harus diisi!',
            'new_password.same' => 'Password harus sama dengan Konfirmasi Password!',
            'new_password.min' => 'Password harus minimal :min karakter!',
            'new_password_confirmation.required_with' => 'Konfirmasi Password harus diisi!',
        ]);

        $user = User::findOrFail($id);

        $email_verified = $user->email_verified_at ? $user->email_verified_at : Carbon::now();

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'email_verified_at' => $email_verified,
            'password' => $request->new_password ? bcrypt($request->new_password) : $user->password,
        ];

        $user->update($data);
        $user->syncPermissions($request->permission ?? []);
        sweetalert()->success('Data User berhasil diupdate!');

        return redirect('/user');
    }

    // Menampilkan konfirmasi hapus user
    public function delete($id)
    {
        $data = User::findOrFail($id);

        return view('users.delete', ['data' => $data]);
    }

    // Menghapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        sweetalert()->success('Data User berhasil dihapus!');

        return redirect('/user');
    }

    // Memblokir/unblok user
    public function toggleBlock($id)
    {
        $user = User::findOrFail($id);

        if ($user->blocked_at == null) {
            $data = [
                'blocked_at' => now(),
            ];

            $message = 'User '.$user->name.' telah di-blokir!';
        } else {
            $data = [
                'blocked_at' => null,
            ];

            $message = 'User '.$user->name.' telah di-unblok!';
        }

        $user->update($data);
        sweetalert()->success($message);

        return redirect()->back();
    }
}
