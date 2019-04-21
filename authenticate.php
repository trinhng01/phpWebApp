<?php
/**
 * Created by PhpStorm.
 * User: TrinhNg
 * Date: 2019-04-18
 * Time: 14:45
 */

require_once 'login.php';
$connect = new mysqli($hn, $un, $pw, $db);
if ($connect->connect_error) die("Connection failed: " . $connect->connect_error);

if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
    $un_temp = mysql_entities_fix_string($connect, $_SERVER['PHP_AUTH_USER']);
    $pw_temp = mysql_entities_fix_string($connect, $_SERVER['PHP_AUTH_PW']);

    $query = "SELECT * FROM credentials WHERE email= '$un_temp'";
    $result = $connect->query($query);

    echo "Email: $un_temp , Pass: $pw_temp <br><br>";

    if (!$result) die($connect->error);

    elseif ($result->num_rows) {
        $row = $result->fetch_array(MYSQLI_NUM);
        $result->close();
        $salt1 = "qm&h*"; $salt2 = "pg!@";
        $token = hash('ripemd128', "$salt1$pw_temp$salt2");
        echo "Token: $token - $row[3]<br>";

        //Compare passwords
        if ($token == $row[3]) {
            session_start();
            $_SESSION['email'] = $un_temp;
            $_SESSION['password'] = $pw_temp;
            $_SESSION['name'] = $row[1];

            echo "<br>Hi $row[1], you are now logged in as '$row[2]'";
            die ("<p><a href=http://localhost:63342/phpWebApp/uploadFile.php?_ijt=rlea9mom1daqr0slq5j1trvp7f>Click here to access your files</a></p>");
        }
        else {
            echo "<p><a href=http://localhost:63342/phpWebApp/authenticate.php?_ijt=rn8ltjgq1n80427m7i8ldacouq>Log in</a></p>";
            echo "<p><a href=http://localhost:63342/phpWebApp/signup.php?_ijt=rn8ltjgq1n80427m7i8ldacouq>Sign up</a></p>";
            die("Invalid username/password combination");
        }
    }
    else die("Invalid username/password combination");
} else {
    header('WWW-Authenticate: Basic realm="Restricted Section"');
    header('HTTP/1.0 401 Unauthorized');
    die ("Please enter your username and password");
}

$connect->close();
function mysql_entities_fix_string($connect, $string)
{
    return htmlentities(mysql_fix_string($connect, $string));
}
function mysql_fix_string($connect, $string)
{
    if (get_magic_quotes_gpc()) $string = stripslashes($string);
    return $connect->real_escape_string($string);
}

