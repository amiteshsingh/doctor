<?php
if (isset($res) && count($res) > 0) {
    $i = (!empty($page)) ? ($page - 1) * $page_size : 0;
    foreach ($res as $user) {
?>
<tr>
    <td><?= ++$i ?></td>
    <td><?= htmlspecialchars($user->name) ?></td>
    <td><?= htmlspecialchars($user->phone_no ?? '—') ?></td>
    <td><?= htmlspecialchars($user->email) ?></td>
    <td><?= htmlspecialchars($user->gender ?? '—') ?></td>
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
    <td colspan="6" class="text-center">No record found.</td>
</tr>
<?php } ?>
