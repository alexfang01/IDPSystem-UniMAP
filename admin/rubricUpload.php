<?php 
    include('../config/db_connect.php');
    include './logincheckAdmin.php';

    $sql = "SELECT * FROM rubricfile";
    $resultFile = mysqli_query($conn, $sql);
    
    if(isset($_POST['delete'])){
        $fileid = mysqli_real_escape_string($conn, $_POST['hiddenid']);
        $target_file = "";
        
        $sql = "SELECT * FROM rubricfile WHERE fileid='$fileid'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0){
            $infoFile = mysqli_fetch_assoc($result);
            $target_file = "../fileuploaded/rubrics/". $infoFile['filename'];
        }else{
            echo "hello";
        }
        
        if (file_exists($target_file)) {
            unlink($target_file);
            $sql = "DELETE FROM rubricfile WHERE fileid='$fileid'";
            if(mysqli_query($conn, $sql)){
                //success to save
                echo "<script>alert('File has successfully deleted');</script>";
                echo "<script>window.location.href = 'rubricUpload.php?t=" . time() . "';</script>";
            }else{
                echo 'query error'.mysqli_error($conn);
            }
            
        }else{
            echo "<script>alert('File doesn't exist');</script>";
            echo "<script>window.location.href = 'rubricUpload.php?t=" . time() . "';</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Rubric</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://kit.fontawesome.com/4c7903acb1.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .container {
            align-items: center;
            justify-content: center;
        }
        form {
            margin-bottom: 20px;
        }

        label {
            display: inline-block;
            width: 150px;
        }

        button {
            background-color: #4CAF50;
            color: black;
            cursor: pointer;
            background-color: #F3F6F4;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin: 4px;
            padding:5px;
        }

        button:hover {
            cursor:pointer;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: -170px;
            border-radius: 25px;
            overflow: hidden;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
        .file-link {
            color: blue;
            text-decoration: none;
            cursor: pointer;
        }

        .file-link:hover {
            color: darkblue;
        }

        .pdf-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }
        .pdf-modal-content {
            background-color: white;
            padding: 0;
            border-radius: 5px;
            width: 90%;
            height: 90%;
            overflow: hidden;
            position: relative;
        }
        .pdf-modal-content iframe {
            width: 100%;
            height: 100%;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-top: 20px;
        }

        select {
            border-radius: 20px;
            text-align: center;
            background-color: #F3F6F4;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: 4px;
            border: 2px solid black;
        }

        .custom-file-input {
            display: none;
        }

        .custom-file-label {
            display: inline-block;
            cursor: pointer;
            padding: 6px 12px;
            background-color: #F3F6F4;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 20px;
            border: 1px solid #ccc;
            transition: background-color 0.3s ease;
            width: 100%;
            max-width: 400px;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .custom-file-label:hover {
            background-color: #e2e2e2;
        }

        .file-input-container {
            position: relative;
            display: inline-block;
            width: 100%;
            max-width: 400px;
        }

        .file-input-button {
            display: inline-block;
            padding: 6px 12px;
            background-color: #F3F6F4;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 0 20px 20px 0;
            border: 1px solid #ccc;
            border-left: none;
            cursor: pointer;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .file-input-button:hover {
            background-color: #e2e2e2;
        }

        #file{
        background-color: #F3F6F4;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 0px 20px 20px 0px;
        margin-left: 3px;
    }

    .delete-icon-button {
            background-color: #DC3545;
            border: none;
            border-radius: 5px;
            padding: 0;
            color: white;
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 13px;
        }

        .delete-icon-button i {
            color: white;
        }

        @media (max-width: 768px) {
            .container {
                margin: 0 auto;
                padding: 10px;
            }

            .custom-file-label {
                width: 100%;
            }

            .file-input-container {
                width: 100%;
            }

            .file-input-button {
                width: 100%;
                border-radius: 20px;
                margin-top: 10px;
            }

            .table-responsive {
                margin-top: 10px;
            }

            label, input[type="file"], select, button {
                width: 100%;
                margin-bottom: 10px;
            }

            .navbar {
        flex-direction: row; /* Keep elements in a row */
        align-items: center;
        justify-content: space-between; /* Distribute space between items */
        width: 100%; /* Ensure the navbar takes the full width */
        padding: 0 10px; /* Add some padding for spacing */
    }

    .navbar-collapse {
        width: auto; /* Let it auto-adjust */
        display: flex;
        justify-content: flex-end; /* Align items to the right */
        align-items: center;
        flex-grow: 1; /* Allow it to grow to take remaining space */
    }

    .navbar-nav {
        display: flex;
        flex-direction: row;
        align-items: center;
        padding: 0; /* Remove any extra padding */
    }

    .nav-item {
        margin: 0 5px; /* Reduce margins to fit items */
    }

    .bell-icon {
        font-size: 20px; /* Adjusted for smaller screens */
        margin-right:-10px; /* Remove margin */
    }

    .profile-link {
        display: flex;
        align-items: center;
        padding-left: 0; /* Remove padding for better fit */
        font-family: 'Poppins', sans-serif;
        font-size: 16px; /* Ensure this font size matches your desired size */
        color: #0D6EFD; /* Ensure this color matches your desired color */
        text-decoration: none; /* Ensure this prevents text underline */
        margin: 0; /* Remove margin */
    }

    .profile-link img {
        margin-left: -20px; /* Smaller margin for better fit */
        height: 30px; /* Adjust height if necessary */
        width: 30px; /* Adjust width if necessary */
    }

    #sidebar-toggle {
        margin-left: -120px; /* Add margin to separate from other elements */
    }
}
    </style>
</head>
<body>
<div class="d-flex">
<?php include './sidebarAdmin.php'; ?>
    <div class="container">
        <h1 class="text-center mb-3 title-container">Upload Rubric</h1>
        <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="rubricType" style="font-size: 20px; margin-top:20px;">Rubric Type:</label>
            <select id="rubricType" name="rubricType" required>
                <option value="" required>Select Type</option>
                <option value="report" required>Final report</option>
                <option value="proposal" required>Proposal</option>
                <option value="minute" required>Minute meetings</option>
                <option value="poster" required>Video and Poster</option>
                <option value="idpex" required>Exhibition</option>
                <option value="peersv" required>Peer review SV</option>
                <option value="peerstudent" required>Peer review Student</option>
            </select>
            <br><br>
            <label style="font-size: 20px; width:290px;" for="file">Select rubric file to upload:</label>
            <input type="file" id="file" name="file" accept="application/pdf" required>
            <button type="submit" style="border-radius: 20px;">Upload File</button>
        </form>
<br>
<br>
        <h2 class="text-center" style="margin-top:-10px;">Saved Files</h2>
        <div class="table-responsive">
        <table id="filesTable" class="table">
            <!-- Populate this table with saved files from your database -->
             <tr>
             <th style="background-color:#f2f2f2;">File name</th>
                <th style="background-color:#f2f2f2;">Time uploaded</th>
                <th style="background-color:#f2f2f2;">Delete</th>
             </tr>
                <?php 
                    if ($resultFile) {
                        // Output data rows
                        while ($row = mysqli_fetch_assoc($resultFile)) {
                            echo "<form action='rubricUpload.php' method='post'>";
                            echo "<tr>";
                            echo "<td><a href='#' onclick='openModal(\"../fileuploaded/rubrics/" . htmlspecialchars($row['filename']) . "\")' class='file-link'>" . htmlspecialchars($row['filename']) . "</a></td>";
                            echo "<td>" . htmlspecialchars($row['submitTime']) . "</td>";
                            echo "<input type='hidden' name='hiddenid' value='" . htmlspecialchars($row['fileid']) . "'>";
                            echo "<td><button class='delete-icon-button' type='submit' name='delete'><i class='bi bi-trash'></i></button></td>";
                            echo "</tr>";
                            echo "</form>";
                            echo "</br>";
                        }
                        // Free result set
                        mysqli_free_result($resultFile);
                    } else {
                        echo "No record found";
                    }
                                    
                ?>
    
             
        </table>
        </div>
<div id="pdfModal" class="pdf-modal">
    <div class="pdf-modal-content">
        <iframe id="pdfIframe" src="" onload="adjustPdfZoom()" onerror="showError()" width="100%" height="100%"></iframe>
        <p id="error-message" class="error-message" style="display:none;">PDF file not found.</p>
    </div>
</div>
    </div>
    
    <script>
    function openModal(pdfUrl) {
        document.getElementById('pdfIframe').src = pdfUrl;
        document.getElementById('pdfModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('pdfModal').style.display = 'none';
        document.getElementById('error-message').style.display = 'none';
    }

    function showError() {
        document.getElementById('error-message').style.display = 'block';
    }

    function adjustPdfZoom() {
        const iframe = document.getElementById('pdfIframe');
        iframe.contentWindow.addEventListener('load', () => {
            iframe.contentWindow.document.body.style.zoom = '150%';
        });
    }

    window.onclick = function(event) {
        const modal = document.getElementById('pdfModal');
        if (event.target == modal) {
            closeModal();
        }
    }

        document.addEventListener("DOMContentLoaded", function() {
            // This is where you will retrieve and display the saved files from the database
            // Example:
            fetch('get_files.php')
                .then(response => response.json())
                .then(data => {
                    const table = document.getElementById('filesTable');
                    data.forEach(file => {
                        const row = table.insertRow();
                        const cell1 = row.insertCell(0);
                        const cell2 = row.insertCell(1);
                        cell1.textContent = file.rubric_type;
                        cell2.innerHTML = `<a href="${file.file_path}" target="_blank">${file.file_name}</a>`;
                    });
                });
        });

    </script>
</body>
<?php

mysqli_close($conn); 
?>
</html>
