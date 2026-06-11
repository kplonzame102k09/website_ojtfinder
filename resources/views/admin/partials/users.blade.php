<div class="bg-[#1e293b]/80 rounded-xl border border-white/10 overflow-x-auto">
    <table class="min-w-full text-sm text-slate-300">
        <thead class="bg-black/40 text-xs uppercase">
            <tr>
                <th class="px-4 py-3 text-center">ID</th>
    			<th class="px-4 py-3 text-center">IP Address</th>
    			<th class="px-4 py-3 text-center">User Agent</th>
                <th class="px-4 py-3 text-center">Name</th>
                <th class="px-4 py-3 text-center">Email</th>
                <th class="px-4 py-3 text-center">Email Verification</th>
                <th class="px-4 py-3 text-center">Contact Number</th>
                <th class="px-4 py-3 text-center">Role</th>
                <th class="px-4 py-3 text-center text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr class="border-t border-white/5 hover:bg-white/5">
                <td class="px-4 py-3 text-center">{{ $user->id }}</td>
                <td class="px-4 py-3 text-center"> {{ $user->sessions->first()?->ip_address ?? '---' }}</td>
                <td class="px-4 py-3 text-center">
                    <div class="flex items-center justify-center">
                        @if($user->sessions->first())
                            <span class="px-2 py-1 rounded  max-w-[200px] truncate cursor-help" 
                                  title="{{ $user->sessions->first()->user_agent }}">
                                {{ $user->sessions->first()->user_agent }}
                            </span>
                        @else
                            <span class="text-[9px] text-slate-700 uppercase font-black italic">
                                No Data
                            </span>
                        @endif
                    </div>
                </td>
                <td class="px-4 py-3 text-center">{{ $user->name }}</td>
                <td class="px-4 py-3 text-center">{{ $user->email }}</td>
                <td class="px-4 py-3 text-center">{{ $user->email_verified_at ? 'Verified' : 'Unverified' }}</td>
                <td class="px-4 py-3 text-center">{{ $user->contact_number }}</td>
                <td class="px-4 py-3 text-center">{{ $user->role }}</td>
                <td class="px-4 py-3 text-center text-right">
                    <form method="POST" action="{{ route('admin.user.delete', $user) }}">
                        @csrf @method('DELETE')
                        <button class="text-red-400 hover:text-red-300 text-xs font-bold">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $users->links() }}
</div>