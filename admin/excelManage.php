<?php
// Set error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start or resume the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include ('../config/db_connect.php');
include './logincheckAdmin.php';
include './sidebarAdmin.php';
include 'excelManageFunc.php';

// Define the target directory for uploads
$targetDir = 'excelUpload/';

// Populate the session hash array with existing files' hashes on page load
if (!isset($_SESSION['uploaded_file_hashes'])) {
    $_SESSION['uploaded_file_hashes'] = [];
    $existingFiles = scandir($targetDir);
    foreach ($existingFiles as $file) {
        if ($file != '.' && $file != '..') {
            $filePath = $targetDir . $file;
            if (is_file($filePath)) {
                $fileHash = hash_file('sha256', $filePath);
                $_SESSION['uploaded_file_hashes'][] = $fileHash;
                error_log("Existing file hash added to session: $fileHash");
            }
        }
    }
}

// Handle file upload and process it
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    uploadFileAndPopulateTable($conn, $targetDir);
}

// Fetch unverified student data from the database
$sql = "SELECT * FROM student WHERE verify = 0";
$result = $conn->query($sql);

// Fetch Excel data from session if available
$excelData = isset($_SESSION['excel_data']) ? $_SESSION['excel_data'] : [];

// Fetch Excel data from the database if session data is not available
if (empty($excelData)) {
    $sqlExcel = "SELECT * FROM uploadedexceldata";
    $resultExcel = $conn->query($sqlExcel);
    if ($resultExcel->num_rows > 0) {
        while ($rowExcel = $resultExcel->fetch_assoc()) {
            $excelData[$rowExcel['name']] = $rowExcel; // Use 'name' as the key
        }
        $_SESSION['excel_data'] = $excelData; // Store the fetched data in session
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check students' data</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    integrity="sha512-fDnh8MPtZZ2ayE8D6f6fvMm8eEaMYPeMgpIH93nUuO8hjO7w/JjrGzrj9oH9W/zB84r+JUtYW+lpPF9LCJLqKQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>

    table {
        width: 100%;
        border-collapse: collapse;
        border-radius: 20px;
        overflow: hidden;
        margin: auto;
        border-spacing: 0; /* Ensure there are no gaps between cells */
    }
    th, td {
        border: 1px solid #f2f2f2;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
        border-top: none; /* Remove top border for headers to avoid double borders */
        text-align:center;
    }

    .row-box {
        padding: 0;
        margin-bottom: 10px;
    }
    .row-box tr {
        background-color: inherit; /* Ensure the background color is inherited */
    }
    .matched-row {
        background-color: lightgreen; /* Set background color for matched rows */
    }
    .partial-matched-row {
        background-color: yellow; /* Set background color for partially matched rows */
    }
    .unmatched-row {
        background-color: lightcoral; /* Set background color for unmatched rows */ 
    }
    .no-excel-row {
        background-color: white; /* Set background color for rows when no Excel file is uploaded */
    }
    .verify-button {
        color: white;
        border-color: hidden; /* Set border color to match the background color */
        margin-left: 7px;
    }
    .green-icon {
        color: green; /* Set the color for the verified icon */
    }
    .red-icon {
        color: red; /* Set the color for the unverified icon */
    }
/* Common styles for edit buttons */
.edit-buttons {
    width: 40px; /* Adjust the width to match the verify button */
    height: 38px; /* Adjust the height to match the verify button */
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
    border-radius: 7px; /* Optional: add border radius for rounded corners */
    color: white; /* Set the icon color to white */
    margin-left: 10px;
}

.edit-buttons.edit {
    background-color: #0D6EFD; /* Set the background color to blue */
}

/* Styles for the save (submit edit) icon button */
.edit-buttons.save {
    background-color: #198754; /* Set the background color to green */
}

/* Styles for the cancel edit icon button */
.edit-buttons.cancel {
    background-color: #DC3545; /* Set the background color to red */
}

/* Specific icon styles */
.edit-buttons i {
    color: white !important; /* Set icon color to white */
}

/* Optional: Additional styles for hover effects */
.edit-buttons:hover {
    opacity: 0.8; /* Slightly dim the button on hover */
}

    input[type="text"] {
    width: auto;
    box-sizing: border-box;
    border: 1px solid #ccc;
    padding: 4px;
    font-size: 14px;
    }

    input[type="text"].resizable {
    display: inline-block;
    width: auto;
    min-width: 100px; /* Set a minimum width for the input fields */
    font-size: 20px;
}
    .edit-buttons.hide-on-edit {
        display: none; /* Hide Edit button during edit process */
    }
    .scrollable-table-container {
        height: calc(100vh - 400px); /* Adjust this value based on your layout */
        overflow-y: auto;
    }
    .content {
        margin: auto; /* Adjust this value to match the width of the sidebar */
        padding: 20px;
    }
        /* Styles for the upload form button */
        #uploadForm input[type="submit"] {
            background-color: #F3F6F4;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 20px 20px 20px 20px;
        text-align: center;
    }
.updateUploadedExcelList{
    margin-left: 30px;
}
    ul#uploadedExcelList {
    list-style-type: disc; /* Ensure bullets are shown */
    padding-left: 40px; /* Add padding to align bullets correctly */
    margin-left: 30px; /* Remove default margins */
}

ul#uploadedExcelList li {
    list-style-type: disc; /* Ensure bullets are shown */
            margin-bottom: 10px; /* Add space between list items */
            padding-left: 20px; /* Add padding to ensure bullets are inside the padding area */
        }
/* Container for centering the search box */
.search-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 20px 0; /* Optional: add margin for spacing */
}
#searchBox {
    width: 500px; /* Adjust the width to make it shorter */
    border-radius: 20px; /* Make the corners rounded */
    padding: 5px 10px; /* Add some padding for better appearance */
    border: 1px solid #ddd; /* Add a border */
    outline: none; /* Remove the default outline */
    text-align: center;
    font-size:20px;
}
        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

#file{
    background-color: #F3F6F4;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 0px 20px 20px 0px;
    }
    .center {
    align-items: center;
    justify-content: center;
padding-left:20px;
}

.pdf-button, .download-link {
            display: flex;
            justify-content: center;
        }
        .pdf-button button, .download-link a {
            color: black;
            border: solid black 2px;
            text-decoration: none;
            cursor: pointer;
            background-color: #F3F6F4;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 20px 20px 20px 20px;
        text-align: center;
        padding-left: 10px;
        padding-right: 10px;
        }
        .pdf-button button:hover, .download-link a:hover {
            background-color: #CED4DA;
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
            max-width: 100%;
            max-height: 100%;
        }
        .pdf-modal-content iframe {
            width: 100%;
            height: 100%;
            border: none; /* Remove border */
            overflow: hidden; /* Add this to ensure the iframe fits within the modal */
        }

        .error-message {
            color: red;
            text-align: center;
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .title-container {
            border-bottom: 3px solid black;
            width: fit-content;
        }  
            .pdf-modal-content {
                width: 95%;
                height: 90%;
            }

        .pdf-button button, .download-link a {
        font-size: 14px;
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

    .search-container {
                flex-direction: column;
                gap: 10px;
            }

            #searchBox {
                width: 90%;
                font-size: 16px;
            }

            #uploadForm {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 10px;
            }

            #uploadForm input[type="file"],
            #uploadForm input[type="submit"] {
                width: 90%;
            }
        }       
    </style>
</head>
<body>
<h1 class="text-center mb-3 title-container ">Student Data Check</h1>
<div class="content">

<div class="pdf-button">
            <button onclick="openModal()">Click here to read Excel format instruction</button>
        </div>
    </div>
    <div class="download-link">
            <a href="excel.xlsx" download>Click here to download excel file example</a>
        </div>

    <div id="pdfModal" class="pdf-modal">
    <div class="pdf-modal-content">
            <!-- Modified line below -->
            <iframe id="pdfIframe" src="excel.pdf" onload="adjustPdfZoom()" onerror="showError()" width="100%" height="100%"></iframe>
            <p id="error-message" class="error-message" style="display:none;">PDF file not found.</p>
        </div>
    </div>
<br>
    <!-- Form for file upload -->
    <form action="" method="post" enctype="multipart/form-data" id="uploadForm" class="text-center mb-3">
        Select Excel file to upload:
        <input type="file" name="file" id="file">
        <input type="submit" value="Upload File" name="submit">
    </form>
    <!-- Uploaded files list -->
    <h3 class ="updateUploadedExcelList">Uploaded Excel Files List</h3>
    <ul id="uploadedExcelList" ></ul>
    <!-- Search box -->
    <h3  class="text-center ">Search Unverified Students</h3>
    <div class="search-container">
    <input type="text" id="searchBox" placeholder="Search by any matched data..." onkeyup="filterTable()">
    </div>
    <!-- Table Container -->
    <div id="tableContainer" class="scrollable-table-container" style="margin:30px">
        <?php
        // Display the combined data
        echo "<table id='studentsTable'>";
        echo "<tr>";
        echo "<th>ID</th>";
        echo "<th>Name</th>";
        echo "<th>IC number</th>";
        echo "<th>Matric Number</th>";
        echo "<th>Program</th>";
        echo "<th>Race</th>";
        echo "<th>Gender</th>";
        echo "<th>CGPA</th>";
        echo "<th>Phone Number</th>";
        echo "<th>Email</th>";
        echo "<th>Edit</th>";
        echo "</tr>";

        // Check if the Excel data is empty or not uploaded
        if (empty($excelData)) {
            echo "<tr class='row-box no-excel-row'>";
            echo "<td colspan='10' style='text-align: center;'>Please upload Excel file</td>";
            echo "</tr>";
        } else {
        // Output data from the database
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Initialize flags to track match status
                $exactMatch = false;
                $partialMatch = false;

                // Find the corresponding Excel row for the current database row
                $matchingExcelRow = findMatchingExcelRow($excelData, $row);

                // Start a new row for Excel data if available
                if (!empty($excelData)) {
                    foreach ($excelData as $excelRow) {
                        // Check for exact matches
                        if (
                            $row["name"] == $excelRow["name"] &&
                            $row["ic"] == $excelRow["ic"] &&
                            $row["matric"] == $excelRow["matric"]
                        ) {
                            // Exact match found
                            $exactMatch = true;
                            // Store the matched Excel row
                            $matchingExcelRow = $excelRow;
                            // Exit the loop since we found a match for the current database row
                            break;
                        } elseif (
                            // Check for partial matches
                            ($row["name"] == $excelRow["name"] ||
                            $row["ic"] == $excelRow["ic"] ||
                            $row["matric"] == $excelRow["matric"])
                        ) {
                            // Partial match found
                            $partialMatch = true;
                            $matchingExcelRow = $excelRow;
                            // You might want to break here if you only want to consider the first partial match
                        }
                    }
                }

                // Determine row class based on match status
                $rowClass = 'unmatched-row'; // Default to unmatched
                if ($exactMatch) {
                    $rowClass = 'matched-row'; // Green for exact match
                } elseif ($partialMatch) {
                    $rowClass = 'partial-matched-row'; // Yellow for partial match
                } else {
                    $rowClass = 'unmatched-row'; // Red for no match
                }

                // Generate a unique ID for each "row box"
                $rowBoxId = 'row_box_' . $row['studentid'];
                $excelRowBoxId = 'excel_row_box_' . $row['studentid'];

                // Check if there is a matching Excel row for the current database row
                if ($matchingExcelRow !== null) {
                    // Check for partial match
                    $dataDifference = (
                        $row["name"] != $matchingExcelRow["name"] ||
                        $row["ic"] != $matchingExcelRow["ic"] ||
                        $row["matric"] != $matchingExcelRow["matric"] ||
                        $row["prog"] != $matchingExcelRow["prog"] ||
                        $row["race"] != $matchingExcelRow["race"] ||
                        $row["gender"] != $matchingExcelRow["gender"] ||
                        $row["cgpa"] != $matchingExcelRow["cgpa"] ||
                        $row["phnum"] != $matchingExcelRow["phnum"] ||
                        $row["email"] != $matchingExcelRow["email"]
                    );

                    if (!$dataDifference) {
                        // Exact match
                        $rowClass = 'matched-row'; // Green for exact match
                    } else {
                        // Partial match
                        $rowClass = 'partial-matched-row'; // Yellow for partial match
                    }
                } else {
                    // Unmatched
                    $rowClass = 'unmatched-row';
                }

                // Output the wrapper div with the appropriate background color class
                echo "<tr id='row_box_" . $row['studentid'] . "' class='row-box $rowClass'>";
                // Output database row with buttons
                echo "<td>".$row["studentid"]."</td>";
                echo "<td class='editable ' data-field='name'>".$row["name"]."</td>";
                echo "<td class='editable' data-field='ic'>".$row["ic"]."</td>";
                echo "<td class='editable' data-field='matric'>".$row["matric"]."</td>";
                echo "<td class='editable' data-field='prog'>".$row["prog"]."</td>";
                echo "<td class='editable' data-field='race'>".$row["race"]."</td>";
                echo "<td class='editable' data-field='gender'>".$row["gender"]."</td>";
                echo "<td class='editable' data-field='cgpa'>".$row["cgpa"]."</td>";
                echo "<td class='editable' data-field='phnum'>".$row["phnum"]."</td>";
                echo "<td class='editable' data-field='email'>".$row["email"]."</td>";

                echo "<td>";
                echo "<button class='edit-buttons edit' id='editButton_" . $row['studentid'] . "' onclick=\"editStudent('" . $row['studentid'] . "')\"><i class=\"fas fa-pencil\"></i></button>";
                echo "<button class='edit-buttons save' id='saveButton_" . $row['studentid'] . "' style='display:none;' onclick=\"saveStudent('" . $row['studentid'] . "')\"><i class=\"bi bi-wrench\"></i></button>";
                echo "<button class='edit-buttons cancel' id='cancelButton_" . $row['studentid'] . "' style='display:none;' onclick=\"cancelEdit('" . $row['studentid'] . "')\"><i class=\"bi bi-x-octagon\"></i></button>";
                echo "</td>";
                echo "</tr>";
                

                // Output the corresponding Excel row if matched
                if ($matchingExcelRow !== null) {
                    echo "<tr id='$excelRowBoxId' class='row-box $rowClass'>"; // Apply the same rowClass for Excel rows
                    echo "<td></td>"; // Empty cell for studentid
                    echo "<td>".$matchingExcelRow["name"]."</td>";
                    echo "<td>".$matchingExcelRow["ic"]."</td>";
                    echo "<td>".$matchingExcelRow["matric"]."</td>";
                    echo "<td>".$matchingExcelRow["prog"]."</td>";
                    echo "<td>".$matchingExcelRow["race"]."</td>";
                    echo "<td>".$matchingExcelRow["gender"]."</td>";
                    echo "<td>".$matchingExcelRow["cgpa"]."</td>";
                    echo "<td>".$matchingExcelRow["phnum"]."</td>";
                    echo "<td>".$matchingExcelRow["email"]."</td>";
                    echo "<td></td>"; // Empty cells for the buttons
                    echo "</tr>";
                } elseif (!$exactMatch && !$partialMatch) {
                    // Display a message row for the unmatched database row
                    echo "<tr class='row-box unmatched-row'>";
                    echo "<td colspan='11'>No matching data found in Excel for the student's name, matric number or IC</td>";
                    echo "</tr>";
                }
            }
            }
        }
        echo "</table>";

        // Close the database connection
        $conn->close();
        ?>
    </div>
</div>

<script>

        function openModal() {
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
// Close modal when clicking outside of the modal content
window.onclick = function(event) {
    const modal = document.getElementById('pdfModal');
    if (event.target == modal) {
        closeModal();
    }
}
    // Function to add event listeners to all verify buttons
    function addVerifyButtonListeners() {
        var verifyButtons = document.querySelectorAll('.verify-button');
        verifyButtons.forEach(function(button) {
            var studentId = button.dataset.studentId;
            button.addEventListener('click', function() {
                verifyStudent(studentId);
            });
        });
    }

    // Function to update the background color of the button based on verification status
    function updateButtonColor(studentId, newVerifyStatus) {
        var buttonElement = document.getElementById('verifyButton_' + studentId);
        if (newVerifyStatus == '1') {
            // Update background color for verified status
            buttonElement.style.backgroundColor = 'green';
        } else {
            // Update background color for unverified status
            buttonElement.style.backgroundColor = 'red';
        }
    }


    function editStudent(studentId) {
        var row = document.getElementById('row_box_' + studentId);
        var cells = row.querySelectorAll('.editable');

        // Hide the Edit button
        row.querySelector('.edit-buttons.edit').style.display = 'none';

        cells.forEach(function(cell) {
            // Store the original value in data-original-value attribute
            if (!cell.hasAttribute('data-original-value')) {
                cell.setAttribute('data-original-value', cell.textContent);
            }

            var input = document.createElement('input');
            input.type = 'text';
            input.value = cell.textContent;
            input.setAttribute('data-field', cell.getAttribute('data-field'));
            input.classList.add('resizable');
            cell.innerHTML = '';
            cell.appendChild(input);
            adjustInputWidth(input);
        });

    // Show Save and Cancel buttons within the same row
    row.querySelector('.edit-buttons.save').style.display = 'inline';
    row.querySelector('.edit-buttons.cancel').style.display = 'inline';
    }

    function adjustInputWidth(input) {
    // Create a temporary span element to measure the text width
    var tempSpan = document.createElement('span');
    tempSpan.style.visibility = 'hidden';
    tempSpan.style.whiteSpace = 'pre';
    tempSpan.style.fontSize = input.style.fontSize;
    document.body.appendChild(tempSpan);

    // Set the span's text to the input's value
    tempSpan.textContent = input.value;

    // Get the width of the text
    var textWidth = tempSpan.offsetWidth;

    // Remove the temporary span from the document
    document.body.removeChild(tempSpan);

    // Set the input's width to the text width plus some extra padding
    input.style.width = (textWidth + 20) + 'px'; // Adjust the padding as needed
}

    function saveStudent(studentId) {
        var row = document.getElementById('row_box_' + studentId);
        var inputs = row.querySelectorAll('input');
        var formData = new FormData();

        inputs.forEach(function(input) {
            formData.append(input.getAttribute('data-field'), input.value);
        });

        formData.append('studentid', studentId);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'excelEditStudent.php', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    alert('Success: Student data updated successfully.');
                    location.reload(); // Reload the page after 3 seconds
                } else {
                    alert('Error: Failed to update student data.');
                }
            }
        };
        xhr.send(formData);
    }

    function cancelEdit(studentId) {
        var row = document.getElementById('row_box_' + studentId);
        var cells = row.querySelectorAll('.editable');

        cells.forEach(function(cell) {
            var originalValue = cell.getAttribute('data-original-value');
            if (originalValue !== null) {
                // Revert to the original cell content
                cell.textContent = originalValue;
            }
            // Remove the input element if it exists
            var input = cell.querySelector('input');
            if (input) {
                cell.removeChild(input);
            }
        });

        // Show the Edit button again
        row.querySelector('.edit-buttons.edit').style.display = 'inline';

    // Hide the Save Edit and Cancel Edit buttons within the same row
    row.querySelector('.edit-buttons.save').style.display = 'none';
    row.querySelector('.edit-buttons.cancel').style.display = 'none';
    }

// Function to handle delete confirmation
function confirmDelete(studentId) {
    var userConfirmed = confirm('Are you sure you want to delete this student record?');
    if (userConfirmed) {
        deleteStudent(studentId); // Call the deleteStudent function if confirmed
    }
}

// Function to delete student
function deleteStudent(studentId) {
    var formData = new FormData();
    formData.append('delete_studentid', studentId);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'excelEditStudent.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                alert('Deleted! Student has been deleted.');
                location.reload(); // Reload the page to reflect the changes
            } else {
                alert('Error: Failed to delete student.');
            }
        }
    };
    xhr.send(formData);
}

    // Function to update the uploaded files list
    function updateUploadedExcelList() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'excelFileGet.php', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var files = JSON.parse(xhr.responseText);
                    var fileList = document.getElementById('uploadedExcelList');
                    fileList.innerHTML = ''; // Clear previous list
                    files.forEach(function(file, index) {
                        var listItem = document.createElement('li');
                        listItem.id = 'file-' + index; // Ensure a unique ID for each list item
                        listItem.innerHTML = file + ' <a href="excelDownloadFile.php?file=' + file + '" download>Download</a> <a href="#" onclick="deleteFile(\'' + file + '\', \'file-' + index + '\')">Delete</a>';
                        fileList.appendChild(listItem);
                    });
                } else {
                    console.error('Failed to retrieve uploaded files.');
                }
            }
        };
        xhr.send();
    }

// Function to handle file deletion
function deleteFile(fileName, elementId) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'excelDeleteFile.php?file=' + fileName, true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var listItem = document.getElementById(elementId);
                if (listItem) {
                    listItem.remove(); // Remove the specific list item
                }
                Swal.fire('Deleted!', 'File has been deleted.', 'success');
            } else {
                Swal.fire('Error', 'Failed to delete file.', 'error');
            }
        }
    };
    xhr.send();
}
    // Reset the file input field and update the uploaded files list on page load
    window.onload = function() {
        document.getElementById('uploadForm').reset();
        updateUploadedExcelList();
    };

    // Function to filter table rows based on search input
    function filterTable() {
        var searchInput = document.getElementById('searchBox').value.toLowerCase();
        var rows = document.querySelectorAll('.row-box'); // Select all row-box elements

        rows.forEach(function(rowBox) {
            var match = false;
            var cells = rowBox.getElementsByTagName('td');

            for (var i = 0; i < cells.length; i++) {
                var cellText = cells[i].textContent || cells[i].innerText;
                if (cellText.toLowerCase().indexOf(searchInput) > -1) {
                    match = true;
                    break;
                }
            }

            if (match) {
                rowBox.style.display = ''; // Show row box if match found
            } else {
                rowBox.style.display = 'none'; // Hide row box if no match
            }
        });
    }

document.addEventListener('DOMContentLoaded', function() {
    // Reset the file input field and update the uploaded files list on page load
    document.getElementById('uploadForm').reset();
    updateUploadedExcelList(); // Update the uploaded files list on load
});
</script>

</body>
</html>