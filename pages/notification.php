<?php
// =====================================================================
// NOTIF PAGE (FULL) - Pengingat Task
// Tabel: notifications (id, user_id, task_id, title, message, is_read, created_at, updated_at)
// Join reminder dari tabel tasks (id_task, id_user, name, category, priority, status, deadline, reminder, ...)
// =====================================================================

/* Ambil notifikasi + task terkait */
$sqlNotif = mysqli_query(
    $koneksi,
    "SELECT 
        n.id,
        n.task_id,
        n.title,
        n.message,
        n.is_read,
        n.created_at,

        t.name,
        t.category,
        t.priority,
        t.status,
        t.deadline,
        t.reminder

     FROM notifications n
     JOIN tasks t ON n.task_id = t.id_task
     WHERE n.user_id = '$sesi_id'
     ORDER BY n.created_at DESC"
);
?>

<div class="page-heading">
    <div class="page-title">
        <h3>Pengingat Task</h3>
        <p class="text-subtitle text-muted">
            Notifikasi pengingat berdasarkan deadline dan waktu reminder tugas Anda
        </p>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">📢 Notifikasi</h4>
                <button class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modalTambahNotif">
                    <i class="bi bi-plus-circle"></i> Tambah Pengingat
                </button>
            </div>

            <div class="card-body">
                <?php if (!$sqlNotif || mysqli_num_rows($sqlNotif) == 0): ?>
                    <p class="text-muted">Belum ada notifikasi.</p>
                <?php else: ?>
                    <ul class="list-group list-group-flush">

                        <?php while ($n = mysqli_fetch_assoc($sqlNotif)): ?>
                            <?php
                            $now = date('Y-m-d H:i:s');

                            // Badge reminder (ambil dari tasks.reminder)
                            if ($n['reminder'] && $n['reminder'] <= $now) {
                                $badge = 'danger';
                                $label = 'Reminder Terlewat';
                            } elseif ($n['reminder'] && date('Y-m-d', strtotime($n['reminder'])) == date('Y-m-d')) {
                                $badge = 'warning';
                                $label = 'Hari Ini';
                            } else {
                                $badge = 'info';
                                $label = 'Akan Datang';
                            }

                            // Badge priority
                            $priorityBadge = ($n['priority'] == 'tinggi')
                                ? 'danger'
                                : (($n['priority'] == 'sedang') ? 'warning' : 'primary');
                            ?>

                            <li class="list-group-item d-flex justify-content-between align-items-start <?= $n['is_read'] ? '' : 'bg-light'; ?>">

                                <div class="me-3">
                                    <h6 class="mb-1"><?= htmlspecialchars($n['title']); ?></h6>

                                    <small class="text-muted d-block">
                                        <?= htmlspecialchars($n['message']); ?>
                                    </small>

                                    <small class="text-muted d-block mt-1">
                                        📌 Task: <b><?= htmlspecialchars($n['name']); ?></b><br>
                                        📅 Deadline:
                                        <?= $n['deadline'] ? date('d M Y H:i', strtotime($n['deadline'])) : '-'; ?>
                                    </small>

                                    <div class="mt-2">
                                        <span class="badge bg-<?= $badge; ?>"><?= $label; ?></span>

                                        <?php if (!$n['is_read']): ?>
                                            <span class="badge bg-dark">Belum dibaca</span>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- AKSI -->
                                <div class="text-end" style="min-width: 120px;">
                                    <a href="?page=task" class="btn btn-sm btn-outline-primary mb-1">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <button class="btn btn-sm btn-outline-warning mb-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditNotif"
                                        data-edit-notif
                                        data-id="<?= $n['id']; ?>"
                                        data-title="<?= htmlspecialchars($n['title']); ?>"
                                        data-message="<?= htmlspecialchars($n['message']); ?>"
                                        data-read="<?= (int)$n['is_read']; ?>">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <button class="btn btn-sm btn-outline-danger mb-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalDeleteNotif"
                                        data-delete-notif
                                        data-id="<?= $n['id']; ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                    <?php if (!(int)$n['is_read']): ?>
                                        <form method="post" action="functions/function_notification.php" class="d-inline">
                                            <input type="hidden" name="notif_id" value="<?= $n['id']; ?>">
                                            <button type="submit" name="btn_read" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-check2"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>

                            </li>
                        <?php endwhile; ?>

                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<!-- ================= MODAL TAMBAH NOTIFICATION ================= -->
<div class="modal fade" id="modalTambahNotif" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="post" action="functions/function_notification.php" class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Tambah Pengingat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body row g-3">
                <div class="col-md-12">
                    <label>Task Terkait</label>
                    <select name="task_id" class="form-select" required>
                        <option value="">- Pilih Task -</option>
                        <?php
                        $qTask = mysqli_query(
                            $koneksi,
                            "SELECT t.id_task, t.name
                                    FROM tasks t
                                    LEFT JOIN notifications n 
                                        ON n.task_id = t.id_task 
                                        AND n.user_id = '$sesi_id'
                                    WHERE t.id_user = '$sesi_id'
                                    AND n.id IS NULL
                                    ORDER BY t.deadline ASC"
                        );

                        while ($t = mysqli_fetch_assoc($qTask)):
                        ?>
                            <option value="<?= $t['id_task']; ?>">
                                <?= htmlspecialchars($t['name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-md-12">
                    <label>Judul</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="col-md-12">
                    <label>Pesan</label>
                    <textarea name="message" class="form-control" rows="3" required></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" name="btn_add_notif" class="btn btn-primary">
                    Simpan
                </button>
            </div>

        </form>
    </div>
</div>

<!-- ================= MODAL EDIT NOTIFICATION ================= -->
<div class="modal fade" id="modalEditNotif" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="post" action="functions/function_notification.php" class="modal-content">

            <input type="hidden" name="notif_id" id="edit-notif-id">

            <div class="modal-header">
                <h5 class="modal-title">Edit Pengingat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body row g-3">
                <div class="col-md-12">
                    <label>Judul</label>
                    <input type="text" name="title" id="edit-title" class="form-control" required>
                </div>

                <div class="col-md-12">
                    <label>Pesan</label>
                    <textarea name="message" id="edit-message" class="form-control" rows="3" required></textarea>
                </div>

                <div class="col-md-6">
                    <label>Status</label>
                    <select name="is_read" id="edit-is-read" class="form-select">
                        <option value="0">Belum Dibaca</option>
                        <option value="1">Sudah Dibaca</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" name="btn_edit_notif" class="btn btn-primary">
                    Simpan Perubahan
                </button>
            </div>

        </form>
    </div>
</div>

<!-- ================= MODAL DELETE NOTIFICATION ================= -->
<div class="modal fade" id="modalDeleteNotif" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="functions/function_notification.php" class="modal-content">

            <input type="hidden" name="notif_id" id="delete-notif-id">

            <div class="modal-header">
                <h5 class="modal-title text-danger">Hapus Notifikasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p>Yakin ingin menghapus pengingat ini?</p>
            </div>

            <div class="modal-footer">
                <button type="submit" name="btn_delete_notif" class="btn btn-danger">
                    Ya, Hapus
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        // EDIT
        document.querySelectorAll('[data-edit-notif]').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('edit-notif-id').value = this.dataset.id || '';
                document.getElementById('edit-title').value = this.dataset.title || '';
                document.getElementById('edit-message').value = this.dataset.message || '';
                document.getElementById('edit-is-read').value = this.dataset.read || '0';
            });
        });

        // DELETE
        document.querySelectorAll('[data-delete-notif]').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('delete-notif-id').value = this.dataset.id || '';
            });
        });

    });
</script>