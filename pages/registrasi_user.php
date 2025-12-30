<?php
$name                   = $_SESSION['form_data']['name'] ?? '';
$email                  = $_SESSION['form_data']['email'] ?? '';
$password               = $_SESSION['form_data']['password'] ?? '';
$konfirmasi_password    = $_SESSION['form_data']['konfirmasi_password'] ?? '';

unset($_SESSION['form_data']);
?>

<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Registrasi Pengguna</h4>
        </div>

        <form action="functions/function_user.php" method="post" enctype="multipart/form-data" data-parsley-validate>
            <div class="card-body">

                <div class="row">
                    <!-- NAMA -->
                    <div class="col-md-6 mt-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text"
                            name="name"
                            class="form-control"
                            value="<?= htmlspecialchars($name); ?>"
                            placeholder="Nama lengkap"
                            required>
                    </div>

                    <!-- EMAIL -->
                    <div class="col-md-6 mt-3">
                        <label class="form-label">Email</label>
                        <input type="email"
                            name="email"
                            class="form-control"
                            value="<?= htmlspecialchars($email); ?>"
                            placeholder="email@example.com"
                            required>
                    </div>

                    <!-- PASSWORD -->
                    <div class="col-md-6 mt-3">
                        <label class="form-label">Password</label>
                        <input type="password"
                            name="password"
                            class="form-control"
                            minlength="5"
                            placeholder="***"
                            required>
                    </div>

                    <!-- KONFIRMASI -->
                    <div class="col-md-6 mt-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password"
                            name="konfirmasi_password"
                            class="form-control"
                            minlength="5"
                            placeholder="***"
                            required>
                    </div>
                </div>

                <!-- SYARAT -->
                <div class="form-check mt-4">
                    <input class="form-check-input"
                        type="checkbox"
                        name="setuju"
                        id="setuju"
                        required
                        data-parsley-required="true"
                        data-parsley-errors-container="#error-setuju">
                    <label class="form-check-label" for="setuju">
                        Saya menyetujui syarat dan ketentuan yang berlaku
                    </label>
                </div>
                <div id="error-setuju" class="text-danger small mt-1"></div>

                <!-- BUTTON -->
                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit" name="btn_userregister" class="btn btn-primary">
                        Simpan Data
                    </button>
                </div>

            </div>
        </form>
    </div>
</section>