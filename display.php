<?php
include 'connect.php';

$sql = "SELECT * FROM users";
$result = $conn->query($sql); // or use mysqli_query($conn, $sql);

// echo '<table class="table table-bordered">';
// echo '<thead>';
// echo '<tr>';
// echo '<th>Sl no</th>';
// echo '<th>First Name</th>';
// echo '<th>Last Name</th>';
// echo '<th>Email</th>';
// echo '<th>Mobile</th>';
// echo '<th>Profile Picture</th>';
// echo '<th>Operations</th>';
// echo '</tr>';
// echo '</thead>';
// echo '<tbody>';
?>

<table class="table table-bordered">
 <thead>
 <tr>
 <th>Sl no</th>
 <th>First Name</th>
 <th>Last Name</th>
 <th>Email</th>
 <th>Mobile</th>
 <th>Profile Picture</th>
 <th>Operations</th>
 </tr>
 </thead>
 <tbody>

<?php

$number = 1;
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '<tr>';
        echo '<td>' . (string)$number .'</td>';
        echo '<td>' . $row["firstname"] . '</td>';
        echo '<td>' . $row["lastname"] . '</td>';
        echo '<td>' . $row["email"] . '</td>';
        echo '<td>' . $row["mobile"] . '</td>';
        echo '<td><img src="' . $row["profile_picture"] . '" alt="Profile Picture" width="50" height="50"></td>';
        echo '<td>';
        echo '<button class="btn btn-primary" onclick="getUserDetails(' . $row["id"] . ')">Update</button>';
        echo ' <button class="btn btn-danger" onclick="deleteUser(' . $row["id"] . ')">Delete</button>';
        echo '</td>';
        echo '</tr>';
        $number++;
    }
} else {
    echo '<tr><td colspan="6" class="text-center">No users found</td></tr>';
}

echo '</tbody>';
echo '</table>';

$conn->close();
?>
