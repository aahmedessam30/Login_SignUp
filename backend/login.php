<?php

session_start();

// Check Request Method

if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['login_btn'])) {
    $_SESSION['message'] = "You Can Only Send Data With Post Method";
    header("Location:../frontend/login.php");
}

// Receive Data

$email = $_POST['email'];

$password = $_POST['password'];

$errors = [];

// Validate Data

if (empty($email)) {
    $errors['email'] = "You Must Enter Email";
    header("Location:../frontend/login.php");
}

if (empty($password)) {
    $errors['password'] = "You Must Enter Password";
    header("Location:../frontend/login.php");
}

// Search Data In File System

$data = file_get_contents('db.txt');

$rows = explode("\n", $data);

$emails = [];

$passwords = [];

foreach ($rows as $row) {
    $columns = explode(",", $row);
    $emails[] = $columns[2];
    $passwords[] = $columns[3];
}
if (in_array($email, $emails) && in_array($password, $passwords)) {
    header("Location:../frontend/success.php");
} else {
    $errors['auth_error'] = "The Email Address And Password You Entered Do Not Match Any Account. Please Try Again";
}

// Send Errors To FrontEnd

$_SESSION['errors'] = $errors;

if (count($errors)) {
    header('Location:../frontend/login.php');
    exit();
}
