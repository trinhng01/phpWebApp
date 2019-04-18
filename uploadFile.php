<?php
/**
 * Trinh Nguyen
 * Homework #4 - PHP + MySQL
 * CS174
 * IDE: PhpStorm
 * Date: 2019-04-14
 */

require_once "login.php";
$connect = new mysqli($hn, $un, $pw, $db);

//Check connection
if ($connect->connect_error) die("Connection failed: " . $connect->connect_error);



$table = "CREATE TABLE IF NOT EXISTS inputfile (
          id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
          name VARCHAR(32) NOT NULL,
          content LONGTEXT NOT NULL)";

//Create another table




if ($connect->query($table) === TRUE) {
    //echo "Table inputfile created successfully"."<br>";
} else {
    echo "Error creating table: " . $connect->error;
}

echo <<<_END
    <html><head><title>CS174 Assignment #4</title></head><body>
    
    <form method='POST' action='uploadFile.php' enctype='multipart/form-data'>
        Name:
        <input type="text" name="name" id="name">
        <br><br> 
        Select File:
        <input type='file' name='filename' id='filename'> <br><br>
        <input type='submit' name="submit" value='Upload'>
    </form>
        
_END;


if (isset($_POST['submit'])) {
    $name = sanitizeString($_POST['name']);

    if ($_FILES['filename']['type'] == 'text/plain'){

        $filename = $_FILES['filename']['name'];
        move_uploaded_file($_FILES['filename']['tmp_name'], $filename);
        if(!file_exists($filename)) die("File does not exist");

        $content = sanitizeString(sanitizeString(file_get_contents($filename)));
        $query = "INSERT INTO inputfile VALUES (NULL,'$name','$content')";
        $result = $connect->query($query);

        if (!$result) die ("Database Error: " . $connect->error);
    } else {
            echo "This file is not a text file. Please try again" . "<br></br>";
    }

}

//Display Database Content
$query = "SELECT * FROM inputfile";
$result = $connect->query($query);

if($result) {
    $rows = $result->num_rows;

    for($i=0; $i < $rows; ++$i) {
        $result->data_seek($i);
        $each_row = $result->fetch_array(MYSQLI_ASSOC);
        echo "Name: " . $each_row['name'] . "<br>";
        echo "File Content: " . $each_row['content'] . "<br><br>";
    }
    $result->close();
}

function sanitizeString($var) {
    $var = stripslashes($var);
    $var = strip_tags($var);
    $var = htmlentities($var);
    return $var;
}

echo "</body></html>";
$connect->close();
?>



