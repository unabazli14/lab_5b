<?php
session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $conn = new mysqli("localhost", "root", "", "Lab_5b");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve and sanitize form data
    $matric = htmlspecialchars(trim($_POST["matric"]));
    $password = htmlspecialchars(trim($_POST["password"]));

    // Query to check if matric exists and matches password
    $sql = "SELECT * FROM users WHERE matric = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $matric, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify matric and password
    if ($user) {
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        header("Location: dashboard.php"); // Redirect to dashboard
        exit();
    } else {
        $error_message = "Invalid matric or password!";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!-- Login Form -->
<?php include('header.php'); ?>
<div class="card mx-auto mt-5" style="max-width: 400px;">
    <div class="card-header text-center bg-success text-white">
        <h3>Login</h3>
    </div>
    <div class="card-body">
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger text-center"><?= $error_message; ?></div>
        <?php endif; ?>
        <form method="post">
            <div class="mb-3">
                <label for="matric" class="form-label">Matric</label>
                <input type="text" class="form-control" id="matric" name="matric" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </div>
            <div class="text-center mt-3">
                <a href="registration.php" class="text-decoration-none">Don't have an account? Register here</a>
            </div>
        </form>
    </div>
</div>

