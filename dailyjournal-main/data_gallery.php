<?php
include "koneksi.php";

$hlm = (isset($_POST['hlm']) && is_numeric($_POST['hlm']) && $_POST['hlm'] > 0) ? (int)$_POST['hlm'] : 1;
$limit = 4;
$limit_start = ($hlm - 1) * $limit;

// menajga agar limit start tidak negatif
if ($limit_start < 0) {
    $limit_start = 0;
}

$no = $limit_start + 1;

// ambil gallery data
$sql2 = "SELECT * FROM gallery ORDER BY id DESC LIMIT $limit_start, $limit";
$hasil2 = $conn->query($sql2);
?>

<table class="table table-hover">
    <thead class="table-dark">
        <tr>
            <th>No</th>
            <th>Gambar</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($hasil2->num_rows > 0) {
            while ($row = $hasil2->fetch_assoc()) {
        ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td>
                        <?php
                        if ($row["gambar"] != '' && file_exists('img/' . $row["gambar"])) {
                        ?>
                            <img src="img/<?= $row["gambar"] ?>" width="100">
                        <?php
                        }
                        ?>
                    </td>
                    <td>
                        <a href="#" title="delete" class="badge rounded-pill text-bg-info" data-bs-toggle="modal" data-bs-target="#modalHapus<?= $row["id"] ?>"><i class="bi bi-x-circle"></i></a>

                        <!-- Modal Hapus -->
                        <div class="modal fade" id="modalHapus<?= $row["id"] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Konfirmasi Hapus Gambar</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form method="post" action="">
                                        <div class="modal-body">
                                            <p>Yakin ingin menghapus gambar ini?</p>
                                            <input type="hidden" name="id" value="<?= $row["id"] ?>">
                                            <input type="hidden" name="gambar" value="<?= $row["gambar"] ?>">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <input type="submit" value="Hapus" name="hapus" class="btn btn-danger">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
        <?php
            }
        } else {
            echo '<tr><td colspan="3">Tidak ada gambar tersedia.</td></tr>';
        }
        ?>
    </tbody>
</table>

<?php
// Mengambil banyak gallery untuk pagination
$sql1 = "SELECT COUNT(*) AS total FROM gallery";
$hasil1 = $conn->query($sql1);
$row = $hasil1->fetch_assoc();
$total_records = $row['total'];

if ($total_records > 0) {
    $jumlah_page = ceil($total_records / $limit);
    $jumlah_number = 1; // Angka page untuk menunjukan setelah dan sebelum page saat ini
    $start_number = ($hlm > $jumlah_number) ? $hlm - $jumlah_number : 1;
    $end_number = ($hlm < ($jumlah_page - $jumlah_number)) ? $hlm + $jumlah_number : $jumlah_page;

    echo '<nav class="mb-2"><ul class="pagination justify-content-end">';

    if ($hlm == 1) {
        echo '<li class="page-item disabled"><a class="page-link" href="#">First</a></li>';
        echo '<li class="page-item disabled"><a class="page-link" href="#"><span aria-hidden="true">&laquo;</span></a></li>';
    } else {
        $link_prev = $hlm - 1;
        echo '<li class="page-item halaman" id="1"><a class="page-link" href="#">First</a></li>';
        echo '<li class="page-item halaman" id="' . $link_prev . '"><a class="page-link" href="#"><span aria-hidden="true">&laquo;</span></a></li>';
    }

    for ($i = $start_number; $i <= $end_number; $i++) {
        $link_active = ($hlm == $i) ? ' active' : '';
        echo '<li class="page-item halaman' . $link_active . '" id="' . $i . '"><a class="page-link" href="#">' . $i . '</a></li>';
    }

    if ($hlm == $jumlah_page) {
        echo '<li class="page-item disabled"><a class="page-link" href="#"><span aria-hidden="true">&raquo;</span></a></li>';
        echo '<li class="page-item disabled"><a class="page-link" href="#">Last</a></li>';
    } else {
        $link_next = $hlm + 1;
        echo '<li class="page-item halaman" id="' . $link_next . '"><a class="page-link" href="#"><span aria-hidden="true">&raquo;</span></a></li>';
        echo '<li class="page-item halaman" id="' . $jumlah_page . '"><a class="page-link" href="#">Last</a></li>';
    }

    echo '</ul></nav>';
}
?>
