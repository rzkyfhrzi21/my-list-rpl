<?php
require_once 'functions/function_statistik.php';
?>

<div class="page-heading">
    <h3>Statistik To-Do List</h3>
    <p class="text-subtitle text-muted">
        Ringkasan jumlah tugas, status penyelesaian, dan aktivitas Anda.
    </p>
</div>
<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-12">
            <div class="row">
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-12 d-flex justify-content-start">
                                    <div class="stats-icon purple mb-2">
                                        <i class="bi bi-list-task"></i>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <h6 class="text-muted font-semibold">Total Task</h6>
                                    <h6 class="font-extrabold mb-0"><?= $totalTask; ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-12 d-flex justify-content-start">
                                    <div class="stats-icon green mb-2">
                                        <i class="bi bi-check-circle-fill"></i>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <h6 class="text-muted font-semibold">Task Selesai</h6>
                                    <h6 class="font-extrabold mb-0"><?= $totalSelesai; ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-12 d-flex justify-content-start">
                                    <div class="stats-icon red mb-2">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <h6 class="text-muted font-semibold">Belum Selesai</h6>
                                    <h6 class="font-extrabold mb-0"><?= $totalBelum; ?></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 col-md-6">
                    <div class="card">
                        <div class="card-body px-4 py-4-5">
                            <div class="row">
                                <div class="col-12 d-flex justify-content-start">
                                    <div class="stats-icon orange mb-2">
                                        <i class="bi bi-alarm-fill"></i>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <h6 class="text-muted font-semibold">Deadline Hari Ini</h6>
                                    <h6 class="font-extrabold mb-0"><?= $deadlineHariIni; ?></h6>
                                    <small class="text-danger">
                                        Terlewat: <?= $deadlineLewat; ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <!-- <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Profile Visit</h4>
                        </div>
                        <div class="card-body">
                            <div id="chart-profile-visit"></div>
                        </div>
                    </div>
                </div> -->
            </div>

            <div class="row">
                <!-- <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Line Chart</h4>
                        </div>
                        <div class="card-body">
                            <div id="line"></div>
                        </div>
                    </div>
                </div> -->
                <!-- <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Bar Chart</h4>
                        </div>
                        <div class="card-body">
                            <div id="bar"></div>
                        </div>
                    </div>
                </div> -->
            </div>

            <div class="row">
                <div class="col-12 col-xl-10">
                    <div class="card">
                        <div class="card-header">
                            <h4>🕒 Task Terbaru</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-lg">
                                    <thead>
                                        <tr>
                                            <th>Nama Task</th>
                                            <th>Deadline</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $qTask = mysqli_query($koneksi, "
                                            SELECT name, deadline, status
                                            FROM tasks
                                            WHERE id_user = '$sesi_id'
                                            ORDER BY created_at DESC
                                            LIMIT 5
                                        ");

                                        while ($t = mysqli_fetch_assoc($qTask)):
                                        ?>
                                            <tr>
                                                <td><?= $t['name']; ?></td>
                                                <td><?= date('d M Y H:i', strtotime($t['deadline'])); ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $t['status'] == 'selesai' ? 'success' : 'danger'; ?>">
                                                        <?= ucfirst($t['status']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

<script>
    // Mengambil data dari PHP
    let totalLakiLaki = <?= $totalPenggunaLakiLaki; ?>; // Menggunakan data dari PHP
    let totalPerempuan = <?= $totalPenggunaPerempuan; ?>; // Menggunakan data dari PHP

    let optionsVisitorsProfile = {
        series: [totalLakiLaki, totalPerempuan], // Menggunakan variabel yang diambil dari PHP
        labels: ["Laki-laki", "Perempuan"],
        colors: ["#435ebe", "#55c6e8"],
        chart: {
            type: "donut",
            width: "100%",
            height: "350px",
        },
        legend: {
            position: "bottom",
        },
        plotOptions: {
            pie: {
                donut: {
                    size: "30%",
                },
            },
        },
    };

    var chartVisitorsProfile = new ApexCharts(
        document.getElementById("chart-visitors-profile"),
        optionsVisitorsProfile
    );
    chartVisitorsProfile.render();
</script>