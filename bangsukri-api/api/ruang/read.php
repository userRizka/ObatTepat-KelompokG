<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include "../../koneksi/koneksi.php";

file_put_contents('php://stderr', print_r($_GET, TRUE));  

$table = isset($_GET['table']) ? $_GET['table'] : '';

$valid_tables = ['DaftarObat', 'EditObat', 'TambahObat', 'Pengaturan'];
if (!in_array($table, $valid_tables)) {
    echo json_encode([
        "error" => true,
        "message" => "Action tidak ditemukan. Parameter 'table' harus salah satu dari: " . implode(", ", $valid_tables)
    ]);
    exit;
}

$read_sql = "";
if ($table === "Pengaturan") {
    $read_sql = "SELECT Notifikasi as notifikasi, 
                        DefaultMessage as default_message, 
                        Bahasa as bahasa 
                 FROM Pengaturan";
} elseif ($table === "DaftarObat") {
    $read_sql = "SELECT * FROM DaftarObat";
} elseif ($table === "EditObat") {
    $read_sql = "SELECT * FROM EditObat";
} elseif ($table === "TambahObat") {
    $read_sql = "SELECT * FROM TambahObat";
}

// Eksekusi query
$result = mysqli_query($mysqli, $read_sql);

// Inisialisasi respons
$response = [
    "error" => true,
    "message" => "Gagal mengambil data",
    "count" => 0,
    "data" => []
];

if ($result) {
    $count = mysqli_num_rows($result);
    if ($count > 0) {
        $response["error"] = false;
        $response["message"] = "Data berhasil diambil";
        $response["count"] = $count;

        while ($row = mysqli_fetch_assoc($result)) {
            array_push($response["data"], $row);
        }
    } else {
        $response["error"] = false;
        $response["message"] = "Tidak ada data untuk tabel yang dipilih";
    }
} else {
    $response["error"] = true;
    $response["message"] = "Kesalahan dalam query: " . mysqli_error($mysqli);
}

echo json_encode($response);

mysqli_close($mysqli);
?>
