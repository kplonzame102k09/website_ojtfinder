<div class="bg-[#1e293b]/80 rounded-xl border border-white/10 overflow-x-auto">
    <table class="min-w-full text-sm text-slate-300">
        <thead class="bg-black/40 text-xs uppercase">
            <tr>
                <th class="px-4 py-3 text-center">ID</th>
                <th class="px-4 py-3 text-center">Author</th>
                <th class="px-4 py-3 text-center">Company Name</th>
                <th class="px-4 py-3 text-center">Content</th>
                <th class="px-4 py-3 text-center">Category</th>
                <th class="px-4 py-3 text-center">Image</th>
                <th class="px-4 py-3 text-center">File</th>
                <th class="px-4 py-3 text-center">Created At</th>
                <th class="px-4 py-3 text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($posts as $post)
            <tr class="border-t border-white/5 hover:bg-white/5">
                <td class="px-4 py-3 text-center">{{ $post->id }}</td>
                <td class="px-4 py-3 text-center">{{ $post->user->name }}</td>
                <td class="px-4 py-3 text-center">{{ $post->user->company->company_name ?? '__' }}</td>
                <td class="px-4 py-3 text-center">{{ $post->content }}</td>
                <td class="px-4 py-3 text-center">{{ $post->training_category }}</td>
                <td class="px-4 py-3 text-center max-w-[200px] truncate cursor-help"  
    				title="{{ $post->image }}">{{ $post->image }}</td>
                <td class="px-4 py-3 text-center max-w-[200px] truncate cursor-help"
    				title="{{ $post->file }}">{{ $post->file }}</td>
                <td class="px-4 py-3 text-center">{{ $post->created_at }}</td>
                <td class="px-4 py-3 text-center text-right">
                    <form method="POST" action="{{ route('admin.post.delete', $post) }}">
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
    {{ $posts->links() }}
</div>