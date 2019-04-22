<?php
require_once "login.php";

session_start();

//Set time out to 1 day
ini_set('session.gc_maxlifetime', 60 * 60 * 24);

if (isset($_SESSION['email']))
{
    $email = $_SESSION['email'];
    $password = $_SESSION['password'];
    $name = $_SESSION['name'];

    destroy_session_and_data();

    echo "Welcome back $name!<br><br>";

    $connect = new mysqli($hn, $un, $pw, $db);

    //Check connection
    if ($connect->connect_error) die("Connection failed: " . $connect->connect_error);

    $table = "CREATE TABLE IF NOT EXISTS inputfile (
          id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          ownerName VARCHAR(32) NOT NULL,
          ownerEmail VARCHAR(50) NOT NULL,
          fileName VARCHAR(32) NOT NULL,
          content LONGTEXT NOT NULL)";

    //Check connections
    if ($connect->query($table) === TRUE) {
        //echo "Table inputfile created successfully"."<br>";
    } else {
        echo "Error creating table: " . $connect->error;
    }

    //Upload file
    echo <<<_END
    <html><head><title>Upload File</title></head><body>
    
    <form method='POST' action='uploadFile.php' enctype='multipart/form-data'>
        File Name:
        <input type="text" name="name" id="name">
        <br><br> 
        Select File:
        <input type='file' name='filename' id='filename'> <br><br>
        <input type='submit' name="submit" value='Upload'>
    </form>
        
_END;


    if (isset($_POST['submit'])) {
        $file = sanitizeString($_POST['name']);

        if ($_FILES['filename']['type'] == 'text/plain'){

            $filename = $_FILES['filename']['name'];
            move_uploaded_file($_FILES['filename']['tmp_name'], $filename);
            if(!file_exists($filename)) die("File does not exist");

            $content = sanitizeString(sanitizeString(file_get_contents($filename)));
            $query = "INSERT INTO inputfile VALUES (NULL, '$name', '$email', '$file','$content')";
            $result = $connect->query($query);

            if (!$result) die ("Database Error: " . $connect->error);
        } else {
            echo "This file is not a text file. Please try again" . "<br>";
        }
    }


//Display Database Content
    $query = "SELECT fileName, content FROM inputfile WHERE ownerName = '$name' AND ownerEmail = '$email'";
    $result = $connect->query($query);

    if($result) {
        $rows = $result->num_rows;
        for($i=0; $i < $rows; ++$i) {
            $result->data_seek($i);
            $each_row = $result->fetch_array(MYSQLI_ASSOC);
            echo "File Name: " . $each_row['fileName'] . "<br>";
            echo "File Content: " . $each_row['content'] . "<br><br>";
        }
        $result->close();
    }

    echo "</body></html>";
    $connect->close();
}

else {
    echo "Access Denied! Please <a href=authenticate.php>Log in.</a>";
}

function destroy_session_and_data()
{
    session_start();
    $_SESSION = array();	// Delete all the information in the array
    session_destroy();
}

function sanitizeString($var) {
    $var = stripslashes($var);
    $var = strip_tags($var);
    $var = htmlentities($var);
    return $var;
}

?>



