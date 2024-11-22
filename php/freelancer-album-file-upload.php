<?php
if (isset($_FILES['file'])) {
    $file_name = $_FILES['file']['name'];  // Use the original file name
    $tmp_name = $_FILES['file']['tmp_name'];
    
    // Define the target directory for the uploaded file
    $target_directory = "../album/";
    $target_file = $target_directory . $file_name;

    // Move the uploaded file to the target directory
    if (move_uploaded_file($tmp_name, $target_file)) {
        echo json_encode([
            'status' => 'success',
            'fileName' => $file_name,  // Return the original file name
            'message' => 'File uploaded successfully.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to upload file.'
        ]);
    }
}
?>
