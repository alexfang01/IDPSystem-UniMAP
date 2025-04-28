<!DOCTYPE html>
<html lang="en">
<?php
session_start();
include ('../config/db_connect.php');
include './logincheckAdmin.php';
include './sidebarAdmin.php';
?>

<?php
if (isset($_FILES["pdfFile"]["name"])) {
    // Set the target directory where you want to save the uploaded PDF
    $targetDir = "../fileuploaded/rubric/";

    // Define a default file name (e.g., "uploaded_file.pdf")
    $defaultFileName = "rubric" . ".pdf";

    // Set the target file path
    $targetFile = $targetDir . $defaultFileName;

    // Initialize upload status
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($_FILES["pdfFile"]["name"], PATHINFO_EXTENSION));

    // Check if file is a PDF
    if ($fileType != "pdf") {
        echo "Sorry, only PDF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Try to upload the file
        if (move_uploaded_file($_FILES["pdfFile"]["tmp_name"], $targetFile)) {
            echo "The file " . htmlspecialchars(basename($_FILES["pdfFile"]["name"])) . " has been uploaded as rubric.pdf.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

?>

<style>
    iframe {
        width: 100%;
        height: 100%;
        /* Adjust height as needed */
        border: none;
    }

    .inputx {
        text-align: center;
    }
    .title-container {
        border-bottom: 3px solid black;
    }
    .container {
        display: flex;
        justify-content: center;
        align-items: center;
        /* border: 3px solid red; */
    }
</style>
<html>

<body>
    <h1 class="text-center mb-3 title-container"> View & Upload Rubric</h1>
    <form action="rubric.php" class="inputx" method="post" enctype="multipart/form-data">
        <label for="pdfFile">New Rubric PDF:</label>
        <input type="file" name="pdfFile" id="pdfFile" accept="application/pdf" required>
        <input type="submit" value="Upload">
    </form>
    <iframe src="../fileuploaded/rubric/rubric.pdf"></iframe>
    </div>
    </div>
</body>

</html>