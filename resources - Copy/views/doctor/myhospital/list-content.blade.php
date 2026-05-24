<?php
if (isset($res) && count($res) > 0) {
    $i = (!empty($page)) ? ($page - 1) * $page_size : 0;
    foreach ($res as $hospital) {
        $initial = strtoupper(substr($hospital->name, 0, 1));
?>
<tr>
    <td style="font-weight:600;color:#888;font-size:13px;">{{ ++$i }}</td>

    <td>
        <div class="hosp-name-cell">
            <div class="hosp-avatar">{{ $initial }}</div>
            <div>
                <div style="font-weight:700;color:#1a1a2e;font-size:13.5px;"><?= htmlspecialchars($hospital->name) ?></div>
                <div style="font-size:11px;color:#888;"><?= htmlspecialchars($hospital->email ?? '') ?></div>
            </div>
        </div>
    </td>

    <td>
        <span style="font-size:13px;">
            <i class="fa fa-phone" style="color:#0a6ebd;margin-right:5px;font-size:11px;"></i>
            <?= htmlspecialchars($hospital->phone_no ?? '—') ?>
        </span>
    </td>

    <td>
        <span style="font-size:13px;">
            <i class="fa fa-map-marker" style="color:#f472b6;margin-right:5px;font-size:11px;"></i>
            <?= htmlspecialchars($hospital->city ?? '—') ?>
        </span>
    </td>

    <td style="font-size:13px;"><?= htmlspecialchars($hospital->state ?? '—') ?></td>

    <td>
        <span style="background:#f0f4ff;color:#0a6ebd;border-radius:8px;padding:3px 10px;font-size:12px;font-weight:600;">
            <?= htmlspecialchars($hospital->zip_code ?? '—') ?>
        </span>
    </td>

    <td>
        <?php if ($hospital->status == 1): ?>
            <span class="s-badge s-active">✅ Active</span>
        <?php else: ?>
            <span class="s-badge s-inactive">❌ Inactive</span>
        <?php endif; ?>
    </td>

    <td>
        <?php if ($hospital->approval_status == 1): ?>
            <span class="s-badge s-approved">✔ Approved</span>
        <?php elseif ($hospital->approval_status == 2): ?>
            <span class="s-badge s-block">🚫 Block</span>
        <?php else: ?>
            <span class="s-badge s-pending">⏳ Pending</span>
        <?php endif; ?>
    </td>

    <td class="text-right">
        <div class="dropdown dropdown-action">
            <button class="action-btn dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-ellipsis-v"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right" style="border-radius:10px;border:1px solid #e2e8f0;box-shadow:0 8px 24px rgba(0,0,0,.1);min-width:140px;">
                <a class="dropdown-item" href="{{ route('doctor.myhospital.add') }}?id=<?= $hospital->id ?>"
                   style="font-size:13px;padding:9px 16px;">
                    <i class="fa fa-pencil mr-2" style="color:#0a6ebd;"></i> Edit
                </a>
                <a class="dropdown-item text-danger" href="#"
                   data-toggle="modal" data-target="#delete_expense"
                   data-url="/doctor/myhospital/delete/<?= $hospital->id ?>"
                   style="font-size:13px;padding:9px 16px;">
                    <i class="fa fa-trash-o mr-2"></i> Delete
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
    <td colspan="9" class="text-center" style="padding:40px;color:#aaa;">
        <i class="fa fa-hospital-o fa-3x mb-3" style="display:block;color:#d0e4ff;"></i>
        No hospitals found. <a href="{{ route('doctor.myhospital.add') }}">Add your first hospital</a>
    </td>
</tr>
<?php } ?>
