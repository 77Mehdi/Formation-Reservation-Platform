<?php
// Include the database connection
include 'db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Formation
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Get all formations
    public function getAllFormations()
    {
        $query = "SELECT * FROM formations";
        $stmt = $this->conn->query($query);
        return $stmt;
    }

    // Reserve a formation
    // Reserve a formation
    public function reserveFormation($userId, $formationId)
    {
        // Start transaction to ensure both actions (mark formation reserved and insert into reservations) are atomic
        $this->conn->beginTransaction();

        try {
            // Mark the formation as reserved
            $query = "UPDATE formations SET reserved = TRUE WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $formationId, PDO::PARAM_INT);
            $stmt->execute();

            // Insert reservation into the reservations table
            $query = "INSERT INTO reservations (user_id, formation_id) VALUES (:user_id, :formation_id)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':formation_id', $formationId, PDO::PARAM_INT);
            $stmt->execute();

            // Commit the transaction
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            // Rollback in case of an error
            $this->conn->rollBack();
            return false;
        }
    }
}

// Create an instance of the Formation model
$formationModel = new Formation($conn);

// Reserve Formation (via GET request)
if (isset($_GET['reserve'])) {
    $formationId = $_GET['reserve'];
    //var_dump($_SESSION['user']['id']);
    if ($formationModel->reserveFormation($_SESSION['user']['id'], $formationId)) {
        $message = "Formation reserved successfully!";
    } else {
        $message = "Error: Could not reserve formation.";
    }
}

// Fetch all formations
$formations = $formationModel->getAllFormations();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve Formations</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link href="css/style.css" rel="stylesheet">
</head>
<style>
    body {
        background: #f7f9fc;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    h2 {
        text-align: center;
        margin-bottom: 40px;
        font-weight: bold;
        color: #333;
    }

    .card {
        background: white;
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.6s forwards;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .card-title {
        font-size: 1.4rem;
        font-weight: 600;
        color: #333;
    }

    .card-text {
        font-size: 0.95rem;
        color: #555;
        margin-bottom: 10px;
    }

    .btn-warning {
        background-color: #ffc107;
        border: none;
        font-weight: 600;
        transition: background-color 0.3s ease;
    }

    .btn-warning:hover {
        background-color: #e0a800;
    }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Fade in cards with stagger */
    .col-md-4:nth-child(1) .card {
        animation-delay: 0.1s;
    }

    .col-md-4:nth-child(2) .card {
        animation-delay: 0.2s;
    }

    .col-md-4:nth-child(3) .card {
        animation-delay: 0.3s;
    }

    .alert-info {
        text-align: center;
        font-weight: 500;
    }
</style>


<body>

    <?php include 'nav.php'; ?>

    <div class="container mt-5">
        <h2>Available Formations</h2>
        <?php if (!empty($message)) echo "<p class='alert alert-info'>$message</p>"; ?>

        <!-- Display formations -->
        <div class="row">
            <?php while ($row = $formations->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['name']; ?></h5>
                            <p class="card-text"><?php echo $row['description']; ?></p>
                            <p class="card-text"><strong>Price:</strong> $<?php echo $row['price']; ?></p>
                            <p class="card-text"><strong>Duration:</strong> <?php echo $row['duration']; ?></p>
                            <p class="card-text"><strong>Type:</strong> <?php echo $row['type']; ?></p>
                            <?php if (isset($_SESSION['user'])): ?>
                                <a href="?reserve=<?php echo $row['id']; ?>" class="btn btn-warning">Reserve Formation</a>
                            <?php else: ?>
                                <a href="#" class="btn btn-warning" onclick="alert('Please login first to reserve a formation.'); return false;">Reserve Formation</a>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="js/bootstrap.js"></script>
</body>

</html>