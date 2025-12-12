<?php
session_start();
require_once '../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $deskripsi = $_POST['deskripsi'];
    $status = $_POST['status'];
    $old_gambar = $_POST['old_gambar'];

    $gambar = $old_gambar;

    // Check if new image is uploaded
    if (!empty($_FILES["gambar"]["name"])) {
        $target_dir = "../../assets/uploads/";
        $file_extension = pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION);
        $file_name = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $file_name;

        $uploadOk = 1;
        $imageFileType = strtolower($file_extension);

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["gambar"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["gambar"]["size"] > 2000000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif"
        ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                $gambar = $file_name;
                // Delete old image
                if (file_exists($target_dir . $old_gambar)) {
                    unlink($target_dir . $old_gambar);
                }
            } else {
                echo "Sorry, there was an error uploading your file.";
                exit();
            }
        } else {
            exit();
        }
    }

    // Update DB
    $stmt = $conn->prepare("UPDATE menu SET nama=?, kategori=?, harga=?, deskripsi=?, gambar=?, status=? WHERE id=?");
    $stmt->bind_param("ssisssi", $nama, $kategori, $harga, $deskripsi, $gambar, $status, $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();
?>