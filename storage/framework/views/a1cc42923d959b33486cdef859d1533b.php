<div class="bg-[#1e293b]/80 rounded-xl border border-white/10 overflow-x-auto">
    <table class="min-w-full text-sm text-slate-300">
        <thead class="bg-black/40 text-xs uppercase">
            <tr>
                <th class="px-4 py-3">ID</th>
                <th class="px-4 py-3">Owner</th>
                <th class="px-4 py-3">Company Name</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3">Company Logo</th>
                <th class="px-4 py-3">Certificate of registration</th>
                <th class="px-4 py-3">Mayors Permit</th>
                <th class="px-4 py-3">Certificate of Corporation</th>
                <th class="px-4 py-3">Barangay Clearance</th>
                <th class="px-4 py-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="border-t border-white/5 hover:bg-white/5">
                <td class="px-4 py-3 text-center"><?php echo e($company->id); ?></td>
                <td class="px-4 py-3 text-center"><?php echo e($company->user->name); ?></td>
                <td class="px-4 py-3 text-center"><?php echo e($company->company_name); ?></td>
                <td class="px-4 py-3 text-center"><?php echo e($company->email); ?></td>
                <td class="px-4 py-3 text-center">
                    <?php if($company->company_logo): ?>
                        <a href="<?php echo e(route('company.file', [
                            'company' => $company->id,
                            'path' => $company->company_logo
                        ])); ?>" target="_blank"
                        class="text-blue-400 hover:underline text-xs font-bold">
                            Preview
                        </a>
                    <?php else: ?>
                        <span class="text-gray-500 text-xs">No file</span>
                    <?php endif; ?>
                </td>
                
                <td class="px-4 py-3 text-center">
                    <?php if($company->certificate_of_registration): ?>
                        <a href="<?php echo e(route('company.file', [
                        'company' => $company->id,
                        'path' => $company->certificate_of_registration
                        ])); ?>" target="_blank" class="text-blue-400 hover:underline text-xs font-bold">
                            Preview
                        </a>
                    <?php else: ?>
                        <span class="text-gray-500 text-xs">No file</span>
                    <?php endif; ?>
                </td>

                <td class="px-4 py-3 text-center">
                    <?php if($company->mayors_permit): ?>
                        <a href="<?php echo e(route('company.file', [
                            'company' => $company->id,
                            'path' => $company->mayors_permit
                        ])); ?>" target="_blank" class="text-blue-400 hover:underline text-xs font-bold">
                            Preview
                        </a>
                    <?php else: ?>
                        <span class="text-gray-500 text-xs">No file</span>
                    <?php endif; ?>
                </td>

                <td class="px-4 py-3 text-center">
                    <?php if($company->certificate_of_corporation): ?>
                        <a href="<?php echo e(route('company.file', [
                            'company' => $company->id,
                            'path' => $company->certificate_of_corporation
                        ])); ?>" target="_blank" class="text-blue-400 hover:underline text-xs font-bold">
                            Preview
                        </a>
                    <?php else: ?>
                        <span class="text-gray-500 text-xs">No file</span>
                    <?php endif; ?>
                </td>

                <td class="px-4 py-3 text-center">
                    <?php if($company->barangay_clearance): ?>
                        <a href="<?php echo e(route('company.file', [
                            'company' => $company->id,
                            'path' => $company->barangay_clearance
                        ])); ?>" target="_blank" class="text-blue-400 hover:underline text-xs font-bold">Preview</a>
                    <?php else: ?>
                        <span class="text-gray-500 text-xs">No file</span>
                    <?php endif; ?>
                </td>

                <td class="px-4 py-3 text-center">
                    <form method="POST" action="<?php echo e(route('admin.company.delete', $company)); ?>">
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
    <?php echo e($companies->links()); ?>

</div><?php /**PATH C:\Users\Kristel Lonzame\Desktop\Website_ojtFinder\htdocs\resources\views/admin/partials/companies.blade.php ENDPATH**/ ?>