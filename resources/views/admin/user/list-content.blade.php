<?php
if (isset($res) && count($res) > 0) {
    $i = (!empty($page)) ? ($page - 1) * $page_size : 0;
    foreach ($res as $user) {
        $today     = new DateTime();
        $hasMem    = !empty($user->membership_subscription_end_date);
        $isActive  = $hasMem && new DateTime($user->membership_subscription_end_date) >= $today;
        $status    = $hasMem ? ($isActive ? 'active' : 'expired') : 'none';
?>
<tr>
    <td><?= ++$i ?></td>
    <td>
        <strong><?= htmlspecialchars($user->name) ?></strong><br>
        <small style="color:#888;"><?= htmlspecialchars($user->phone_no ?? '—') ?></small>
    </td>
    <td style="font-size:13px;"><?= htmlspecialchars($user->email) ?></td>

    {{-- Membership Amount --}}
    <td>
        <?php if ($hasMem): ?>
            <span style="font-weight:700;color:#0a6ebd;font-size:13px;">
                ₹<?= number_format($user->membership_amount, 2) ?>
            </span><br>
            <?php if ($status === 'active'): ?>
                <span style="background:#e6fff5;color:#00b074;border:1px solid #b3f0d8;border-radius:12px;padding:2px 8px;font-size:11px;font-weight:600;">
                    ✅ Active
                </span>
            <?php else: ?>
                <span style="background:#fff8e6;color:#f59e0b;border:1px solid #fde68a;border-radius:12px;padding:2px 8px;font-size:11px;font-weight:600;">
                    ⚠️ Expired
                </span>
            <?php endif; ?>
        <?php else: ?>
            <span style="background:#f1f5f9;color:#94a3b8;border-radius:12px;padding:2px 8px;font-size:11px;font-weight:600;">
                🔒 None
            </span>
        <?php endif; ?>
    </td>

    {{-- Start Date --}}
    <td style="font-size:12px;color:#555;">
        <?php if ($hasMem): ?>
            <i class="fa fa-calendar" style="color:#0a6ebd;"></i>
            <?= date('d M Y', strtotime($user->membership_subscription_date)) ?>
        <?php else: echo '—'; endif; ?>
    </td>

    {{-- End Date --}}
    <td style="font-size:12px;">
        <?php if ($hasMem): ?>
            <span style="color:<?= $isActive ? '#00b074' : '#ef4444' ?>;">
                <i class="fa fa-calendar-check-o"></i>
                <?= date('d M Y', strtotime($user->membership_subscription_end_date)) ?>
            </span>
        <?php else: echo '—'; endif; ?>
    </td>

    {{-- Updated --}}
    <td>
        <span style="font-size:12px;color:#555;">
            <?= $user->updated_at ? date('d M Y', strtotime($user->updated_at)) : '—' ?>
        </span><br>
        <span style="font-size:11px;color:#999;">
            <?= $user->updated_at ? date('h:i A', strtotime($user->updated_at)) : '' ?>
        </span>
    </td>

    <td class="text-right">
        <div class="dropdown dropdown-action">
            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-ellipsis-v"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="/admin/user/view/<?= $user->id ?>">
                    <i class="fa fa-eye m-r-5"></i> View
                </a>
                <a class="dropdown-item" href="{{ route('admin.user.add') }}?id=<?= $user->id ?>">
                    <i class="fa fa-pencil m-r-5"></i> Edit
                </a>
                <a class="dropdown-item text-danger" href="#"
                   data-toggle="modal" data-target="#delete_user_modal"
                   data-url="/admin/user/delete/<?= $user->id ?>">
                    <i class="fa fa-trash-o m-r-5"></i> Delete
                </a>
            </div>
        </div>
    </td>
</tr>
<?php
    }
} else {
?>
<tr>
    <td colspan="9" class="text-center">No record found.</td>
</tr>
<?php } ?>
