<x-admin-layout title="Users">
    <section class="heading">
        <div>
            <h1>Users</h1>
            <p class="copy">View customer profiles, roles, and account status.</p>
        </div>
    </section>

    <section class="table-wrap">
        <table>
            <thead><tr><th>User</th><th>Phone</th><th>Roles</th><th>Status</th><th>Update</th></tr></thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td><strong>{{ $user->name }}</strong><br><span class="copy">{{ $user->email }}</span></td>
                        <td>{{ $user->phone ?? 'N/A' }}</td>
                        <td>{{ $user->roles->pluck('name')->join(', ') ?: 'customer' }}</td>
                        <td><span class="pill {{ $user->is_active ? 'ok' : 'bad' }}">{{ $user->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td>
                            <form class="actions" method="POST" action="{{ route('admin.users.update', $user) }}">
                                @csrf
                                @method('PUT')
                                <select name="is_active">
                                    <option value="1" @selected($user->is_active)>Active</option>
                                    <option value="0" @selected(! $user->is_active)>Inactive</option>
                                </select>
                                <div class="actions">
                                    @foreach ($roles as $role)
                                        <label style="display:inline-flex;align-items:center;gap:5px;">
                                            <input
                                                type="checkbox"
                                                name="role_ids[]"
                                                value="{{ $role->id }}"
                                                @checked($user->roles->contains('id', $role->id))
                                            >
                                            {{ ucfirst($role->name) }}
                                        </label>
                                    @endforeach
                                </div>
                                <button class="button" type="submit">Save</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>

    <div class="pages">{{ $users->links() }}</div>
</x-admin-layout>
