<?php
session_start();
date_default_timezone_set('UTC');
require 'server.php';
$db = new db();

$current_date = date("Y-m-d"); 

$sql_employees = "SELECT * FROM employees";
$db->execute($sql_employees);
$employees = $db->fetch_results();

$db->query = "SELECT attendance.date, attendance.status, employees.name, employees.id, employees.email, employees.phone, employees.department
   FROM attendance
   INNER JOIN employees ON attendance.employee_id = employees.id WHERE attendance.date = :date";

$db->stmt= $db->con->prepare($db->query);
$db->stmt->bindParam(':date', $current_date, PDO::PARAM_STR);
$db->stmt->execute();
$attendances = $db->stmt->fetchAll(PDO::FETCH_ASSOC);



if (isset($_GET['present_id'])) {
    $employee_id = $_GET['present_id'];


    // Check if the employee_id exists in the attendance table
    $db->query = "SELECT COUNT(*) AS count FROM attendance WHERE employee_id = :employee_id";
    $db->stmt= $db->con->prepare($db->query);
    $db->stmt->bindParam(':employee_id', $employee_id);
    $db->stmt->execute();
    $result = $db->stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] > 0) {

        // Employee exists in attendance table, perform an update
        $update_query = "UPDATE attendance SET date = :date, status = :status WHERE employee_id = :employee_id";
        $db->stmt = $db->con->prepare($update_query);
    } else {

        // Employee does not exist in attendance table, perform an insert
        $insert_query = "INSERT INTO attendance (employee_id, date, status) VALUES (:employee_id, :date, :status)";
        $db->stmt = $db->con->prepare($insert_query);
    }

    $present = 'PRESENT';

    $db->stmt->bindParam(':employee_id', $employee_id);
    $db->stmt->bindParam(':date', $current_date);
    $db->stmt->bindParam(':status', $present);

    $db->stmt->execute();

    header("Location: attendancelist.php");
    exit();
}

if (isset($_GET['absent_id'])) {
    $employee_id = $_GET['absent_id'];

    // Check if the employee_id exists in the attendance table
    $db->query = "SELECT COUNT(*) AS count FROM attendance WHERE employee_id = :employee_id";
    $db->stmt= $db->con->prepare($db->query);
    $db->stmt->bindParam(':employee_id', $employee_id);
    $db->stmt->execute();
    $result = $db->stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] > 0) {
        // Employee exists in attendance table, perform an update
        $update_query = "UPDATE attendance SET date = :date, status = :status WHERE employee_id = :employee_id";
        $db->stmt = $db->con->prepare($update_query);
    } else {
        // Employee does not exist in attendance table, perform an insert
        $insert_query = "INSERT INTO attendance (employee_id, date, status) VALUES (:employee_id, :date, :status)";
        $db->stmt = $db->con->prepare($insert_query);
    }

    $absent = 'ABSENT';

    $db->stmt->bindParam(':employee_id', $employee_id);
    $db->stmt->bindParam(':date', $current_date);
    $db->stmt->bindParam(':status', $absent);

    $db->stmt->execute();

    header("Location: attendancelist.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance List Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/main.css">
</head>

<body>
    <style>
        body {
            background-image: url("images/bri.jpg");
        }
    </style>
    <div class="container" my-5>
        <div class="attendance_list_header">
            <h2>Roll Call</h2>
            <h2><?php echo date('d-m-Y'); ?></h2>
        </div>
        <hr>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Department</th>
                    <th>Action</th>
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
                        <td>
                            <a class='btn btn-primary btn-sm' href="?present_id=<?php echo $employee['id']; ?>">Present</a>
                            <a class='btn btn-danger btn-sm' href="?absent_id=<?php echo $employee['id']; ?>">Absent</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="container" my-5>
        <div class="attendance_list_header">
            <h2>Attendance List</h2>
            <h2><?php echo date('d-m-Y'); ?></h2>
        </div>
        <hr>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Department</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach ($attendances as $attendance) : ?>
                    <tr>
                        <td><?php echo $attendance['id']; ?></td>
                        <td><?php echo $attendance['name']; ?></td>
                        <td><?php echo $attendance['email']; ?></td>
                        <td><?php echo $attendance['phone']; ?></td>
                        <td><?php echo $attendance['department']; ?></td>
                        <td><?php echo $attendance['date']; ?></td>
                        <td><?php echo $attendance['status']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>