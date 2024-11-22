<?php
  if (isset($_FILES['file'])) {
    $file_name =  $_FILES['file']['name'];
    $tmp_name = $_FILES['file']['tmp_name'];
    $file_up_name = time() . '_' . $file_name;  

    if (move_uploaded_file($tmp_name, "../valid_id/" . $file_up_name)) {
      echo json_encode([
        'status' => 'success',
        'fileName' => $file_up_name,
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
