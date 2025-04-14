<?php
session_start();
include 'db.php';

require 'Formation.php'; // Include the model

// Check if user is logged in as admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header('Location: login.php');
    exit();
}

// Create an instance of the Formation model
$formationModel = new Formation($conn);

// Add Formation
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $type = $_POST['type'];  // Type of formation

    if ($formationModel->addFormation($name, $description, $price, $duration, $type)) {
        $message = "Formation added successfully!";
    } else {
        $message = "Error: Could not add formation.";
    }
}

// Update Formation
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $duration = $_POST['duration'];
    $type = $_POST['type'];

    if ($formationModel->updateFormation($id, $name, $description, $price, $duration, $type)) {
        $message = "Formation updated successfully!";
    } else {
        $message = "Error: Could not update formation.";
    }
}

// Delete Formation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if ($formationModel->deleteFormation($id)) {
        $message = "Formation deleted successfully!";
    } else {
        $message = "Error: Could not delete formation.";
    }
}

// Fetch all formations
$formations = $formationModel->getAllFormations();

// Reserve Formation
// if (isset($_GET['reserve'])) {
//     $id = $_GET['reserve'];
//     if ($formationModel->reserveFormation($id)) {
//         $message = "Formation reserved successfully!";
//     } else {
//         $message = "Error: Could not reserve formation.";
//     }
// }

// Fetch all reservations


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Formations</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <?php include 'nav.php'; ?>

    <div class="container mt-5">
        <div class="text-right">
            <a href="reservation.php" class="btn btn-primary">Reservation User Data</a>
        </div>

        <h2>Admin - Manage Formations</h2>
        <?php if (!empty($message)) echo "<p class='alert alert-info'>$message</p>"; ?>

        <!-- Add Formation Form -->
        <form method="POST" action="">
            <h3>Add Formation</h3>
            <div class="form-group">
                <input type="text" name="name" class="form-control" placeholder="Formation Name" required>
            </div>
            <div class="form-group">
                <textarea name="description" class="form-control" placeholder="Description" required></textarea>
            </div>
            <div class="form-group">
                <input type="number" step="0.01" name="price" class="form-control" placeholder="Price" required>
            </div>
            <div class="form-group">
                <input type="text" name="duration" class="form-control" placeholder="Duration" required>
            </div>
            <div class="form-group">
                <select name="type" class="form-control" required>
                    <option value="online">Online</option>
                    <option value="in-person">In-person</option>
                </select>
            </div>
            <button type="submit" name="add" class="btn btn-success">Add Formation</button>
        </form>

        <hr>



        <!-- Update Formation Form (for each formation) -->
        <h3>Update Formations</h3>

<div class="form-container">
    <?php while ($row = $formations->fetch(PDO::FETCH_ASSOC)): ?>
        <form method="POST" action="" class="form-item mt-3">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <div class="form-group">
                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($row['name']); ?>" required>
            </div>
            <div class="form-group">
                <textarea name="description" class="form-control" required><?php echo htmlspecialchars($row['description']); ?></textarea>
            </div>
            <div class="form-group">
                <input type="number" step="0.01" name="price" class="form-control" value="<?php echo htmlspecialchars($row['price']); ?>" required>
            </div>
            <div class="form-group">
                <input type="text" name="duration" class="form-control" value="<?php echo htmlspecialchars($row['duration']); ?>" required>
            </div>
            <div class="form-group">
                <select name="type" class="form-control" required>
                    <option value="online" <?php echo $row['type'] == 'online' ? 'selected' : ''; ?>>Online</option>
                    <option value="in-person" <?php echo $row['type'] == 'in-person' ? 'selected' : ''; ?>>In-person</option>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" name="update" class="btn btn-primary">Update Formation</button>
                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger mt-2" onclick="return confirm('Are you sure you want to delete this formation?');">Delete</a>
            </div>
        </form>
        <hr>
    <?php endwhile; ?>
</div>

    <script src="js/bootstrap.js"></script>
</body>

</html>