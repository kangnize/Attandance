<?php
session_start();
require 'server.php';
$db = new db();

$name = "";
$email = "";
$phone = "";
$department = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $id = $_GET["id"];
    $sql  = "SELECT * FROM employees WHERE id='$id'";
    
    $db->execute($sql);
    $employee = $db->fetch_result();
    
    if ($employee) {
        $name = $employee["name"];
        $email = $employee["email"];
        $phone = $employee["phone"];
        $department = $employee["department"];
    }

}

if (isset($_POST["submit"])) {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $department = $_POST["department"];

    $name = $db->clean_input($name);
    $email = $db->clean_input($email);
    $phone = $db->clean_input($phone);
    $department = $db->clean_input($department);

    if (empty($name) || empty($email) || empty($phone) || empty($department)) {
        $errorMessage = "All the fileds are required";
    }

    $db->query = "UPDATE employees 
    SET name = :name, email = :email, phone = :phone, department = :department 
    WHERE id = :id";
    
    $db->stmt = $db->con->prepare($db->query);
    $db->stmt->bindParam(':name', $name);
    $db->stmt->bindParam(':email', $email);
    $db->stmt->bindParam(':phone', $phone);
    $db->stmt->bindParam(':department', $department);
    $db->stmt->bindParam(':id', $id);

    $db->stmt->execute();

    $successMessage = "Client updated correctly";

    header("location: /attendance system/main.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body>
    <style>
        body {
            background-image: url("images/bri.jpg");
        }
    </style>
    <div class="container my-5">
        <h2>New Employee</h2>

        <?php
        if (!empty($errorMessage)) {
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong>$errorMessage</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            ";
        }
        ?>
        <form method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Name</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="email" value="<?php echo $email; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Phone</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="phone" value="<?php echo $phone; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Department</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="department" value="<?php echo $department; ?>">
                </div>
            </div>

            <?php
            if (!empty($successMessage)) {
                echo "
                    <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                        <strong>$successMessage</strong>
                        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>
                    ";
            }
            ?>

            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="/attendance system/main.php" role="button">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</body>

</html>