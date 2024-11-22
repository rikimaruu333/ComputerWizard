<?php
$uploadDirectory = "../album/";

if (isset($_POST['fileName'])) {
    $fileName = $_POST['fileName'];

    $filePath = $uploadDirectory . basename($fileName);

    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            echo json_encode([
                'status' => 'success',
                'message' => 'File deleted successfully.'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Error deleting the file.'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'File not found.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'No file specified.'
    ]);
}
?>
