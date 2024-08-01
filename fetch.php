<?php
include 'connect.php';

$id = $_POST['id'];

$sql = "SELECT * FROM users WHERE id=$id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo "No results found";
}

$conn->close();
?>
