<?php
include('../../koneksi/koneksi.php');
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

function sendError($message) {
    echo json_encode(["error" => true, "message" => $message]);
    exit;
}

function sendSuccess($message, $data = null) {
    echo json_encode(["error" => false, "message" => $message, "data" => $data]);
    exit;
}

if ($method === 'POST') {

    $data = json_decode(file_get_contents("php://input"));

    // Handle DaftarObat
    if (isset($data->DaftarObat)) {
        $daftarObat = $data->DaftarObat;
        if (isset($daftarObat->NamaObat) && isset($daftarObat->JumlahObat) && isset($daftarObat->Expired) && isset($daftarObat->TanggalKonsul)) {
            
            $alarm = null;
            if (isset($daftarObat->alarm) && !empty($daftarObat->alarm)) {
                $alarm = date("H:i", strtotime($daftarObat->alarm));
            }
            if (!$alarm) {
                $alarm = '00:00';
            }

            $query = "INSERT INTO DaftarObat (NamaObat, JumlahObat, Expired, TanggalKonsul, alarm, Waktu) 
                      VALUES ('" . $daftarObat->NamaObat . "', " . $daftarObat->JumlahObat . ", '" . $daftarObat->Expired . "', '" . $daftarObat->TanggalKonsul . "', '" . $alarm . "', '" . $daftarObat->Waktu . "')";
            $result = mysqli_query($mysqli, $query);
            if ($result) {
                sendSuccess("Data berhasil ditambahkan ke DaftarObat");
            } else {
                sendError("Gagal menambahkan data ke DaftarObat");
            }
        } else {
            sendError("Data tidak lengkap untuk menambahkan DaftarObat");
        }
    }

    // Handle EditObat
    if (isset($data->EditObat)) {
        $editObat = $data->EditObat;
        if (isset($editObat->Id_DaftarObat) && isset($editObat->NamaObat) && isset($editObat->JumlahObat) && isset($editObat->Expired)) {
            
            $alarm = null;
            if (isset($editObat->alarm) && !empty($editObat->alarm)) {
                $alarm = date("H:i", strtotime($editObat->alarm));
            }

            if (!$alarm) {
                $alarm = '00:00';
            }

            $query = "INSERT INTO EditObat (Id_DaftarObat, NamaObat, JumlahObat, Expired, alarm, Waktu) 
                      VALUES (" . $editObat->Id_DaftarObat . ", '" . $editObat->NamaObat . "', " . $editObat->JumlahObat . ", '" . $editObat->Expired . "', '" . $alarm . "', '" . $editObat->Waktu . "')";
            $result = mysqli_query($mysqli, $query);
            if ($result) {
                sendSuccess("Data berhasil ditambahkan ke EditObat");
            } else {
                sendError("Gagal menambahkan data ke EditObat");
            }
        } else {
            sendError("Data tidak lengkap untuk menambahkan EditObat");
        }
    }

    // Handle TambahObat
    if (isset($data->TambahObat)) {
        $tambahObat = $data->TambahObat;
        if (isset($tambahObat->Id_DaftarObat) && isset($tambahObat->Id_EditObat) && isset($tambahObat->NamaObat) && isset($tambahObat->JumlahObat) && 
            isset($tambahObat->Expired) && isset($tambahObat->TanggalKonsul)) {

            $alarm = null;
            if (isset($tambahObat->alarm) && !empty($tambahObat->alarm)) {
                $alarm = date("H:i", strtotime($tambahObat->alarm));
            }
            if (!$alarm) {
                $alarm = '00:00';
            }

            $query = "INSERT INTO TambahObat (Id_DaftarObat, Id_EditObat, alarm, Waktu, NamaObat, JumlahObat, Expired, TanggalKonsul, Ulangi, Aktif, IngatkanSaya) 
                      VALUES (" . $tambahObat->Id_DaftarObat . ", " . $tambahObat->Id_EditObat . ", '" . $alarm . "', '" . $tambahObat->Waktu . "', 
                      '" . $tambahObat->NamaObat . "', " . $tambahObat->JumlahObat . ", '" . $tambahObat->Expired . "', '" . $tambahObat->TanggalKonsul . "', 
                      " . ($tambahObat->Ulangi ? 1 : 0) . ", " . ($tambahObat->Aktif ? 1 : 0) . ", " . ($tambahObat->IngatkanSaya ? 1 : 0) . ")";
            $result = mysqli_query($mysqli, $query);
            if ($result) {
                sendSuccess("Data berhasil ditambahkan ke TambahObat");
            } else {
                sendError("Gagal menambahkan data ke TambahObat");
            }
        } else {
            sendError("Data tidak lengkap untuk menambahkan TambahObat");
        }
    }

    // Handle Pengaturan
    if (isset($data->Pengaturan)) {
        $pengaturan = $data->Pengaturan;
        if (isset($pengaturan->Notifikasi) && isset($pengaturan->DefaultMessage) && isset($pengaturan->Bahasa)) {
            $query = "UPDATE Pengaturan SET Notifikasi = " . ($pengaturan->Notifikasi ? 1 : 0) . ", DefaultMessage = " . ($pengaturan->DefaultMessage ? 1 : 0) . ", 
                      Bahasa = '" . $pengaturan->Bahasa . "'";
            $result = mysqli_query($mysqli, $query);
            if ($result) {
                sendSuccess("Data berhasil diperbarui pada Pengaturan");
            } else {
                sendError("Gagal memperbarui data pada Pengaturan");
            }
        } else {
            sendError("Data tidak lengkap untuk memperbarui Pengaturan");
        }
    }

} else {
    sendError("Action tidak ditemukan");
}
