<?php

// Fetch categories for dropdown
$stmt = $conn->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
</head>

<body>
    <?php foreach ($users as $user) { ?>

        <div class="modal fade" id="editUserModal<?php echo htmlspecialchars($user['id'] ?? ''); ?>" tabindex="-1" role="dialog" aria-labelledby="editeUserModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editeUserModalLabel">Edite Users</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">X</span>
                        </button>
                    </div>
                    <div class="container" style="height: 459px;">
                        <form action="function.php" method="post">
                            <!-- Username -->
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>"
                                    required>
                            </div>

                            <!-- Password -->
                            <div class="mb-3 ">
                                <label for="password" class="form-label">Password</label>
                                <input type="" class="form-control" id="password" name="password"
                                    value="<?php echo htmlspecialchars($user['password'] ?? ''); ?>"

                                    required>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>"

                                    required>
                            </div>

                            <!-- Role -->
                            <div class="mb-3">
                                <label for="role" class="form-label">Role</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="<?php echo htmlspecialchars($user['role'] ?? ''); ?>">-- ជ្រើសរើសតួនាទី --</option>
                                    <option value="Admin" <?php if (isset($user['role']) && $user['role'] === 'Admin') echo 'selected'; ?>>Admin</option>
                                    <option value="User" <?php if (isset($user['role']) && $user['role'] === 'User') echo 'selected'; ?>>User</option>
                                </select>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" name="update-user" class="btn btn-primary">Update User</button>
                            <a aria-hidden="true" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cancel</a>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

</body>

</html>