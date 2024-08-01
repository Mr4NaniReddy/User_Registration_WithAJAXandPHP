<?php
include 'connect.php';

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$email = $_POST['email'];
$mobile = $_POST['mobile'];
$password = $_POST['password'];
$confirmPassword = $_POST['confirmPassword'];
$profile_picture = '';

$errors = [];

if (empty($firstname)) {
    $errors['firstname'] = "First name is required";
}

if (empty($lastname)) {
    $errors['lastname'] = "Last name is required";
}

$emailPattern =  '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
if (empty($email)) {
    $errors['email'] = "Email is required";
} else if(!preg_match($emailPattern, $email)){
    $errors['email'] = "Invalid email";
} else if($email) {
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors['email'] = "Email already exists";
    }
    $stmt->close();
}

$mobilePattern = '/^[0-9]{10}$/';
if (empty($mobile)) {
    $errors['mobile'] = "Mobile is required";
} else if(!preg_match($mobilePattern, $mobile)) {
    $errors['mobile'] = "Must include 10 numbers";
}

$passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/';
if (empty($password)) {
    $errors['password'] = "Password is required";
} else if(!preg_match($passwordPattern, $password)) {
    $errors["password"] = "Atleast 6 characters include lower, upper, number, special";
}

if ($password !== $confirmPassword) {
    $errors['confirmPassword'] = "Passwords do not match";
}

if (!empty($_FILES['profile_picture']['name'])) {
    $fileName = $_FILES['profile_picture']['name'];
    $fileTmpName = $_FILES['profile_picture']['tmp_name'];
    $fileSize = $_FILES['profile_picture']['size'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowed = ['jpeg', 'jpg', 'png'];

    if (!in_array($fileExt, $allowed)) {
        $errors['profile_picture'] = "Invalid file type. Only JPEG, JPG, and PNG are allowed.";
    }

    if ($fileSize > 100000) {
        $errors['profile_picture'] = "File size exceeds 100KB.";
    }

    if (empty($errors)) {
        $profile_picture = 'uploads/' . time() . '.' . $fileExt;
        move_uploaded_file($fileTmpName, $profile_picture);
    }
} else{
    $errors['profile_picture'] = 'Please upload image';
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode($errors);
    exit;
}

$passwordHash = password_hash($password, PASSWORD_BCRYPT);

$sql = "INSERT INTO users (firstname, lastname, email, mobile, password, profile_picture) 
        VALUES ('$firstname', '$lastname', '$email', '$mobile', '$passwordHash', '$profile_picture')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
// $result = mysqli_query($conn, $sql);

$conn->close();
?>
