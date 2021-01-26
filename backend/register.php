<?php

session_start();

//Check The Request Method

$errors = [];

if ($_SERVER['REQUEST_METHOD'] != 'POST' || !isset($_POST['register_btn'])) {
    $errors['message'] = "You Can Only Send Data With Post Method";
    $_SESSION['errors'] = $errors;
    header("Location:../frontend/register.php");
    exit(0);
}

// Old Value

$old_val = [
    'fname' => $_POST['fname'],
    'lname' => $_POST['lname'],
    'email' => $_POST['email'],
];

$_SESSION['old'] = $old_val;

// Receive Data 

$fname = $_POST['fname'];

$lname = $_POST['lname'];

$email = $_POST['email'];

$password = $_POST['password'];

$cpassword = $_POST['cpassword'];

// Validate Data

if (strlen($fname) < 5) {
    $errors['fname'] = "Please Enter Name At Least 6 Char";
}

if (strlen($lname) < 5) {
    $errors['lname'] = "Please Enter Name At Least 6 Char";
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Please Enter Valid Email";
} else {

    // Check If Email Is Exist

    $handle = fopen('db.txt', 'r');
    $data = file_get_contents('db.txt');
    $rows = explode("\n", $data);
    $emails = [];
    foreach ($rows as $row) {
        $columns = explode(",", $row);
        $emails[] = $columns[2];
    }
    if (in_array($email, $emails)) {
        $errors['email'] = "This Email Already Taken";
        header("Location:../register.php");
    }
}

if (strlen($password) <= 6) {
    $errors['password'] = "Please Enter Password At Least 7 Char";
} elseif ($password !== $cpassword) {
    $errors['password'] = "The Confirmed Password Must Be Identical To Password";
}

// Send Errors To FrontEnd

$_SESSION['errors'] = $errors;

if (count($errors)) {
    header('Location:../frontend/register.php');
    exit();
}

// Add User Data To DB File System
$row = "{$fname},{$lname},{$email},{$password}\n";
$handle = fopen('db.txt', 'a+');
fwrite($handle, $row);
$_SESSION['message'] = "Congratulation, You Can Login Now";
header("Location:../frontend/login.php");
