<?php

// Fetch categories for dropdown
$stmt = $conn->prepare("SELECT * FROM categories");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php foreach ($categories as $row) { ?>
        <div class="modal fade" id="editeCategoryModal<?php echo htmlspecialchars($row['id'] ?? ''); ?>" tabindex="-1" role="dialog" aria-labelledby="editeCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editeCategoryModalLabel">Edit Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="container" style="height: 330px;">
                        <!-- Edit Category Form -->
                        <form action="function.php?id=<?= htmlspecialchars($id) ?>" method="post">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($row['id']) ?>">

                            <!-- Category Title -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Category Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($row['title']) ?>" required>
                            </div>

                            <!-- Meta Description -->
                            <div class="mb-3">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea class="form-control" id="meta_description" name="meta_description" rows="3" required><?= htmlspecialchars($row['meta_description']) ?></textarea>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" name="update-categories" class="btn btn-primary">Update Category</button>
                            <a aria-hidden="true" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Cancel</a>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    <?php } ?>
    <?php include 'components/js.php'; ?>
</body>

</html>