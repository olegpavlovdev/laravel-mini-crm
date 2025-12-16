

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <h3>Tickets</h3>

    <form method="get" class="row g-2 mb-3">
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">All statuses</option>
                <option value="new" <?php echo e(request('status')=='new' ? 'selected' : ''); ?>>New</option>
                <option value="in_progress" <?php echo e(request('status')=='in_progress' ? 'selected' : ''); ?>>In Progress</option>
                <option value="processed" <?php echo e(request('status')=='processed' ? 'selected' : ''); ?>>Processed</option>
            </select>
        </div>
        <div class="col-md-2"><input name="email" value="<?php echo e(request('email')); ?>" class="form-control" placeholder="Email"></div>
        <div class="col-md-2"><input name="phone" value="<?php echo e(request('phone')); ?>" class="form-control" placeholder="Phone"></div>
        <div class="col-md-2"><input name="from" type="date" value="<?php echo e(request('from')); ?>" class="form-control"></div>
        <div class="col-md-2"><input name="to" type="date" value="<?php echo e(request('to')); ?>" class="form-control"></div>
        <div class="col-md-2"><button class="btn btn-primary">Filter</button></div>
    </form>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Created</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($ticket->id); ?></td>
                <td><?php echo e($ticket->customer->name); ?><br><small><?php echo e($ticket->customer->email); ?> / <?php echo e($ticket->customer->phone); ?></small></td>
                <td><?php echo e($ticket->subject); ?></td>
                <td>
                    <?php if($ticket->status == 'new'): ?>
                        <span class="badge bg-primary">New</span>
                    <?php elseif($ticket->status == 'in_progress'): ?>
                        <span class="badge bg-warning">In Progress</span>
                    <?php else: ?>
                        <span class="badge bg-success">Processed</span>
                    <?php endif; ?>
                </td>
                <td><?php echo e($ticket->created_at->format('Y-m-d H:i')); ?></td>
                <td><a href="<?php echo e(route('admin.tickets.show', $ticket)); ?>" class="btn btn-sm btn-outline-primary">View</a></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <?php echo e($tickets->links()); ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/tickets/index.blade.php ENDPATH**/ ?>