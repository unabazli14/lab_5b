<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $conn = new mysqli("localhost", "root", "", "Lab_5b");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve form data
    $matric = htmlspecialchars(trim($_POST["matric"]));
    $name = htmlspecialchars(trim($_POST["name"]));
    $role = htmlspecialchars(trim($_POST["role"]));
    $password = htmlspecialchars(trim($_POST["password"]));

    // Prepare and execute the SQL query
    $sql = "INSERT INTO users (matric, name, role, password) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Bind parameters (4 fields = 4 placeholders)
    $stmt->bind_param("ssss", $matric, $name, $role, $password);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect to login.php after successful registration
        header("Location: login.php");
        exit();
    } else {
        $error_message = "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!-- Registration Form -->
<?php include('header.php'); ?>
<div class="card mx-auto mt-5" style="max-width: 600px;">
    <div class="card-header text-center bg-primary text-white">
        <h3>Register</h3>
    </div>
    <div class="card-body">
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger text-center"><?= $error_message; ?></div>
        <?php endif; ?>
        <form method="post" class="row g-3">
            <div class="col-12">
                <label for="matric" class="form-label">Matric</label>
                <input type="text" class="form-control" id="matric" name="matric" required>
            </div>
            <div class="col-12">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="col-12">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="" selected disabled>Choose a role</option>
                    <option value="Student">Student</option>
                    <option value="Lecturer">Lecturer</option>
                </select>
            </div>
            <div class="col-12">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-success w-100">Register</button>
            </div>
        </form>
    </div>
</div>