<?php
   session_start();
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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $date = $_POST["date"];

        $selectedDate = DateTime::createFromFormat('Y-m-d', $date);
        $current_date = $selectedDate->format('Y-m-d');

        $db->query = "SELECT attendance.date, attendance.status, employees.name, employees.id, employees.email, employees.phone, employees.department
        FROM attendance
        INNER JOIN employees ON attendance.employee_id = employees.id WHERE attendance.date = :date";
    
        $db->stmt= $db->con->prepare($db->query);
        $db->stmt->bindParam(':date', $current_date, PDO::PARAM_STR);
        $db->stmt->execute();
        $attendances = $db->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
  
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="styles/main.css"></head>
<body>
    <style>
        body {
            background-image: url("images/bri.jpg");
        }
    </style>
    <div class="container" my-5>
        <div class="menu">
            <!-- <a class="btn btn-primary" href="/attendance system/attendancepresent.php" role="button">Present Attendance</a>
            <a class="btn btn-primary" href="/attendance system/attendanceabsent.php" role="button">Absent Attendance</a> -->
            <form method="post" action="">
                <label for="date">Select a Date:</label>
                <input type="date" id="date" name="date">
                <input type="submit" class="btn btn-primary" value="Show Attendance">
            </form>
        </div>
        <br>
        <div class="attendance_list_header">
            <h2>List Of Employees</h2>
            <h2><?php echo $current_date; ?></h2>
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