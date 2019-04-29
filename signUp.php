<?php

require_once "login.php";
$connect = new mysqli($hn, $un, $pw, $db);
if ($connect->connect_error) die("Connection failed: " . $connect->connect_error);

echo <<<_END
    <html><head><title>Sign up</title></head><body>
    
    <form method='POST' action='signUp.php'>
        <div class="container">
            <h1>Sign Up</h1>
            <p>Please fill in this form to create an account.</p>
            <hr>
            
            <label for="name"><b>Name</b></label>
            <input type="text" placeholder="Enter Name" name="name" required> 
            <br><br>
        
            <label for="email"><b>Email</b></label>
            <input type="text" placeholder="Enter Email" name="email" required> 
            <br><br>
            
            <label for="psw"><b>Password</b></label>
            <input type="password" placeholder="Enter Password" name="psw" required>
            <br><br>
            
            <label for="psw-repeat"><b>Repeat Password</b></label>
            <input type="password" placeholder="Repeat Password" name="psw-repeat" required>
            <br><br>
        
            <div class="clearfix">
                <button type="submit" name = "submit" class="signupbtn">Sign Up</button>
                <button type="button" class="cancelbtn">Cancel</button>
            </div>
        </div>
    </form>
        
_END;


$table = "CREATE TABLE IF NOT EXISTS credentials (
          id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          name VARCHAR(32) NOT NULL,
          email VARCHAR(50) NOT NULL,
          password VARCHAR(32) NOT NULL)";


//Check connections
if ($connect->query($table) === TRUE) {
//    echo "Table credentials created successfully"."<br>";
} else {
    echo "Error creating table: " . $connect->error;
}

if (isset($_POST['submit'])) {
    $name = sanitizeMySQL($connect, $_POST['name']);
    $email = sanitizeMySQL($connect, $_POST['email']);
    $password = sanitizeMySQL($connect, $_POST['psw']);

    $salt1 = "qm&h*"; $salt2 = "pg!@";
    $token = hash('ripemd128', "$salt1$password$salt2");

    add_user($connect, $name, $email, $token);

    echo "Welcome $name!<br>
    Your email/username is '$email' <br>";
    die ("<p><a href=userAuth.php>Click here to log in</a></p>");
}

function add_user($connection, $name, $email, $token)
{
    $query = "INSERT INTO credentials VALUES(NULL, '$name', '$email', '$token')";
    $result = $connection->query($query);
    if (!$result) die($connection->error);
}


function sanitizeString($var) {
    $var = stripslashes($var);
    $var = strip_tags($var);
    $var = htmlentities($var);
    return $var;
}

function sanitizeMySQL($connection, $var) {
    $var = $connection->real_escape_string($var);
    $var = sanitizeString($var);
    return $var;
}