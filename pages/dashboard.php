<?php
require_once 'functions/data.php';

$today        = date('Y-m-d');
$hariIniEN    = date('l');
$hariDipilih  = $_GET['hari'] ?? $hariIniEN;

/* TASK HARI INI */
$qHariIni = mysqli_query($koneksi, "
    SELECT *, DATEDIFF(deadline, NOW()) AS sisa_hari
    FROM tasks
    WHERE id_user = '$sesi_id'
      AND DATE(deadline) = '$today'
    ORDER BY deadline ASC
");
$totalHariIni = mysqli_num_rows($qHariIni);

/* DEADLINE TERDEKAT (3 TASK, BELUM LEWAT) */
$qDeadline = mysqli_query($koneksi, "
    SELECT 
        id_task,
        name,
        deadline,
        DATEDIFF(DATE(deadline), CURDATE()) AS sisa_hari
    FROM tasks
    WHERE id_user = '$sesi_id'
      AND status = 'belum'
      AND deadline IS NOT NULL
      AND DATE(deadline) >= CURDATE()
    ORDER BY deadline ASC
    LIMIT 2
");

/* TASK BERDASARKAN HARI */
$qByHari = mysqli_query($koneksi, "
    SELECT *
    FROM tasks
    WHERE id_user = '$sesi_id'
      AND DAYNAME(deadline) = '$hariDipilih'
    ORDER BY deadline ASC
");

?>

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>To Do List</h3>
                <p class="text-subtitle text-muted">
                    Pantau ringkasan tugas harian, deadline terdekat, dan prioritas aktivitas Anda.
                </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index">Dashboard</a></li>
                        <li class="breadcrumb-item active text-capitalize" aria-current="page">
                            <?= $page; ?>
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="row">

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5>📅 Hari Ini</h5>
                        <p class="text-muted"><?= formatTanggalIndonesia($today); ?></p>

                        <?php if ($totalHariIni > 0): ?>
                            <span class="badge bg-success">Ada Tugas</span>
                            <p class="mt-2">
                                Total tugas hari ini:
                                <b><?= $totalHariIni ?></b>
                            </p>
                        <?php else: ?>
                            <span class="badge bg-info">Tidak Ada Tugas</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- DEADLINE TERDEKAT -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5>⏳ Deadline Terdekat</h5>

                        <?php if (mysqli_num_rows($qDeadline) > 0): ?>
                            <ul class="list-unstyled mb-0">
                                <?php while ($d = mysqli_fetch_assoc($qDeadline)): ?>
                                    <li class="mb-2">
                                        <b><?= htmlspecialchars($d['name']); ?></b><br>

                                        <?php if ($d['sisa_hari'] == 0): ?>
                                            <small class="text-danger fw-bold">
                                                Hari ini
                                            </small>
                                        <?php else: ?>
                                            <small class="text-muted">
                                                <?= $d['sisa_hari']; ?> hari lagi
                                            </small>
                                        <?php endif; ?>

                                        <br>
                                        <small class="text-muted">
                                            <?= date('d M Y H:i', strtotime($d['deadline'])); ?>
                                        </small>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">Tidak ada deadline terdekat</p>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <!-- STATISTIK MINI -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body py-3 px-4 pb-4">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-lg">
                                <img src="assets/<?= empty($sesi_img) ? 'static/images/faces/1.jpg' : 'profile/' . htmlspecialchars($sesi_img) ?>">
                                <div class="ms-3 name">
                                    <h5 class="font-bold"><?= $sesi_nama; ?></h5>
                                    <h6 class="text-muted mb-0">@<?= $sesi_id; ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">📌 Tugas Hari Ini</h4>

                <!-- Aksi Cepat -->
                <a href="?page=task" class="btn btn-sm btn-primary">
                    <i class="bi bi-list-check"></i> Lihat Semua Task
                </a>
            </div>
            <div class="card-body">

                <?php if ($totalHariIni == 0): ?>
                    <p class="text-muted">Tidak ada tugas hari ini.</p>
                <?php else: ?>
                    <ul class="list-group">
                        <?php while ($t = mysqli_fetch_assoc($qHariIni)): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-start flex-wrap gap-2">

                                <!-- KIRI -->
                                <div>
                                    <b><?= htmlspecialchars($t['name']); ?></b><br>

                                    <?php if (!empty($t['description'])): ?>
                                        <small class="text-muted d-block">
                                            📝 <?= htmlspecialchars($t['description']); ?>
                                        </small>
                                    <?php endif; ?>

                                    <small class="text-muted d-block">
                                        📅 Deadline :
                                        <?= date('H:i', strtotime($t['deadline'])); ?>
                                    </small>

                                    <?php if (!empty($t['category'])): ?>
                                        <small class="badge bg-info me-1">
                                            <?= htmlspecialchars($t['category']); ?>
                                        </small>
                                    <?php endif; ?>

                                    <small class="badge bg-secondary">
                                        <?= ucfirst($t['priority']); ?>
                                    </small>
                                </div>

                                <!-- KANAN -->
                                <div class="text-end">
                                    <span class="badge bg-<?= $t['status'] == 'selesai' ? 'success' : 'danger'; ?>">
                                        <?= ucfirst($t['status']); ?>
                                    </span>
                                </div>

                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php endif; ?>


            </div>
        </div>
    </section>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4>📆 Cek Tugas Berdasarkan Hari</h4>
            </div>
            <div class="card-body">

                <div class="btn-group mb-3">
                    <?php
                    $hari = ['Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat'];
                    foreach ($hari as $en => $id):
                    ?>
                        <a href="?hari=<?= $en ?>"
                            class="btn <?= $hariDipilih == $en ? 'btn-primary' : 'btn-light' ?>">
                            <?= $id ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <?php if (mysqli_num_rows($qByHari) == 0): ?>
                    <p class="text-muted">Tidak ada tugas pada hari ini.</p>
                <?php else: ?>
                    <ul class="list-group">
                        <?php while ($t = mysqli_fetch_assoc($qByHari)): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-start flex-wrap gap-2">

                                <!-- KIRI -->
                                <div>
                                    <b><?= htmlspecialchars($t['name']); ?></b><br>

                                    <small class="text-muted d-block">
                                        📅 Deadline :
                                        <?= date('d M Y', strtotime($t['deadline'])); ?>
                                        <?= date('H:i', strtotime($t['deadline'])); ?>
                                    </small>

                                    <?php if (!empty($t['reminder'])): ?>
                                        <small class="text-muted d-block">
                                            ⏰ Reminder :
                                            <?= date('d M Y', strtotime($t['reminder'])); ?>
                                            <?= date('H:i', strtotime($t['reminder'])); ?>
                                        </small>
                                    <?php endif; ?>

                                    <small class="badge bg-secondary mt-1">
                                        <?= ucfirst($t['priority']); ?>
                                    </small>
                                </div>

                                <!-- KANAN -->
                                <div class="text-end">
                                    <a href="?page=task"
                                        class="btn btn-sm btn-outline-primary">
                                        Lihat
                                    </a>
                                </div>

                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php endif; ?>

            </div>
        </div>
    </section>


</div>