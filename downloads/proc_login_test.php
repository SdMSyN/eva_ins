<?php
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$name = $_POST['input'];
$name2 = $_POST['input2'];

$sql = "SELECT id, firstname, lastname FROM MyGuests WHERE nombre='$name' ";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    header('Location: algun.php');
} else {
    header('Location: index.php');
}
$conn->close();
?>