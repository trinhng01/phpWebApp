<?php
/**
 * Created by PhpStorm.
 * User: TrinhNg
 * Date: 2019-04-18
 * Time: 14:44
 */


require_once "login.php";
$connect = new mysqli($hn, $un, $pw, $db);
if ($connect->connect_error) die("Connection failed: " . $connect->connect_error);


echo <<<_END
    <html><head><title>Sign up</title></head><body>
    
    <form method='POST' action='signup.php'>
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
    $name = sanitizeString($_POST['name']);
    echo "Name: $name <br>";
    $email = sanitizeString($_POST['email']);
    echo "Email: $email <br>";
    $password = sanitizeString($_POST['psw']);
    echo "Pass: $password <br>";

    $salt1 = "qm&h*"; $salt2 = "pg!@";
    $token = hash('ripemd128', "$salt1$password$salt2");
    echo "Token: $token <br>";

    add_user($connect, $name, $email, $token);

    echo "Welcome back $name.<br>
    Your full name is $name.<br>
    Your email/username is '$username' <br><br>";
    die ("<p><a href=http://localhost:63342/phpWebApp/authenticate.php?_ijt=rn8ltjgq1n80427m7i8ldacouq>Click here to log in</a></p>");

}

function add_user($connection, $name, $email, $token)
{
    $query = "INSERT INTO credentials VALUES(NULL, '$name', '$email', '$token')";
    $result = $connection->query($query);
    if (!$result) die($connection->error);
    else "Insert data into table\n";
}


function sanitizeString($var) {
    $var = stripslashes($var);
    $var = strip_tags($var);
    $var = htmlentities($var);
    return $var;
}