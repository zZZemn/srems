<?php
if (isset($_FILES['db']) && $_FILES['db']['error'] == UPLOAD_ERR_OK) {
    $file_type = pathinfo($_FILES['db']['name'], PATHINFO_EXTENSION);
    if ($file_type !== 'sql') {
        echo "Only .sql files are allowed.";
        exit;
    }

    $upload_dir = "/path/to/your/upload/directory/";
    $upload_file = $upload_dir . basename($_FILES['db']['name']);

    if (move_uploaded_file($_FILES['db']['tmp_name'], $upload_file)) {
        echo "File uploaded successfully.<br>";

        $host = 'localhost';
        $username = 'root';
        $password = '';
        $database = 'SREMS';

        $sql_content = file_get_contents($upload_file);

        $conn = new mysqli($host, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $conn->autocommit(FALSE);

        $queries = explode(';', $sql_content);

        foreach ($queries as $query) {
            $query = trim($query);
            if (!empty($query)) {
                if (!$conn->query($query)) {
                    echo "Error executing query: " . $conn->error . "<br>";
                    $conn->rollback();
                    exit;
                }
            }
        }

        $conn->commit();

        $conn->close();

        echo "Database restored successfully.";
    } else {
        echo "Failed to upload the file.";
    }
} else {
    echo "No file uploaded or an error occurred during the upload.";
}
