

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <a href="<?php echo e(route('admin.tickets.index')); ?>" class="btn btn-sm btn-secondary mb-3">Back to list</a>

    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <div class="card mb-3">
        <div class="card-header">Ticket #<?php echo e($ticket->id); ?> — <?php echo e($ticket->subject); ?></div>
        <div class="card-body">
            <p><strong>Customer:</strong> <?php echo e($ticket->customer->name); ?> — <?php echo e($ticket->customer->email); ?> / <?php echo e($ticket->customer->phone); ?></p>
            <p><strong>Message:</strong><br><?php echo e(nl2br(e($ticket->message))); ?></p>
            <p><strong>Status:</strong>
                <?php if($ticket->status == 'new'): ?>
                    <span class="badge bg-primary">New</span>
                <?php elseif($ticket->status == 'in_progress'): ?>
                    <span class="badge bg-warning">In Progress</span>
                <?php else: ?>
                    <span class="badge bg-success">Processed</span>
                <?php endif; ?>
            </p>

            <form method="post" action="<?php echo e(route('admin.tickets.updateStatus', $ticket)); ?>" class="row g-2 align-items-center">
                <?php echo csrf_field(); ?>
                <div class="col-auto">
                    <select name="status" class="form-select">
                        <option value="new" <?php echo e($ticket->status=='new' ? 'selected' : ''); ?>>New</option>
                        <option value="in_progress" <?php echo e($ticket->status=='in_progress' ? 'selected' : ''); ?>>In Progress</option>
                        <option value="processed" <?php echo e($ticket->status=='processed' ? 'selected' : ''); ?>>Processed</option>
                    </select>
                </div>
                <div class="col-auto"><button class="btn btn-primary">Change</button></div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Attachments</div>
        <div class="card-body">
            <?php if($attachments->isEmpty()): ?>
                <p>No attachments</p>
            <?php else: ?>
                <ul>
                    <?php $__currentLoopData = $attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $media): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <?php echo e($media->file_name); ?> — <a href="<?php echo e(route('admin.tickets.downloadAttachment', [$ticket, $media->id])); ?>">Download</a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/tickets/show.blade.php ENDPATH**/ ?>