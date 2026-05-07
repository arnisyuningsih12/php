<?php
// Set header agar bisa diakses dari luar (CORS) dan formatnya JSON
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'koneksi.php';

// Ambil metode request (GET, POST, dll)
$method = $_SERVER['REQUEST_METHOD'];

$response = [];

switch ($method) {
    case 'GET':
        // LOGIKA READ: Menampilkan data
        $query = mysqli_query($koneksi, "SELECT * FROM users");
        $users = [];
        while ($row = mysqli_fetch_assoc($query)) {
            $users[] = $row;
        }
        $response = [
            "status" => "success",
            "data" => $users
        ];
        break;

    case 'POST':
        // LOGIKA CREATE: Tambah data
        // Mengambil data baik dari Form-Data maupun Raw JSON
        $nama = $_POST['nama'] ?? null;
        $sandi = $_POST['sandi'] ?? null;

        if (!$nama || !$sandi) {
            // Jika post kosong, coba baca dari JSON input (untuk Postman raw body)
            $json = json_decode(file_get_contents('php://input'), true);
            $nama = $json['nama'] ?? null;
            $sandi = $json['sandi'] ?? null;
        }

        if ($nama && $sandi) {
            $insert = mysqli_query($koneksi, "INSERT INTO users (nama, sandi) VALUES('$nama', '$sandi')");
            if ($insert) {
                http_response_code(201);
                $response = ["status" => "success", "message" => "Data berhasil ditambahkan"];
            } else {
                $response = ["status" => "error", "message" => mysqli_error($koneksi)];
            }
        } else {
            $response = ["status" => "error", "message" => "Data nama dan sandi tidak lengkap"];
        }
        break;

    case 'DELETE':
        // LOGIKA DELETE: Hapus data
        // Biasanya ID dikirim lewat parameter URL, misal: index.php?id=1
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $delete = mysqli_query($koneksi, "DELETE FROM users WHERE id=$id");
            if ($delete) {
                $response = ["status" => "success", "message" => "Data ID $id berhasil dihapus"];
            } else {
                $response = ["status" => "error", "message" => mysqli_error($koneksi)];
            }
        } else {
            $response = ["status" => "error", "message" => "ID tidak ditemukan"];
        }
        break;

    default:
        $response = ["status" => "error", "message" => "Metode tidak dikenali"];
        break;
}

// Keluarkan hasil akhir dalam format JSON
echo json_encode($response, JSON_PRETTY_PRINT);
