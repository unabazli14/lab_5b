<?php include('header.php'); ?>
<div class="card mt-5">
    <div class="card-header bg-info text-white text-center">
        <h3>User Dashboard</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Matric</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $conn = new mysqli("localhost", "root", "", "Lab_5b");
                $result = $conn->query("SELECT matric, name, role FROM users");
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['matric']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['role']}</td>
                        <td>
                            <a href='update.php?matric={$row['matric']}' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='delete.php?matric={$row['matric']}' class='btn btn-danger btn-sm'>Delete</a>
                        </td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

