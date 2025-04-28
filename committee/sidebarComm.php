
<!DOCTYPE html>
<html lang="en">
<?php
include ('../config/db_connect.php');
include './logincheckComm.php';
if (!isset($sidebarIncluded)) {
    $sidebarIncluded = true;
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FTKEN IDP SYSTEM</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    integrity="sha512-fDnh8MPtZZ2ayE8D6f6fvMm8eEaMYPeMgpIH93nUuO8hjO7w/JjrGzrj9oH9W/zB84r+JUtYW+lpPF9LCJLqKQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.2/font/bootstrap-icons.min.css">    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://kit.fontawesome.com/4c7903acb1.js" crossorigin="anonymous"></script>

</head>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins&display=swap');

    *,
    ::after,
    ::before {
        box-sizing: border-box;
    }

    body {
        font-family: 'Poppins', sans-serif;
        font-size: 20px;
        /* 14pixel */
        opacity: 1;
        overflow-y: scroll;
        margin: 0;
    }

    a {
        cursor: pointer;
        text-decoration: none;
        font-family: 'Poppins', sans-serif;
    }

    li {
        list-style: none;
    }

    h4 {
        font-family: 'Poppins', sans-serif;
        font-size: 1.275rem;
        color: var(--bs-emphasis-color);
    }

    /* Layout for dashboard skeleton*/
    .wrapper {
        align-items: stretch;
        display: flex;
        width: 100%;
    }

    #sidebar {
        max-width: 320px;
        min-width: 320px;
        background: var(--bs-dark);
        transition: all 0.35s ease-in-out;
        overflow-y: auto;
    }

    .main {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        min-width: 0;
        overflow: hidden;
        transition: all 0.35s ease-in-out;
        width: 100%;
        background: var(--bs-dark-bg-subtle);
    }

    .sidebar-logo {
        padding: 1.15rem;
    }

    .sidebar-logo a {
        color: #e9ecef;
        font-size: 35px;
        font-weight: 600;
    }

    .sidebar-nav {
        flex-grow: 1;
        list-style: none;
        margin-bottom: 0;
        padding-left: 0;
        margin-left: 0;
    }

    .sidebar-header {
        color: #e9ecef;
        font-size: 20px;
        padding: 1.5rem 1.5rem 0.375rem;
    }

    a.sidebar-link {
        padding: .625rem 1.625rem;
        color: #e9ecef;
        position: relative;
        display: block;
        font-size: 17px;
    }

    .sidebar-link[data-bs-toggle="collapse"]::after {
        border: solid;
        border-width: 0 .075rem .075rem 0;
        content: "";
        display: inline-block;
        padding: 2px;
        position: absolute;
        right: 1.5rem;
        top: 1.4rem;
        transform: rotate(-135deg);
        transition: all .2s ease-out;
    }

    .sidebar-link[data-bs-toggle="collapse"].collapsed::after {
        transform: rotate(45deg);
        transition: all .2s ease-out;
    }

    .avatar {
        height: 40px;
        width: 40px;
    }

    .navbar-expand .navbar-nav {
        margin-left: auto;
    }

    .content {
        flex: 1;
        max-width: 100vw;
        width: 100vw;
    }

    @media (min-width:768px) {
        .content {
            max-width: auto;
            width: auto;
        }
    }

    .card {
        box-shadow: 0 0 .875rem 0 rgba(34, 46, 60, .05);
        margin-bottom: 24px;
    }

    .illustration {
        background-color: var(--bs-primary-bg-subtle);
        color: var(--bs-emphasis-color);
    }

    .illustration-img {
        max-width: 150px;
        width: 100%;
    }

    .title-container {
        border-bottom: 3px solid black;
        width: 80%;
        text-align: center;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
        padding: 15px 0;
    }

    #sidebar.collapsed {
        margin-left: -320px;
    }
    .navbar-nav .nav-icon img {
            height: 40px;
            width: 40px;
        }

        /* Isolate profile link styles */
        .navbar .profile-link {
            display: flex;
            align-items: center;
            padding-left: 1rem;
            font-family: 'Poppins', sans-serif;
            font-size: 20px; /* Ensure this font size matches your desired size */
            color: #0D6EFD; /* Ensure this color matches your desired color */
            text-decoration: none; /* Ensure this prevents text underline */
            margin-top: 6px;
        }

        .navbar .profile-link img {
            margin-right: 0.5rem;
        } 

.notification-link {
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    width: 40px;
    height: 40px;
}

.bell-icon {
    font-size: 25px;
    display: block;
    line-height: 40px; /* Adjust line-height to match the height of the container */
    text-align: center;
    margin-top: 20px;
}

.dot {
    height: 10px;
    width: 10px;
    background-color: red;
    border-radius: 50%;
    position: absolute;
    top: 5px;
    right: 5px;
    display: none; /* Initially hidden */
}

.dropdown-menu {
    width: auto;
    max-height: 250px;
    overflow-y: auto;
    top: 50px !important; /* Adjust this based on the actual position */
}

@media (max-width: 768px) {
    .navbar .profile-link {
            display: flex;
            align-items: center;
            padding-left: 1rem;
            font-family: 'Poppins', sans-serif;
            font-size: 16px; /* Ensure this font size matches your desired size */
            color: #0D6EFD; /* Ensure this color matches your desired color */
            text-decoration: none; /* Ensure this prevents text underline */
            margin-top: 6px;
        }
        a.sidebar-link {
            padding: .625rem 1.625rem;
            color: #e9ecef;
            position: relative;
            display: block;
            font-size: 14px;
        }

        .dropdown-menu {
            width: 70vw;
            max-width: 90vw;
            font-size:13px;
            left: 70% !important;
            transform: translateX(-70%);
        }

        #sidebar {
            max-width: 80%;
            min-width: 80%;
            position: fixed;
            background: var(--bs-dark);
            transition: all 0.35s ease-in-out;
            top: 0;
            height: 100%;
            z-index: 1000;
            margin-left: 0;
        }

        #sidebar.collapsed {
            margin-left: -80%;
        }

        .main {
            margin-left: 0;
        }

        .title-container {
            width: 100%;
        }

        .sidebar-logo a {
            font-size: 24px; /* Adjusted for smaller screens */
        }

        .sidebar-header {
            font-size: 16px; /* Adjusted for smaller screens */
        }

        .sidebar-link {
            font-size: 14px; /* Adjusted for smaller screens */
            padding: .5rem 1.25rem;
        }

        .navbar .profile-link {
            font-size: 14px; /* Adjusted for smaller screens */
            margin-top: 0;
        }

        .bell-icon {
            font-size: 20px; /* Adjusted for smaller screens */
            margin-top: 6px;
            margin-right: -15px;
        }
    }
</style>
<body>
    <!-- Navigation bar in here -->
    <div class="wrapper">
    <aside id="sidebar">
            <!-- Content for Sidebar -->
            <div class="h-100">
                <div class="sidebar-logo">
                    <a href="./home.php">FTKEN IDP</a>
                </div>
                <ul class="sidebar-nav">
                    <li class="sidebar-header">
                        MAIN NAVIGATION
                    </li>
                    <li class="sidebar-item">
                    <a href="./setting.php" class="sidebar-link">
                            <i class="fa-solid fa-user pe-2" aria-hidden="true"></i>
                            User Profile
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="commManage.php" class="sidebar-link">
                            <i class="bi bi-person-vcard-fill pe-2"></i>
                            Accounts Verify & Modify
                        </a>
                    </li>

                    <li class="sidebar-item">
                        <a href="excelManage.php" class="sidebar-link">
                            <i class="bi bi-person-check pe-2"></i>
                            Student Data Check
                        </a>
                    </li>

                <li class="sidebar-item">
                    <a href="./groupSystemFetch.php" class="sidebar-link">
                        <i class="bi bi-people-fill pe-2"></i>
                        Student Grouping
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="./listGroup.php" class="sidebar-link">
                        <i class="bi bi-list-ol pe-2"></i>
                        List of Groups
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="./interExaminer.php" class="sidebar-link">
                        <i class="bi bi-person-workspace pe-2"></i>
                        Assign FinalReport Examiner
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="./groupAssignSV.php" class="sidebar-link">
                        <i class="bi bi-diagram-2-fill pe-2"></i>
                        Assign Exhibition Evaluator
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="./markingMM.php" class="sidebar-link">
                        <i class="bi bi-pencil-fill pe-2"></i>
                       Marking Minute Meetings
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="./viewAll.php" class="sidebar-link">
                        <i class="fa-solid fa-book-open pe-2"></i>
                        View Report & Files
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="./viewScore.php" class="sidebar-link">
                        <i class="bi bi-eye-fill pe-2"></i>
                        Review Final Score
                    </a>
                </li>

                
                <!-- <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed" data-bs-target="#title" data-bs-toggle="collapse"
                        aria-expanded="false"><i class="fa-solid fa-book-open pe-2"></i>
                        View Report & Files
                    </a>
                    <ul id="title" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                    <li class="sidebar-item">
                            <a href="./viewAll.php" class="sidebar-link">View All</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="./viewSynop.php" class="sidebar-link">1. Synopsis</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="./refTemplate.php" class="sidebar-link">2. Minute Meeting</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="./viewAll.php" class="sidebar-link">3. Proposal</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="./refTemplate.php" class="sidebar-link">4. Final Reports</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="./refTemplate.php" class="sidebar-link">5. Peer Review Score</a>
                        </li>
                    </ul>
                </li> -->
                <li class="sidebar-item">
                    <a href="./setDueDate.php" class="sidebar-link">
                        <i class="bi bi-calendar-x-fill pe-2"></i>
                        Set Deadline 
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="./themeEdit.php" class="sidebar-link">
                        <i class="bi bi-pencil-square pe-2"></i>
                        Edit Theme 
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="./rubricUpload.php" class="sidebar-link">
                        <i class="fa-solid fa-comment pe-2"></i>
                        Upload Rubric
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../login.php" class="sidebar-link">
                        <i class="fa-solid fa-key pe-2"></i>
                        Log Out
                    </a>
                </li>
            </ul>
        </div>
        <?php
} // End of if block
?>
</aside>
<!-- Main content in here -->
    <div class="main">
<!-- Navigation bar part -->

        <nav class="navbar navbar-expand px-3 border-bottom ftken-idp-system-navbar">
            <button class="btn" id="sidebar-toggle" type="button" style="box-shadow: none !important;">
                <span class="navbar-toggler-icon"></span>
            </button>
            
    <div class="navbar-collapse navbar">

        <ul class="navbar-nav">
        <li class="nav-item dropdown">
            <a href="#" class="nav-icon notification-link" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell-fill bell-icon"></i>
                <span id="notificationDot" class="dot"></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" id="notificationList">
                <!-- Notifications will be dynamically added here -->
            </ul>
        </li>
                    <li class="nav-item">
                        <a href="./setting.php" class="nav-icon profile-link">
                            <img src="../assets/images/c2/profile.png" class="avatar img-fluid rounded" alt="">
                            <?php echo $_SESSION['username']; ?>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const MAX_MESSAGES = 10;
    const notificationDot = document.getElementById('notificationDot');
    const notificationList = document.getElementById('notificationList');

function updateNotificationList(notifications) {
    notificationList.innerHTML = '';
    if (notifications.length > 0) {
        notifications.slice(0, MAX_MESSAGES).forEach(notification => {
            const li = document.createElement('li');
            li.className = 'dropdown-item';
            li.textContent = notification.message;
            notificationList.prepend(li);
        });
    } else {
        const noNotification = document.createElement('li');
        noNotification.className = 'dropdown-item';
        noNotification.textContent = 'No notification.';
        notificationList.appendChild(noNotification);
    }
}

    let storedNotifications = JSON.parse(localStorage.getItem('notifications')) || [];
    updateNotificationList(storedNotifications);

    async function fetchNotifications() {
        try {
            const response = await fetch('notificationFetch.php');
            
            if (response.status === 204) {
                return;
            }

            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }

            const newNotifications = await response.json();
            if (newNotifications.length > 0) {
                notificationDot.style.display = 'block';
                storedNotifications = [...newNotifications, ...storedNotifications].slice(0, MAX_MESSAGES);
                localStorage.setItem('notifications', JSON.stringify(storedNotifications));
                updateNotificationList(storedNotifications);
            }
        } catch (error) {
            console.error('Error fetching notifications:', error);
        }
    }

    document.getElementById('notificationDropdown').addEventListener('click', async function() {
        notificationDot.style.display = 'none';
        localStorage.removeItem('showNotificationDot');

        try {
            // Mark notifications as read
            const markReadResponse = await fetch('notificationMarkRead.php');
            if (!markReadResponse.ok) {
                throw new Error('Failed to mark notifications as read');
            }
        } catch (error) {
            console.error('Error marking notifications as read:', error);
        }
    });

    <?php if (isset($_SESSION['message'])): ?>
    const message = "<?php echo $_SESSION['message']; ?>";
    storedNotifications.unshift({ message });  // Use unshift to add at the beginning
    storedNotifications = storedNotifications.slice(0, MAX_MESSAGES);
    localStorage.setItem('notifications', JSON.stringify(storedNotifications));
    updateNotificationList(storedNotifications);
    notificationDot.style.display = 'block';
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

fetchNotifications();
setInterval(fetchNotifications, 5000); // Poll every 5 seconds
                });

                document.addEventListener("DOMContentLoaded", function () {
                    const sidebarToggle = document.querySelector("#sidebar-toggle");
                    sidebarToggle.addEventListener("click", function () {
                        document.querySelector("#sidebar").classList.toggle("collapsed")
                    });

// Close sidebar if clicking outside of it on mobile devices
document.addEventListener("click", function (event) {
    const sidebar = document.querySelector("#sidebar");
    const toggleButton = document.querySelector("#sidebar-toggle");

    if (!sidebar.contains(event.target) && !toggleButton.contains(event.target)) {
        sidebar.classList.add("collapsed");
    }
                    });

// Ensure the sidebar doesn't close when scrolling
sidebar.addEventListener('touchmove', function(event) {
    event.stopPropagation();
}, { passive: true });

sidebar.addEventListener('scroll', function(event) {
    event.stopPropagation();
}, { passive: true });

        });
        </script>
        <script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('collapsed');
    }

    function handleResize() {
        const sidebar = document.getElementById('sidebar');
        if (window.innerWidth <= 768) {
            sidebar.classList.add('collapsed');
        } else {
            sidebar.classList.remove('collapsed');
        }
    }

    // Check screen size on page load
    document.addEventListener('DOMContentLoaded', handleResize);

    // Check screen size on window resize
    window.addEventListener('resize', handleResize);
</script>
</body>
</html>