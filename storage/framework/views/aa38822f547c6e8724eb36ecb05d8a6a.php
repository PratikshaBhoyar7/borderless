<?php $__env->startSection('page-title', 'Patient Details - ' . $patient->serial_number); ?>

<?php $__env->startSection('admin-content'); ?>

<!-- Header -->
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
    <h2 style="margin: 0; color: #2e59a7;">
        <i class="bi bi-person-fill"></i> Patient Details
    </h2>
    <div style="display: flex; gap: 10px;">
        <a href="<?php echo e(route('admin.patients.edit', $patient)); ?>" class="btn btn-primary">
            <i class="bi bi-pencil-fill"></i> Edit
        </a>
        <a href="<?php echo e(route('admin.patients.index')); ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="table-container">
    <!-- Basic Information Card -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-person-vcard"></i> Basic Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label style="font-weight: 600; color: #666;">Serial Number</label>
                    <p style="font-size: 1.1em; color: #333;"><?php echo e($patient->serial_number); ?></p>
                </div>
                <div class="col-md-3 mb-3">
                    <label style="font-weight: 600; color: #666;">Patient Name</label>
                    <p style="font-size: 1.1em; color: #333;"><?php echo e($patient->patient_name); ?></p>
                </div>
                <div class="col-md-2 mb-3">
                    <label style="font-weight: 600; color: #666;">Age</label>
                    <p style="font-size: 1.1em; color: #333;"><?php echo e($patient->age); ?> years</p>
                </div>
                <div class="col-md-2 mb-3">
                    <label style="font-weight: 600; color: #666;">Sex</label>
                    <p style="font-size: 1.1em; color: #333;"><?php echo e($patient->sex); ?></p>
                </div>
                <div class="col-md-2 mb-3">
                    <label style="font-weight: 600; color: #666;">Date</label>
                    <p style="font-size: 1.1em; color: #333;"><?php echo e($patient->date->format('M d, Y')); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Campaign Type Card -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-collection"></i> Campaign Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Campaign Type</label>
                    <?php if($patient->campaignType): ?>
                        <p style="font-size: 1.1em; color: #333;"><?php echo e($patient->campaignType->name); ?></p>
                    <?php else: ?>
                        <p style="color: #999;"><em>No campaign assigned</em></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Location Details Card -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-map"></i> Location Details</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Village</label>
                    <p><?php echo e($patient->village); ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Taluka</label>
                    <p><?php echo e($patient->taluka?->name ?? 'N/A'); ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">District</label>
                    <p><?php echo e($patient->district?->name ?? 'N/A'); ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">State</label>
                    <p><?php echo e($patient->state?->name ?? 'N/A'); ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Country</label>
                    <p><?php echo e($patient->country?->name ?? 'N/A'); ?></p>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Mobile Number</label>
                    <p><?php echo e($patient->mobile); ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Aadhar Number</label>
                    <p><?php echo e($patient->aadhar ?? 'N/A'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Vital Signs Card -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-white">
            <h5 class="mb-0"><i class="bi bi-heart-pulse"></i> Vital Signs</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label style="font-weight: 600; color: #666;">Height</label>
                    <p><?php echo e($patient->height ? $patient->height . ' cm' : 'N/A'); ?></p>
                </div>
                <div class="col-md-3 mb-3">
                    <label style="font-weight: 600; color: #666;">Weight</label>
                    <p><?php echo e($patient->weight ? $patient->weight . ' kg' : 'N/A'); ?></p>
                </div>
                <div class="col-md-3 mb-3">
                    <label style="font-weight: 600; color: #666;">Blood Pressure</label>
                    <p><?php echo e($patient->bp ?? 'N/A'); ?></p>
                </div>
                <div class="col-md-3 mb-3">
                    <label style="font-weight: 600; color: #666;">Hemoglobin</label>
                    <p><?php echo e($patient->hb ? $patient->hb . ' g/dL' : 'N/A'); ?></p>
                </div>
                <div class="col-md-3 mb-3">
                    <label style="font-weight: 600; color: #666;">RBS</label>
                    <p><?php echo e($patient->rbs ? $patient->rbs . ' mg/dL' : 'N/A'); ?></p>
                </div>
                <div class="col-md-3 mb-3">
                    <label style="font-weight: 600; color: #666;">BSL</label>
                    <p><?php echo e($patient->bsl ? $patient->bsl . ' mg/dL' : 'N/A'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Clinical Information Card -->
    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0"><i class="bi bi-stethoscope"></i> Clinical Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Chief Complaints</label>
                    <p><?php echo e($patient->complaints); ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Known Conditions</label>
                    <p><?php echo e($patient->known_conditions ?? 'N/A'); ?></p>
                </div>
                <div class="col-12 mb-3">
                    <label style="font-weight: 600; color: #666;">Diagnosis</label>
                    <p><?php echo e($patient->diagnosis); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Treatment Card -->
    <div class="card mb-4">
        <div class="card-header" style="background-color: #6f42c1; color: white;">
            <h5 class="mb-0"><i class="bi bi-pill"></i> Treatment</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Treatment</label>
                    <p><?php echo e($patient->treatment); ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Dosage</label>
                    <p><?php echo e($patient->dosage); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lab Tests & Referral Card -->
    <div class="card mb-4">
        <div class="card-header" style="background-color: #20c997; color: white;">
            <h5 class="mb-0"><i class="bi bi-flask"></i> Lab Tests & Referral</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Lab Tests</label>
                    <?php if($patient->lab_tests): ?>
                        <p>
                            <?php $__currentLoopData = $patient->lab_tests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-info"><?php echo e($test); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </p>
                    <?php else: ?>
                        <p>N/A</p>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Sample Collected</label>
                    <p><?php echo e($patient->sample_collected ?? 'N/A'); ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Referral Type</label>
                    <p><?php echo e($patient->referral_type ?? 'N/A'); ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Referral Details</label>
                    <p><?php echo e($patient->referral_details ?? 'N/A'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes Card -->
    <?php if($patient->notes): ?>
    <div class="card mb-4">
        <div class="card-header" style="background-color: #fd7e14; color: white;">
            <h5 class="mb-0"><i class="bi bi-sticky"></i> Additional Notes</h5>
        </div>
        <div class="card-body">
            <p><?php echo e($patient->notes); ?></p>
        </div>
    </div>
    <?php endif; ?>

    <!-- Metadata Card -->
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="bi bi-info-circle"></i> Record Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Created By</label>
                    <p><?php echo e($patient->createdBy->name); ?> (<?php echo e($patient->createdBy->email); ?>)</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Created At</label>
                    <p><?php echo e($patient->created_at->format('M d, Y H:i:s')); ?></p>
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Last Updated</label>
                    <p><?php echo e($patient->updated_at->format('M d, Y H:i:s')); ?></p>
                </div>
                <?php if($patient->deleted_at): ?>
                <div class="col-md-6 mb-3">
                    <label style="font-weight: 600; color: #666;">Deleted At</label>
                    <p><?php echo e($patient->deleted_at->format('M d, Y H:i:s')); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-4 d-flex gap-2">
        <a href="<?php echo e(route('admin.patients.edit', $patient)); ?>" class="btn btn-primary">
            <i class="bi bi-pencil-fill"></i> Edit Patient
        </a>
        <a href="<?php echo e(route('admin.patients.index')); ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u756840116/domains/pink-spider-206461.hostingersite.com/public_html/resources/views/admin/patients/show.blade.php ENDPATH**/ ?>