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
        <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="border-t border-white/5 hover:bg-white/5">
                <td class="px-4 py-3 text-center"><?php echo e($post->id); ?></td>
                <td class="px-4 py-3 text-center"><?php echo e($post->user->name); ?></td>
                <td class="px-4 py-3 text-center"><?php echo e($post->user->company->company_name ?? '__'); ?></td>
                <td class="px-4 py-3 text-center"><?php echo e($post->content); ?></td>
                <td class="px-4 py-3 text-center"><?php echo e($post->training_category); ?></td>
                <td class="px-4 py-3 text-center max-w-[200px] truncate cursor-help"  
    				title="<?php echo e($post->image); ?>"><?php echo e($post->image); ?></td>
                <td class="px-4 py-3 text-center max-w-[200px] truncate cursor-help"
    				title="<?php echo e($post->file); ?>"><?php echo e($post->file); ?></td>
                <td class="px-4 py-3 text-center"><?php echo e($post->created_at); ?></td>
                <td class="px-4 py-3 text-center text-right">
                    <form method="POST" action="<?php echo e(route('admin.post.delete', $post)); ?>">
                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                        <button class="text-red-400 hover:text-red-300 text-xs font-bold">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>

<div class="mt-4">
    <?php echo e($posts->links()); ?>

</div><?php /**PATH C:\Users\Kristel Lonzame\Desktop\Website_ojtFinder\htdocs\resources\views/admin/partials/posts.blade.php ENDPATH**/ ?>