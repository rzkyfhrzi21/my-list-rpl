<?php
include 'functions/config.php';

$sql_notif = mysqli_query(
    $koneksi,
    "SELECT 
        n.id              AS notif_id,
        n.title,
        n.message,
        n.is_read,
        t.name            AS task_name,
        t.deadline,
        DATEDIFF(DATE(t.deadline), CURDATE()) AS sisa_hari
     FROM notifications n
     JOIN tasks t ON n.task_id = t.id_task
     WHERE n.user_id = '$sesi_id'
       AND n.is_read = 0
       AND t.status = 'belum'
       AND t.deadline IS NOT NULL
       AND DATE(t.deadline) <= CURDATE()
     ORDER BY t.deadline ASC"
);
?>

<?php if (mysqli_num_rows($sql_notif) > 0): ?>
    <?php while ($n = mysqli_fetch_assoc($sql_notif)): ?>

        <?php
        if ($n['sisa_hari'] < 0) {
            $alertClass = 'danger';
        } elseif ($n['sisa_hari'] == 0) {
            $alertClass = 'warning';
        } else {
            $alertClass = 'info';
        }
        ?>

        <div class="card-body mb-3 notif-card"
            data-notif-id="<?= $n['notif_id']; ?>">

            <div class="alert alert-<?= $alertClass; ?> mb-0 position-relative">

                <!-- TOMBOL SILANG -->
                <button type="button"
                    class="btn-close position-absolute top-0 end-0 m-2 notif-close"
                    aria-label="Close">
                </button>

                <h5 class="alert-heading mb-1">
                    <?= htmlspecialchars($n['title']); ?>
                </h5>

                <p class="mb-2">
                    <?= htmlspecialchars($n['message']); ?>
                </p>

                <hr class="my-2">

                <p class="mb-1">
                    <b>📌 Task:</b>
                    <?= htmlspecialchars($n['task_name']); ?>
                </p>

                <p class="mb-0">
                    <b>⏰ Deadline:</b>
                    <?= date('d M Y H:i', strtotime($n['deadline'])); ?>
                </p>

            </div>
        </div>

    <?php endwhile; ?>
<?php endif; ?>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        const STORAGE_KEY = "hidden_notifs";

        // Ambil daftar notif yang disembunyikan
        let hiddenNotifs = JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];

        // Sembunyikan notif yang sudah pernah ditutup
        document.querySelectorAll(".notif-card").forEach(card => {
            const notifId = card.dataset.notifId;
            if (hiddenNotifs.includes(notifId)) {
                card.remove();
            }
        });

        // Event tombol silang
        document.addEventListener("click", function(e) {
            if (e.target.classList.contains("notif-close")) {

                const card = e.target.closest(".notif-card");
                const notifId = card.dataset.notifId;

                // Simpan ke localStorage
                if (!hiddenNotifs.includes(notifId)) {
                    hiddenNotifs.push(notifId);
                    localStorage.setItem(STORAGE_KEY, JSON.stringify(hiddenNotifs));
                }

                // Hilangkan dari UI
                card.remove();
            }
        });

    });
</script>