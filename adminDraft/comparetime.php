<?php
    // Due date and time string from the database
    $due_date_time_str = "04/21/2024 6:00 PM";

    // Convert due date and time string to a DateTime object
    $due_date_time = DateTime::createFromFormat('m/d/Y g:i A', $due_date_time_str);

    // Current date and time
    $current_date_time = new DateTime();

    // Check if current date and time is after the due date and time
    if ($current_date_time > $due_date_time) {
        echo "Over due date";
    } else {
        echo "Still behind";
    }
?>