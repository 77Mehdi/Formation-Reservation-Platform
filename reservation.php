<?php
session_start();
include 'db.php';
require 'Formation.php';

$formationModel = new Formation($conn);


$reservations = $formationModel->getAllReservations();
?>

<?php include 'nav.php'; ?>

<div class="container mt-5">
    <h2>All Reservations</h2>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>duration</th>
                <th>Formation</th>
                <th>Reserved At</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $reservations->fetch(PDO::FETCH_ASSOC)): ?>

                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['duration']; ?></td>
                    <td><?php echo $row['formation_name']; ?></td>
                    <td><?php echo $row['reserved_at']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>