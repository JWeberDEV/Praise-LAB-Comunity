<?php
$uploadDir = __DIR__ . '/../uploads/';
// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Check if file was uploaded without errors
  if (isset($_FILES['files']) && $_FILES['files']['error'][0] === UPLOAD_ERR_OK) {
    // Create a directory to save the uploaded file if it doesn't exist
    
    if (!is_dir($uploadDir)) {
      mkdir($uploadDir, 0755, true);
    }

    // Loop through each file
    foreach ($_FILES['files']['name'] as $key => $name) {
      $tmpName = $_FILES['files']['tmp_name'][$key];
      $filePath = $uploadDir . basename($name);

      // Move the uploaded file to the specified directory
      if (move_uploaded_file($tmpName, $filePath)) {
        echo "File uploaded successfully: $filePath\n";
      } else {
        echo "Error uploading file: $name\n";
      }
    }
  } else {
    echo "No files uploaded or there was an error uploading the file.";
  }
} else {
  echo "Invalid request method.";
}
?>