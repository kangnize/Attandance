<?php
    class db
    {
        public $con;
        public $query;
        public $stmt;
        public $DATABASE_HOST = "localhost";
        public $DATABASE_USER  = "root";
        public $DATABASE_PASS = "";
        public $DATABASE_NAME = "attendance_system";

        public function __construct()
        {
            $dsn = 'mysql:host=' . $this->DATABASE_HOST . ';dbname=' . $this->DATABASE_NAME . ';charset=utf8';

            try {
                $this->con = new PDO($dsn, $this->DATABASE_USER, $this->DATABASE_PASS);
                $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
            
                echo 'Connection failed: ' . $e->getMessage();
          }
            
        }

        function execute($query = null)
        {
            if ($query){
                $this->query = $query;
            }
            $this->stmt = $this->con->prepare($this->query);
            $this->stmt->execute();
        }
        
        function row_count()
        {
           $row_count = $this->stmt->rowCount();
           return $row_count;
        }

        function fetch_result()
        {
            return $this->stmt->fetch(PDO::FETCH_ASSOC);
        }

        function fetch_results()
        {
            return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        function user_exists($email, $password = null)
        {
            if($password) {
                $this->query = "SELECT * FROM admins WHERE email = '$email' and password = '$password'";
            } 
            else {
                $this->query = "SELECT * FROM admins WHERE email = '$email'";
            }

            $this->execute();
            return $this->row_count() > 0;
        }

        function is_admin($email)
        {
            if($this->user_exists($email)){
                $user = $this->fetch_result();
                return $user["user_type"] === 'admin';
            } else{
                return false;
            }
        }

        function get_userid($email)
        {
            if($this->user_exists($email)){
                $user = $this->fetch_result();
                return $user["user_id"];
            } else{
                return 1;
            }
        }

        function clean_input($string)
        {
            $string = trim($string);
            $string = stripslashes($string);
            $string = htmlspecialchars($string);
            return $string;
        }

        function redirect_user($email = null)
        {
            $email = isset($_SESSION['email']) ? $_SESSION['email'] : $email;
            if ($email) {
                if($this->is_admin($email)){
                    header("Location: main.php");
                    exit();
                } else{
                    header("Location: index.php");
                    exit();
                }
            }
            
        }
    }
   
?>



