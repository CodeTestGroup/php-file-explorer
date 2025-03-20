<?php
// Set the directory to display files from
$directory = './'; // Current directory

// Handle file upload
if (isset($_POST['upload'])) {
    if (isset($_FILES['file'])) {
        $uploadedFile = $_FILES['file'];
        $targetPath = $directory . basename($uploadedFile['name']);

        // Check for upload errors
        if ($uploadedFile['error'] == UPLOAD_ERR_OK) {
            if (move_uploaded_file($uploadedFile['tmp_name'], $targetPath)) {
                echo "File uploaded successfully!";
            } else {
                echo "Failed to upload the file!";
            }
        } else {
            echo "Error uploading file!";
        }
    }
}

// Handle file download
if (isset($_GET['download'])) {
    $file = $_GET['download'];
    $filePath = $directory . $file;

    if (file_exists($filePath)) {
        // Set the headers for the file download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        echo "File does not exist.";
    }
}

// Get all files in the directory
$files = array_diff(scandir($directory), array('..', '.'));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP File Browser</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        h1 {
            text-align: center;
        }
        .file-list {
            margin: 20px 0;
        }
        .file-list a {
            display: block;
            margin: 5px 0;
            color: #007BFF;
            text-decoration: none;
        }
        .file-list a:hover {
            text-decoration: underline;
        }
        .upload-form {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <h1>PHP File Browser</h1>
    
    <div class="file-list">
        <h3>Files:</h3>
        <?php foreach ($files as $file): ?>
            <a href="?download=<?php echo urlencode($file); ?>"><?php echo htmlspecialchars($file); ?></a>
        <?php endforeach; ?>
    </div>

    <div class="upload-form">
        <h3>Upload a file:</h3>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="file" name="file" required>
            <button type="submit" name="upload">Upload</button>
        </form>
    </div>
</body>
</html>
