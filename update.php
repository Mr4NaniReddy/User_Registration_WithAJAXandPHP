<?php
include 'connect.php';

$id = $_POST['update_hiddenid'];
$firstname = $_POST['update_firstname'];
$lastname = $_POST['update_lastname'];
$email = $_POST['update_email'];
$mobile = $_POST['update_mobile'];
$profile_picture = '';

$errors = [];

if (empty($firstname)) {
    $errors['firstname'] = "First name is required";
}

if (empty($lastname)) {
    $errors['lastname'] = "Last name is required";
}

// if (empty($email)) {
//     $errors['email'] = "Email is required";
// }

$emailPattern =  '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
if (empty($email)) {
    $errors['email'] = "Email is required";
} else if(!preg_match($emailPattern, $email)){
    $errors['email'] = "Invalid email";
} 
// else if($email) {
//     $sql = "SELECT id FROM users WHERE email = ?";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("s", $email);
//     $stmt->execute();
//     $stmt->store_result();
//     if ($stmt->num_rows > 0) {
//         $errors['email'] = "Email already exists";
//     }
//     $stmt->close();
// }

// if (empty($mobile)) {
//     $errors['mobile'] = "Mobile is required";
// }

$mobilePattern = '/^[0-9]{10}$/';
if (empty($mobile)) {
    $errors['mobile'] = "Mobile is required";
} else if(!preg_match($mobilePattern, $mobile)) {
    $errors['mobile'] = "Must include 10 numbers";
}


if (!empty($_FILES['update_profile_picture']['name'])) {
    $fileName = $_FILES['update_profile_picture']['name'];
    $fileTmpName = $_FILES['update_profile_picture']['tmp_name'];
    $fileSize = $_FILES['update_profile_picture']['size'];
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
}

if (!empty($errors)) {
    http_response_code(400);
    echo json_encode($errors);
    exit;
}

$sql = "UPDATE users SET firstname='$firstname', lastname='$lastname', email='$email', mobile='$mobile'";

if (!empty($profile_picture)) {
    $sql .= ", profile_picture='$profile_picture'";
}

$sql .= " WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>
