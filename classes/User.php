<?php

require_once 'Database.php';

class User extends Database
{


    public function register($request)
    {
        // these are data coming from the form (views/sign-up.php)
        $first_name = $request['first_name'];
        $last_name = $request['last_name'];
        $username = $request['username'];
        // password hashing for security- we cannot read the passwords inside the database in plain text (password encryption)
        $password = password_hash($request['password'], PASSWORD_DEFAULT);


        // sql code (instructions on how to use the data)
        $sql = "INSERT INTO users (first_name, last_name, username, password) VALUES ('$first_name', '$last_name', '$username', '$password')";

        // conditional statement to check if the sql code works inside phpmyadmin
        if ($this->conn->query($sql)) {
            header("Location: ../views/login.php");
            exit;
        } else {
            die("Error: " . $this->conn->error);
        }
    }
    public function login($request)
    {
        $username = $request['username'];
        $password = $request['password'];

        $sql = "SELECT * FROM users WHERE username = '$username'";

        if ($result = $this->conn->query($sql)) {
            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();

                if (password_verify($password, $user['password'])) {
                    session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['full_name'] = $user['first_name'] . ' ' . $user['last_name'];

                    header("Location: ../views/dashboard.php");
                } else {
                    die("Invalid Credentials");
                }
            } else {
                die("Invalid Credentials");
            }
        } else {
            die("Error: " . $this->conn->error);
        }
    }
    public function displayUsers(){
        $sql = "SELECT * FROM users";

        if($result = $this->conn->query($sql)){
            return $result;
        } else {
            die("Error: " . $this->conn->error);

        }
    }
}
