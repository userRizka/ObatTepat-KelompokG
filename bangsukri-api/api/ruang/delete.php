<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include "../../koneksi/koneksi.php";

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->table) || !isset($data->id)) {
    echo json_encode([
        "error" => true,
        "message" => "Parameter 'table' dan 'id' harus ada dalam request"
    ]);
    exit;
}

$table = $data->table; 
$id = $data->id; 

$query = "";
switch ($table) {
    case "EditObat":
        $query = "DELETE FROM EditObat WHERE Id_EditObat = ?";
        break;
    case "TambahObat":
        $query = "DELETE FROM TambahObat WHERE Id_TambahObat = ?";
        break;
    case "Pengaturan":
        $query = "DELETE FROM Pengaturan WHERE Id_Pengaturan = ?";
        break;
    case "DaftarObat":
        $query = "DELETE FROM DaftarObat WHERE Id_DaftarObat = ?";
        break;
    default:
        echo json_encode([
            "error" => true,
            "message" => "Tabel tidak valid, pilih salah satu dari: EditObat, TambahObat, Pengaturan, DaftarObat"
        ]);
        exit;
}

$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode([
        "error" => false,
        "message" => "Data berhasil dihapus dari tabel " . $table
    ]);
} else {
    echo json_encode([
        "error" => true,
        "message" => "Gagal menghapus data dari tabel " . $table
    ]);
}

$stmt->close();
$mysqli->close();
?>