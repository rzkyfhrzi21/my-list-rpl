<?php
require_once 'functions/data.php';

/* FILTER DARI URL */
$filterStatus   = $_GET['status'] ?? 'semua';
$filterPriority = $_GET['priority'] ?? 'semua';

/* helper URL */
function filterUrl($newStatus = null, $newPriority = null)
{
    $status   = $_GET['status'] ?? 'semua';
    $priority = $_GET['priority'] ?? 'semua';

    if ($newStatus !== null) $status = $newStatus;
    if ($newPriority !== null) $priority = $newPriority;

    return "?status={$status}&priority={$priority}";
}
?>



<section class="section">
    <div class="row">
        <div class="col-6 col-md-6 col-lg-6">
            <div class="btn-group mb-3" role="group">
                <span class="me-2 fw-bold">Status:</span>

                <a href="<?= filterUrl('semua', null) ?>"
                    class="btn <?= $filterStatus == 'semua' ? 'btn-primary' : 'btn-light' ?>">
                    Semua <span class="badge bg-transparent"><?= $hitung_status_semua ?></span>
                </a>

                <a href="<?= filterUrl('belum', null) ?>"
                    class="btn <?= $filterStatus == 'belum' ? 'btn-secondary' : 'btn-light' ?>">
                    Belum <span class="badge bg-transparent"><?= $hitung_status_belum ?></span>
                </a>

                <a href="<?= filterUrl('selesai', null) ?>"
                    class="btn <?= $filterStatus == 'selesai' ? 'btn-success' : 'btn-light' ?>">
                    Selesai <span class="badge bg-transparent"><?= $hitung_status_selesai ?></span>
                </a>
            </div>
        </div>
        <div class="col-6 col-md-6 col-lg-6">
            <div class="btn-group mb-3" role="group">
                <span class="me-2 fw-bold">Priority:</span>

                <a href="<?= filterUrl(null, 'semua') ?>"
                    class="btn <?= $filterPriority == 'semua' ? 'btn-primary' : 'btn-light' ?>">
                    Semua <span class="badge bg-transparent"><?= $hitung_priority_semua ?></span>
                </a>

                <a href="<?= filterUrl(null, 'rendah') ?>"
                    class="btn <?= $filterPriority == 'rendah' ? 'btn-secondary' : 'btn-light' ?>">
                    Rendah <span class="badge bg-transparent"><?= $hitung_priority_rendah ?></span>
                </a>

                <a href="<?= filterUrl(null, 'sedang') ?>"
                    class="btn <?= $filterPriority == 'sedang' ? 'btn-warning' : 'btn-light' ?>">
                    Sedang <span class="badge bg-transparent"><?= $hitung_priority_sedang ?></span>
                </a>

                <a href="<?= filterUrl(null, 'tinggi') ?>"
                    class="btn <?= $filterPriority == 'tinggi' ? 'btn-danger' : 'btn-light' ?>">
                    Tinggi <span class="badge bg-transparent"><?= $hitung_priority_tinggi ?></span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Task App Widget Starts -->
<section class="tasks">

    <div class="row">
        <div class="col-lg-12">
            <div class="card widget-todo">
                <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                    <h4 class="card-name d-flex">
                        <i class="bx bx-check font-medium-5 pl-25 pr-75"></i> Tasks
                    </h4>
                </div>

                <div class="card-body px-0 py-1 overflow-auto">

                    <ul class="widget-todo-list-wrapper" id="widget-todo-list">
                        <?php
                        $sql_query = "SELECT * FROM tasks WHERE id_user='$sesi_id'";

                        if ($filterStatus !== 'semua') {
                            $sql_query .= " AND status='$filterStatus'";
                        }

                        if ($filterPriority !== 'semua') {
                            $sql_query .= " AND priority='$filterPriority'";
                        }

                        $sql_query .= " ORDER BY deadline ASC";


                        $data = mysqli_query($koneksi, $sql_query);

                        while ($task = mysqli_fetch_assoc($data)) :

                            $taskId       = $task['id_task'];
                            $taskName     = $task['name'];
                            $taskCategory = $task['category'];
                            $taskStatus   = $task['status'];
                            $taskPriority = $task['priority'];
                            $taskDeadline     = $task['deadline'];
                            $taskReminder     = $task['reminder'];
                            $taskDescription  = $task['description'];

                            // status badge
                            switch ($taskStatus) {
                                case 'belum':
                                    $statusText = 'Belum';
                                    $statusIcon = 'bi-bookmark-dash-fill';
                                    $bgStatus   = 'bg-danger';
                                    break;
                                case 'selesai':
                                    $statusText = 'Selesai';
                                    $statusIcon = 'bi-bookmark-check-fill';
                                    $bgStatus   = 'bg-success';
                                    break;
                                default:
                                    $statusText = '';
                                    $statusIcon = '';
                                    $bgStatus   = '';
                            }

                            // priority badge
                            switch ($taskPriority) {
                                case 'rendah':
                                    $priorityText = 'Rendah';
                                    $bgPriority   = 'bg-primary';
                                    break;
                                case 'sedang':
                                    $priorityText = 'Sedang';
                                    $bgPriority   = 'bg-warning';
                                    break;
                                case 'tinggi':
                                    $priorityText = 'Tinggi';
                                    $bgPriority   = 'bg-danger';
                                    break;
                                default:
                                    $priorityText = '';
                                    $bgPriority   = '';
                            }

                        ?>

                            <!-- ITEM -->
                            <li class="widget-todo-item <?= $taskStatus === 'selesai' ? 'completed' : '' ?>">
                                <div class="widget-todo-name-wrapper d-flex justify-content-between align-items-center">

                                    <!-- KIRI -->
                                    <div class="widget-todo-name-area d-flex align-items-start gap-2">

                                        <input
                                            type="checkbox"
                                            class="form-check-input task-checkbox"
                                            data-id="<?= $taskId ?>"
                                            data-status="<?= $taskStatus ?>"
                                            <?= $taskStatus === 'selesai' ? 'checked' : '' ?>>

                                        <div class="d-flex flex-column">
                                            <label for="task-1" class="widget-todo-name fw-bold mb-25">
                                                <?= $taskName; ?>
                                            </label>

                                            <div class="d-flex flex-wrap align-items-center gap-2 text-muted small">

                                                <span class="badge bg-success"><?= $taskCategory; ?></span>

                                                <span class="d-flex align-items-center gap-2">
                                                    <i class="bi bi-calendar2-date"></i>
                                                    <?= $taskDeadline; ?>
                                                </span>

                                                <span class="d-flex align-items-center gap-2">
                                                    <i class="bi bi-clock-fill"></i>
                                                    <?= $taskReminder; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- KANAN -->
                                    <div class="widget-todo-item-action d-flex align-items-center gap-2">

                                        <span class="badge <?= $bgPriority; ?>">
                                            <i class="bi bi-bell-fill"></i>
                                            <?= ucfirst($taskPriority); ?>
                                        </span>

                                        <span class="badge <?= $bgStatus; ?>">
                                            <i class="bi <?= $statusIcon; ?>"></i>
                                            <?= ucfirst($taskStatus); ?>
                                        </span>

                                        <div class="btn-group" role="group" aria-label="Basic example">

                                            <!-- EDIT -->
                                            <button
                                                class="btn icon icon-left btn-light"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalEditTask"
                                                data-id="<?= $taskId; ?>"
                                                data-name="<?= $taskName; ?>"
                                                data-description="<?= $taskDescription; ?>"
                                                data-category="<?= $taskCategory; ?>"
                                                data-priority="<?= $taskPriority; ?>"
                                                data-status="<?= $taskStatus; ?>"
                                                data-deadline="<?= $taskDeadline; ?>"
                                                data-reminder="<?= $taskReminder; ?>">
                                                <i class="bi bi-pencil-fill"></i>
                                                DETAIL
                                            </button>

                                            <!-- DELETE -->
                                            <button
                                                class="btn btn-light btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalDeleteTask"
                                                data-id="<?= $taskId; ?>">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    </div>
                            </li>
                        <?php endwhile ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="modalEditTask" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form method="post" action="function_task.php" class="modal-content">

            <div class="modal-header">
                <h5 class="modal-name">Detail Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" name="id" id="edit-id">

                <div class="mb-3">
                    <label class="form-label">Judul Task</label>
                    <input type="text" class="form-control" name="name" id="edit-name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" name="description" id="edit-description" rows="3"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Kategori</label>
                        <input type="text" class="form-control" name="category" id="edit-category">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Prioritas</label>
                        <select class="form-select" name="priority" id="edit-priority">
                            <option value="rendah">Rendah</option>
                            <option value="sedang">Sedang</option>
                            <option value="tinggi">Tinggi</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Deadline</label>
                        <input type="datetime-local" class="form-control" name="deadline" id="edit-deadline">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Reminder</label>
                        <input type="datetime-local" class="form-control" name="reminder" id="edit-reminder">
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="submit" name="btn_edit" class="btn btn-primary">Simpan Perubahan</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>

        </form>
    </div>
</div>

<div class="modal fade" id="modalDeleteTask" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" action="function_task.php" class="modal-content">

            <div class="modal-header">
                <h5 class="modal-name text-danger">Hapus Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" name="id" id="delete-id">

                <p>Apakah Anda yakin ingin menghapus task ini?</p>
            </div>

            <div class="modal-footer">
                <button type="submit" name="btn_delete" class="btn btn-danger">Ya, Hapus</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>

        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        // =============================
        // EDIT MODAL
        // =============================
        document.querySelectorAll('[data-bs-target="#modalEditTask"]').forEach(btn => {
            btn.addEventListener("click", function() {
                document.getElementById("edit-id").value = this.dataset.id;
                document.getElementById("edit-name").value = this.dataset.name;
                document.getElementById("edit-category").value = this.dataset.category;
                document.getElementById("edit-priority").value = this.dataset.priority;
                document.getElementById("edit-deadline").value = (this.dataset.deadline || '').replace(" ", "T");
                document.getElementById("edit-reminder").value = (this.dataset.reminder || '').replace(" ", "T");
                document.getElementById("edit-description").value = this.dataset.description;
            });
        });

        // =============================
        // DELETE MODAL
        // =============================
        document.querySelectorAll('[data-bs-target="#modalDeleteTask"]').forEach(btn => {
            btn.addEventListener("click", function() {
                document.getElementById("delete-id").value = this.dataset.id;
            });
        });

        // =============================
        // CHECKBOX AJAX TOGGLE STATUS
        // =============================
        document.addEventListener("change", function(e) {
            if (!e.target.classList.contains("task-checkbox")) return;

            const checkbox = e.target;
            const taskId = checkbox.dataset.id;
            const oldStatus = checkbox.dataset.status;
            const newStatus = checkbox.checked ? "selesai" : "belum";

            fetch("../functions/ajax_task.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `action=toggle_status&id=${encodeURIComponent(taskId)}&status=${encodeURIComponent(newStatus)}`
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        checkbox.checked = (oldStatus === "selesai");
                        alert(data.message || "Gagal mengubah status");
                        return;
                    }

                    // update data-status
                    checkbox.dataset.status = newStatus;

                    // efek visual
                    const item = checkbox.closest(".widget-todo-item");
                    if (item) {
                        if (newStatus === "selesai") {
                            item.classList.add("completed");
                        } else {
                            item.classList.remove("completed");
                        }
                    }
                })
                .catch(() => {
                    checkbox.checked = (oldStatus === "selesai");
                    alert("Koneksi gagal");
                });
        });

    });
</script>