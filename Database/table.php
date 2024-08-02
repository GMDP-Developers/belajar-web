<?php
include 'dbconn.php';

// Initialize variables
$username = '';
$password = '';
$role = '';
$update = false;
$id = 0;

// Handle form submission for adding or updating records
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if (isset($_POST['save'])) {
        // Add new record
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
        $stmt = $conn->prepare("INSERT INTO login (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $username, $hashed_password, $role);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['update'])) {
        // Update existing record
        $id = $_POST['id'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash the password
        $stmt = $conn->prepare("UPDATE login SET username=?, password=?, role=? WHERE id=?");
        $stmt->bind_param('sssi', $username, $hashed_password, $role, $id);
        $stmt->execute();
        $stmt->close();
    }
}

// Handle request for deleting a record
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM login WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
}

// Handle request for editing a record
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $update = true;
    $result = $conn->query("SELECT * FROM login WHERE id=$id");

    if ($result->num_rows) {
        $row = $result->fetch_array();
        $username = $row['username'];
        $role = $row['role'];
        // Note: For security reasons, we do not fetch the password to display
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Management Dashboard</title>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                background-color: #f8f9fa;
                transition: background-color 0.1s ease;
            }
            .container {
                margin-top: 50px;
            }
            .card {
                margin-bottom: 30px;
            }
            .table {
                margin-top: 20px;
            }
        </style>
    </head>
    <body>

    <div class="container">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">User Management</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <input type="hidden" name="id" value="<?= $id; ?>">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= $username; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="Admin" <?= $role == 'Admin' ? 'selected' : ''; ?>>Admin</option>
                            <option value="Reseller" <?= $role == 'Reseller' ? 'selected' : ''; ?>>Reseller</option>
                            <option value="Client" <?= $role == 'Client' ? 'selected' : ''; ?>>Client</option>
                        </select>
                    </div>
                    <div>
                        <?php if ($update): ?>
                            <button type="submit" class="btn btn-warning" name="update">Update</button>
                        <?php else: ?>
                            <button type="submit" class="btn btn-success" name="save">Save</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">User List</h4>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $conn->query("SELECT * FROM login");
                        while ($row = $result->fetch_assoc()):
                        ?>
                            <tr>
                                <td><?= $row['id']; ?></td>
                                <td><?= $row['username']; ?></td>
                                <td><?= $row['role']; ?></td>
                                <td>
                                    <a href="table.php?edit=<?= $row['id']; ?>" class="btn btn-info btn-sm">Edit</a>
                                    <a href="table.php?delete=<?= $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            // Array of bright colors
            const colors = ['#e83838', '#ee9938', '#e7e139', '#64eb3a', '#3be858', '#38e0e6', '#34a7f5', '#9b38f1', '#e233ee', '#ef3aad'];

            // Function to change the background color
            function changeBackgroundColor() {
                const randomColor = colors[Math.floor(Math.random() * colors.length)];
                document.body.style.backgroundColor = randomColor;
            }

            // Change background color every 100 milliseconds
            setInterval(changeBackgroundColor, 100);
        </script>
    </body>
</html>
