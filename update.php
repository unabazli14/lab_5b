<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}

// Database connection (instead of including db_connection.php)
$servername = "localhost"; // Your database server
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "Lab_5b"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data for updating
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['matric'])) {
    $matric = htmlspecialchars(trim($_GET['matric']));

    // Query to fetch user data
    $sql = "SELECT * FROM users WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc(); // Fetch user data
    } else {
        echo "<div class='alert alert-danger text-center'>User not found.</div>";
        exit(); // Stop script execution
    }

    $stmt->close();
} elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
    echo "<div class='alert alert-danger text-center'>Invalid request: Matric is required.</div>";
    exit();
}

// Handle form submission for updating user data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $role = htmlspecialchars(trim($_POST['role']));
    $matric = htmlspecialchars(trim($_POST['matric']));

    if (empty($name) || empty($role) || empty($matric)) {
        $error_message = "All fields are required.";
    } else {
        // Query to update user data
        $sql = "UPDATE users SET name = ?, role = ? WHERE matric = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $role, $matric);

        if ($stmt->execute()) {
            header("Location: dashboard.php?message=User updated successfully");
            exit();
        } else {
            $error_message = "Failed to update user.";
        }

        $stmt->close();
    }
    $conn->close();
}
?>
<?php include('header.php'); ?>
<div class="card mx-auto mt-5" style="max-width: 600px;">
    <div class="card-header bg-warning text-white text-center">
        <h3>Update User</h3>
    </div>
    <div class="card-body">
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success text-center"><?= $success_message; ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger text-center"><?= $error_message; ?></div>
        <?php endif; ?>
        <form method="post" class="row g-3">
            <div class="col-12">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required>
            </div>
            <div class="col-12">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="student" <?= (isset($user['role']) && $user['role'] === 'student') ? 'selected' : '' ?>>Student</option>
                    <option value="lecturer" <?= (isset($user['role']) && $user['role'] === 'lecturer') ? 'selected' : '' ?>>Lecturer</option>
                </select>
            </div>
            <input type="hidden" name="matric" value="<?= htmlspecialchars($user['matric'] ?? '') ?>">
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-success w-100">Update</button>
            </div>
        </form>
    </div>
</div>
