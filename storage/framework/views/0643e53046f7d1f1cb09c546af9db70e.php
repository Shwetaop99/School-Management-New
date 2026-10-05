<?php $__env->startSection('title', 'Staff Profile'); ?>

<?php $__env->startSection('content'); ?>

<style>
    .staff-page {
        background: #f4f7fb;
        min-height: calc(100vh - 64px);
        padding: 25px;
    }

    .staff-container {
        max-width: 1200px;
        margin: auto;
    }

    .profile-header {
        background: linear-gradient(135deg, #147cf5, #6c63ff);
        border-radius: 18px;
        padding: 30px;
        color: white;
        margin-bottom: 25px;
        box-shadow: 0 8px 25px rgba(20, 124, 245, 0.18);
    }

    .profile-header-content {
        display: flex;
        align-items: center;
        gap: 25px;
    }

    .staff-photo-wrapper {
        width: 125px;
        height: 125px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.25);
        padding: 5px;
        flex-shrink: 0;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .staff-photo-wrapper img {
        width: 115px;
        height: 115px;
        object-fit: cover;
        border-radius: 50%;
        display: block;
    }

    .default-profile {
        width: 115px;
        height: 115px;
        border-radius: 50%;
        background: white;
        color: #147cf5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 48px;
    }

    .profile-info h2 {
        margin: 0 0 8px;
        font-size: 28px;
        font-weight: 700;
    }

    .profile-info p {
        margin: 5px 0;
        font-size: 15px;
        opacity: 0.95;
    }

    .staff-id {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        margin-top: 8px;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        margin-top: 10px;
    }

    .status-active {
        background: #d1fae5;
        color: #047857;
    }

    .status-inactive {
        background: #fee2e2;
        color: #b91c1c;
    }

    .action-buttons {
        margin-left: auto;
        display: flex;
        gap: 10px;
        align-self: flex-start;
    }

    .btn-custom {
        border: none;
        padding: 10px 17px;
        border-radius: 9px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        cursor: pointer;
    }

    .btn-edit {
        background: white;
        color: #147cf5;
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.18);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.35);
    }

    .info-card {
        background: white;
        border-radius: 16px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.05);
    }

    .card-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e5e7eb;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px 30px;
    }

    .info-item {
        padding: 12px 15px;
        background: #f8fafc;
        border-radius: 10px;
    }

    .info-label {
        font-size: 12px;
        color: #64748b;
        margin-bottom: 5px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .info-value {
        font-size: 15px;
        color: #1e293b;
        font-weight: 500;
        word-break: break-word;
    }

    .empty-value {
        color: #94a3b8;
        font-style: italic;
    }

    .danger-card {
        border-left: 4px solid #ef4444;
    }

    @media (max-width: 768px) {
        .staff-page {
            padding: 15px;
        }

        .profile-header-content {
            flex-direction: column;
            text-align: center;
        }

        .action-buttons {
            margin-left: 0;
            align-self: center;
            flex-wrap: wrap;
            justify-content: center;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .profile-info h2 {
            font-size: 23px;
        }
    }
</style>

<div class="staff-page">

    <div class="staff-container">

        
        <div class="profile-header">

            <div class="profile-header-content">

                
                <div class="staff-photo-wrapper">

                    <?php if($otherStaff->profile_photo): ?>

                        <?php if (isset($component)) { $__componentOriginala40248e8bccff36c9e094fa8227c7bfa = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala40248e8bccff36c9e094fa8227c7bfa = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'cloudinary::components.image','data' => ['publicId' => ''.e($otherStaff->profile_photo).'','width' => '115','height' => '115','alt' => ''.e($otherStaff->name).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cloudinary::image'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['public-id' => ''.e($otherStaff->profile_photo).'','width' => '115','height' => '115','alt' => ''.e($otherStaff->name).'']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala40248e8bccff36c9e094fa8227c7bfa)): ?>
<?php $attributes = $__attributesOriginala40248e8bccff36c9e094fa8227c7bfa; ?>
<?php unset($__attributesOriginala40248e8bccff36c9e094fa8227c7bfa); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala40248e8bccff36c9e094fa8227c7bfa)): ?>
<?php $component = $__componentOriginala40248e8bccff36c9e094fa8227c7bfa; ?>
<?php unset($__componentOriginala40248e8bccff36c9e094fa8227c7bfa); ?>
<?php endif; ?>

                    <?php else: ?>

                        <div class="default-profile">
                            <i class="fas fa-user"></i>
                        </div>

                    <?php endif; ?>

                </div>

                
                <div class="profile-info">

                    <h2>
                        <?php echo e($otherStaff->name); ?>

                    </h2>

                    <p>
                        <i class="fas fa-briefcase"></i>
                        <?php echo e($otherStaff->designation ?? 'Staff Member'); ?>

                    </p>

                    <?php if($otherStaff->department): ?>
                        <p>
                            <i class="fas fa-building"></i>
                            <?php echo e($otherStaff->department); ?>

                        </p>
                    <?php endif; ?>

                    <span class="staff-id">
                        <i class="fas fa-id-card"></i>
                        <?php echo e($otherStaff->staff_id); ?>

                    </span>

                    <br>

                    <?php if($otherStaff->status === 'Active'): ?>
                        <span class="status-badge status-active">
                            <i class="fas fa-check-circle"></i>
                            Active
                        </span>
                    <?php else: ?>
                        <span class="status-badge status-inactive">
                            <i class="fas fa-times-circle"></i>
                            Inactive
                        </span>
                    <?php endif; ?>

                </div>

                
                <div class="action-buttons">

                    <a
                        href="<?php echo e(route('admin.other-staff.edit', $otherStaff->id)); ?>"
                        class="btn-custom btn-edit"
                    >
                        <i class="fas fa-edit"></i>
                        Edit
                    </a>

                    <a
                        href="<?php echo e(route('admin.other-staff.index')); ?>"
                        class="btn-custom btn-back"
                    >
                        <i class="fas fa-arrow-left"></i>
                        Back
                    </a>

                </div>

            </div>

        </div>


        
        <div class="info-card">

            <div class="card-title">
                <i class="fas fa-user"></i>
                Personal Information
            </div>

            <div class="info-grid">

                <div class="info-item">
                    <div class="info-label">Full Name</div>
                    <div class="info-value">
                        <?php echo e($otherStaff->name); ?>

                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Staff ID</div>
                    <div class="info-value">
                        <?php echo e($otherStaff->staff_id); ?>

                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Gender</div>
                    <div class="info-value">
                        <?php echo e($otherStaff->gender ?: 'Not Provided'); ?>

                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Date of Birth</div>
                    <div class="info-value">
                        <?php if($otherStaff->date_of_birth): ?>
                            <?php echo e(\Carbon\Carbon::parse($otherStaff->date_of_birth)->format('d M Y')); ?>

                        <?php else: ?>
                            <span class="empty-value">Not Provided</span>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

        </div>


        
        <div class="info-card">

            <div class="card-title">
                <i class="fas fa-address-book"></i>
                Contact Information
            </div>

            <div class="info-grid">

                <div class="info-item">
                    <div class="info-label">Phone</div>
                    <div class="info-value">
                        <?php if($otherStaff->phone): ?>
                            <i class="fas fa-phone"></i>
                            <?php echo e($otherStaff->phone); ?>

                        <?php else: ?>
                            <span class="empty-value">Not Provided</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value">
                        <?php if($otherStaff->email): ?>
                            <i class="fas fa-envelope"></i>
                            <?php echo e($otherStaff->email); ?>

                        <?php else: ?>
                            <span class="empty-value">Not Provided</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="info-item" style="grid-column: 1 / -1;">
                    <div class="info-label">Address</div>
                    <div class="info-value">
                        <?php if($otherStaff->address): ?>
                            <?php echo e($otherStaff->address); ?>

                        <?php else: ?>
                            <span class="empty-value">Not Provided</span>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

        </div>


        
        <div class="info-card">

            <div class="card-title">
                <i class="fas fa-briefcase"></i>
                Professional Information
            </div>

            <div class="info-grid">

                <div class="info-item">
                    <div class="info-label">Designation</div>
                    <div class="info-value">
                        <?php echo e($otherStaff->designation ?: 'Not Provided'); ?>

                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Department</div>
                    <div class="info-value">
                        <?php echo e($otherStaff->department ?: 'Not Provided'); ?>

                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Qualification</div>
                    <div class="info-value">
                        <?php echo e($otherStaff->qualification ?: 'Not Provided'); ?>

                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">License Number</div>
                    <div class="info-value">
                        <?php echo e($otherStaff->license_number ?: 'Not Provided'); ?>

                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">License Expiry</div>
                    <div class="info-value">
                        <?php if($otherStaff->license_expiry): ?>
                            <?php echo e(\Carbon\Carbon::parse($otherStaff->license_expiry)->format('d M Y')); ?>

                        <?php else: ?>
                            <span class="empty-value">Not Provided</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Joining Date</div>
                    <div class="info-value">
                        <?php if($otherStaff->joining_date): ?>
                            <?php echo e(\Carbon\Carbon::parse($otherStaff->joining_date)->format('d M Y')); ?>

                        <?php else: ?>
                            <span class="empty-value">Not Provided</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value">
                        <?php if($otherStaff->status === 'Active'): ?>
                            <span style="color:#047857;font-weight:600;">
                                <i class="fas fa-check-circle"></i>
                                Active
                            </span>
                        <?php else: ?>
                            <span style="color:#b91c1c;font-weight:600;">
                                <i class="fas fa-times-circle"></i>
                                Inactive
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

        </div>


        
        <div class="info-card">

            <div class="card-title">
                <i class="fas fa-clock"></i>
                Record Information
            </div>

            <div class="info-grid">

                <div class="info-item">
                    <div class="info-label">Created At</div>
                    <div class="info-value">
                        <?php if($otherStaff->created_at): ?>
                            <?php echo e($otherStaff->created_at->format('d M Y, h:i A')); ?>

                        <?php else: ?>
                            <span class="empty-value">Not Available</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Last Updated</div>
                    <div class="info-value">
                        <?php if($otherStaff->updated_at): ?>
                            <?php echo e($otherStaff->updated_at->format('d M Y, h:i A')); ?>

                        <?php else: ?>
                            <span class="empty-value">Not Available</span>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Shweta Gundu Mali\Desktop\Student\School-Management-New\resources\views/admin/other-staff/show.blade.php ENDPATH**/ ?>