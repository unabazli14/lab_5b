<?php
// Include database connection
$conn = new mysqli("localhost", "root", "", "Lab_5b");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if matric is provided in the URL
if (isset($_GET['matric'])) {
    $matric = $conn->real_escape_string($_GET['matric']); // Sanitize input

    // Prepare and execute the DELETE query
    $sql = "DELETE FROM users WHERE matric = '$matric'";
    if ($conn->query($sql) === TRUE) {
        // Redirect back to the dashboard with success message
        header("Location: dashboard.php?message=User deleted successfully");
        exit();
    } else {
        // Redirect back with error message
        header("Location: dashboard.php?error=Error deleting user: " . $conn->error);
        exit();
    }
} else {
    // Redirect back with error message if no matric is provided
    header("Location: dashboard.php?error=No user selected for deletion");
    exit();
}

// Close the database connection
$conn->close();
?>
