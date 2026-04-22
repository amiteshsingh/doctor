<?php

$colors = ['', 'green', 'pink', 'blue', 'orange'];

if(isset($res) && count($res) > 0) {
    $i = 0;
    if(!empty($page)) $i = ($page - 1) * $page_size;

    foreach($res as $idx => $doctor) {
        $color = $colors[$idx % count($colors)];

        $profileImage = isset($doctor->profile_pic) && file_exists(public_path('storage/upload/doctor/'.$doctor->profile_pic))
            ? asset('storage/upload/doctor/'.$doctor->profile_pic)
            : asset('storage/upload/doctor/default.jpg');

        $delay = ($idx % 8) * 0.07;
?>

<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4 doc-card-wrap" style="animation-delay:<?= $delay ?>s;">
    <div class="doc-card" style="animation-delay:<?= $delay ?>s;">

        <!-- Banner -->
        <div class="card-banner <?= $color ?> position-relative">
            <img src="<?= $profileImage ?>" alt="<?= htmlspecialchars($doctor->name) ?>" class="doc-avatar">

            <!-- Dropdown -->
            <div class="dropdown" style="position:absolute;top:10px;right:10px;">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"
                   style="width:28px;height:28px;background:rgba(255,255,255,0.25);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:13px;">
                    <i class="fa fa-ellipsis-v"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="{{ route('doctor.mydoctor.add') }}?id=<?= $doctor->id ?>">
                        <i class="fa fa-pencil mr-2 text-primary"></i> Edit
                    </a>
                    <a class="dropdown-item" href="{{ route('doctor.mydoctor.profile', ['id' => $doctor->id]) }}">
                        <i class="fa fa-eye mr-2 text-success"></i> View Profile
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="#"
                       data-toggle="modal" data-target="#delete_expense"
                       data-url="/doctor/mydoctor/delete/<?= $doctor->id ?>">
                        <i class="fa fa-trash-o mr-2"></i> Delete
                    </a>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="card-body">
            <div class="doc-name"><?= htmlspecialchars($doctor->name) ?></div>
            <div class="doc-spec"><?php echo get_specialization($doctor->id); ?></div>
            <div class="doc-loc mb-2">
                <i class="fa fa-map-marker mr-1"></i><?php echo get_location($doctor->id); ?>
            </div>

            <!-- Badges -->
            <div class="d-flex justify-content-center flex-wrap" style="gap:4px;">
                <?php if($doctor->status == 1): ?>
                    <span class="badge-pill-custom badge-active">Active</span>
                <?php else: ?>
                    <span class="badge-pill-custom badge-inactive">Inactive</span>
                <?php endif; ?>

                <?php if($doctor->approval_status == 1): ?>
                    <span class="badge-pill-custom badge-approved">Approved</span>
                <?php elseif($doctor->approval_status == 2): ?>
                    <span class="badge-pill-custom badge-blocked">Blocked</span>
                <?php else: ?>
                    <span class="badge-pill-custom badge-pending" title="Pending admin approval">Pending</span>
                <?php endif; ?>
            </div>

            <!-- Action Buttons -->
            <div class="doc-actions mt-3">
                <a href="{{ route('doctor.mydoctor.add') }}?id=<?= $doctor->id ?>" class="btn-edit" title="Edit">
                    <i class="fa fa-pencil"></i>
                </a>
                <a href="{{ route('doctor.mydoctor.profile', ['id' => $doctor->id]) }}" class="btn-view" title="View Profile">
                    <i class="fa fa-eye"></i>
                </a>
                <a href="#" class="btn-delete" title="Delete"
                   data-toggle="modal" data-target="#delete_expense"
                   data-url="/doctor/mydoctor/delete/<?= $doctor->id ?>">
                    <i class="fa fa-trash"></i>
                </a>
            </div>
        </div>

    </div>
</div>

<?php
    }
} else {
?>
<div class="col-12 text-center py-5">
    <div style="opacity:0.4;">
        <i class="fa fa-user-md" style="font-size:60px;color:#667eea;"></i>
        <p class="mt-3 text-muted">No doctors added yet.</p>
        <a href="{{ route('doctor.mydoctor.add') }}" class="btn btn-primary btn-rounded mt-2">
            <i class="fa fa-plus mr-1"></i> Add First Doctor
        </a>
    </div>
</div>
<?php } ?>
