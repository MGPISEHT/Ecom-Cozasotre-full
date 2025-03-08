<?php
include("./configs/DBconnect.php"); // Include database connection
include 'function.php'; // Include functions
session_start(); // Start session for managing login

if (!isset($_GET['id'])) {
    header("Location: manageUsers.php"); // Redirect to manageUsers if no user id is provided
    exit();
}

$id = $_GET['id'];

// Fetch the user from the database
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    // If user doesn't exist, redirect to manageUsers page
    header("Location: manageUsers.php");
    exit();
}

// Update user logic
if (isset($_POST['update-user'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);
    $status = trim($_POST['status']);

    if (empty($username) || empty($email) || empty($role) || empty($status)) {
        $error = "All fields are required!";
    } else {
        // Update the user in the database
        $stmt = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ?, status = ? WHERE id = ?");
        if ($stmt->execute([$username, $email, $role, $status, $id])) {
            $success = "User updated successfully!";
            header("Location: manageUsers.php"); // Redirect back to manage users after update
            exit();
        } else {
            $error = "Error: Unable to update user.";
        }
    }
}

?>

<!doctype html>
<html lang="en">

<?php include 'components/head.php'; ?>
<title>Edit User</title>

<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical">
        <!-- Sidebar -->
        <?php include 'components/sidebarNavigation.php'; ?>

        <!-- Main Wrapper -->
        <div class="body-wrapper">
            <!-- Header -->
            <?php include 'components/header.php'; ?>

            <div class="container-fluid">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold mb-4">Edit User</h5>

                        <!-- Display Errors or Success Messages -->
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php elseif (isset($success)): ?>
                            <div class="alert alert-success"><?= $success ?></div>
                        <?php endif; ?>

                        <!-- Edit User Form -->
                        <form action="editUser.php?id=<?= $user['id'] ?>" method="post">
                            <!-- Username -->
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>

                            <!-- Role -->
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="user" <?= ($user['role'] == 'user') ? 'selected' : '' ?>>User</option>
                                    <option value="admin" <?= ($user['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </div>

                            <!-- Status -->
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="active" <?= ($user['status'] == 'active') ? 'selected' : '' ?>>Active</option>
                                    <option value="inactive" <?= ($user['status'] == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                                </select>
                            </div>

                            <button type="submit" name="update-user" class="btn btn-primary">Update User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'components/js.php' ?>
</body>

</html>
