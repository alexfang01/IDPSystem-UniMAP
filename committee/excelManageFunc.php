<?php

// Start or resume the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include '../config/db_connect.php';

// Load PhpSpreadsheet library
include './vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

// Function to handle file upload
function uploadFileAndPopulateTable($conn, $targetDir) {
    if (isset($_POST["submit"]) && isset($_FILES["file"])) { // Check if "file" key exists
        // Check if file is selected
        if (empty($_FILES["file"]["name"])) {
            $_SESSION['message'] = "Please select a file.";
            return; // Exit function if no file is selected
        }

        // Check if uploads directory exists, if not, create it
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // File path
        $targetFile = $targetDir . basename($_FILES["file"]["name"]);
        $uploadOk = 1;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Allow CSV and Excel files
        if ($fileType != "csv" && $fileType != "xls" && $fileType != "xlsx") {
            $_SESSION['message'] = "Sorry, only CSV, XLS, and XLSX files are allowed.";
            $uploadOk = 0;
        }

        // Calculate file hash
        $fileHash = hash_file('sha256', $_FILES["file"]["tmp_name"]);
        error_log("Uploading file with hash: $fileHash");

        // Check if the same file has been uploaded before
        if ($uploadOk == 1) {
            if (file_exists($targetFile)) {
                $_SESSION['message'] = "File already exists.";
                error_log("File already exists in target directory: $targetFile");
                $uploadOk = 0;
            } else if (isset($_SESSION['uploaded_file_hashes']) && in_array($fileHash, $_SESSION['uploaded_file_hashes'])) {
                $_SESSION['message'] = "File already exists.";
                error_log("File hash already exists in session.");
                $uploadOk = 0;
            }
        }

        // If upload is successful, proceed to process the file
        if ($uploadOk == 1) {
            // Attempt to move the uploaded file
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
                $_SESSION["uploaded_file"] = true; // Set session variable to indicate file upload success
                $_SESSION['message'] = "The file ". htmlspecialchars(basename($_FILES["file"]["name"])) . " has been uploaded.";
                // Store the path of the latest uploaded file in the session
                $_SESSION['latest_excel_file'] = $targetFile;
                // Clear previous Excel data from session
                unset($_SESSION['excel_data']);
                // Store the file hash
                if (!isset($_SESSION['uploaded_file_hashes'])) {
                    $_SESSION['uploaded_file_hashes'] = [];
                }
                $_SESSION['uploaded_file_hashes'][] = $fileHash;
                error_log("File hash stored in session: $fileHash");
                // Proceed to process the file
                processFile($targetFile, $conn);
            } else {
                $_SESSION['message'] = "Sorry, there was an error uploading your file.";
                error_log("Error moving uploaded file.");
            }
        }
    }
}

// Function to delete a file and its hash
function deleteFile($filename) {
    $filePath = 'excelUpload/' . $filename;

    // Check if the file exists
    if (file_exists($filePath)) {
        // Calculate the file hash to be removed before deleting the file
        $fileHash = hash_file('sha256', $filePath);
        error_log("Deleting file with hash: $fileHash");

        // Attempt to delete the file
        if (unlink($filePath)) {
            // Remove the corresponding file hash from the session
            if (isset($_SESSION['uploaded_file_hashes'])) {
                // Find and remove the hash from the session array
                $_SESSION['uploaded_file_hashes'] = array_filter($_SESSION['uploaded_file_hashes'], function($hash) use ($fileHash) {
                    return $hash !== $fileHash;
                });
                error_log("File hash removed from session: $fileHash");
            }
            $_SESSION['message'] = "File '$filename' has been deleted successfully.";
        } else {
            $_SESSION['message'] = "Error: Unable to delete '$filename'.";
            error_log("Error deleting file: $filePath");
        }
    } else {
        $_SESSION['message'] = "Error: File '$filename' does not exist.";
        error_log("File does not exist: $filePath");
    }

    // Redirect back to the page after deletion
    header("Location: excelManage.php");
    exit(); // Ensure no further code execution after redirection
}

// Function to process the uploaded Excel file and insert data into the database
function processFile($file, $conn) {
    try {
        // Truncate the uploadedexceldata table to remove all previous data
        $conn->query("TRUNCATE TABLE uploadedexceldata");

        // Load Excel file
        $spreadsheet = IOFactory::load($file);

        // Get the first worksheet
        $sheet = $spreadsheet->getActiveSheet();

        // Get all cells
        $allData = $sheet->toArray(null, true, true, true);

        // Remove header row
        unset($allData[1]);

        // Store the Excel data in session for later use
        $excelData = [];

        foreach ($allData as $row) {
            // Adjusted for new structure without studentid
            $name = $row['A']; // Adjust this according to your Excel file structure
            $excelData[$name] = [
                'name' => $name,
                'ic' => $row['B'], 
                'matric' => $row['C'], 
                'prog' => $row['D'], 
                'race' => $row['E'], 
                'gender' => $row['F'], 
                'cgpa' => $row['G'], 
                'phnum' => $row['H'], 
                'email' => $row['I'], 
            ];
        
            // Insert data into the database table 'uploadedexceldata'
            $sql = "INSERT INTO uploadedexceldata (name, ic, matric, prog, race, gender, cgpa, phnum, email)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssss", $name, $row['B'], $row['C'], $row['D'], $row['E'], $row['F'], $row['G'], $row['H'], $row['I']);
            $stmt->execute();
        }

        $_SESSION['excel_data'] = $excelData;

    } catch (Exception $e) {
        // Handle any exceptions here
        error_log('Error processing Excel file: ' . $e->getMessage());
    }
}

// Function to find a matching row in Excel data for the current database row
function findMatchingExcelRow($excelData, $dbRow) {
    foreach ($excelData as $excelRow) {
        if (
            $dbRow["name"] === $excelRow["name"] &&
            $dbRow["matric"] === $excelRow["matric"] &&
            $dbRow["ic"] === $excelRow["ic"]
        ) {
            return $excelRow; // Return the matching Excel row
        }
    }
    return null; // No matching row found
}
?>