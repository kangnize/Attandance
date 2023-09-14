<?php
   session_start();
   require 'server.php';
   $db = new db();

   $sql = "SELECT * FROM employees";
   $db->execute($sql);
   $employees = $db->fetch_results();

   if (isset($_GET['delete_id'])) {
        $deleteId = $_GET['delete_id'];
        $db->query = "DELETE FROM employees WHERE id = :delete_id";
        $db->stmt = $db->con->prepare($db->query);
        $db->stmt->bindParam(':delete_id', $deleteId);
        $db->stmt->execute();
        
        // Redirect to the product listing page after deletion
        header("Location: main.php");
        exit();
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/main.css"></head>
<body>
    <style>
        body {
            background-image: url("images/bri.jpg");
        }
    </style>
    <div class="container" my-5>
        <br>
        <h2>List Of Employee</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Department</th>
                    <th>Date Joined</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($employees as $employee) : ?>
                    <tr>
                        <td><?php echo $employee['id']; ?></td>
                        <td><?php echo $employee['name']; ?></td>
                        <td><?php echo $employee['email']; ?></td>
                        <td><?php echo $employee['phone']; ?></td>
                        <td><?php echo $employee['department']; ?></td>
                        <td><?php echo $employee['created_at']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>