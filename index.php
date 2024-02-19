<?php
session_start();
include('config.php');

$errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = isset($_POST['Name']) ? trim($_POST['Name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $cpassword = isset($_POST['cpassword']) ? trim($_POST['cpassword']) : '';

    if (empty($name)) {
        $errors['name'] = "Name is required.";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $name)) {
        $errors['name'] = "Name can only contain letters and spaces.";
    }

    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email is not valid.";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required.";
    } elseif (strlen($password) < 8) {
        $errors['password'] = "Password must be at least 8 characters long.";
    } elseif (!preg_match("/[A-Z]/", $password)) {
        $errors['password'] = "Password must contain at least one uppercase letter.";
    } elseif (!preg_match("/[a-z]/", $password)) {
        $errors['password'] = "Password must contain at least one lowercase letter.";
    } elseif (!preg_match("/[0-9]/", $password)) {
        $errors['password'] = "Password must contain at least one number.";
    }

    if (empty($cpassword)) {
        $errors['cpassword'] = "Confirm Password is required.";
    } elseif ($password !== $cpassword) {
        $errors['cpassword'] = "Passwords do not match.";
    }

    if (empty($errors)) {
        // Save data to database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO registration (Name, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $hashed_password);
        if ($stmt->execute()) {
            echo "New user added successfully.";

            // Set session variables
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['loggedin'] = true;
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registration Form</title>
    <style>
        .form-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        /* Styling for form elements */
        .form-container form {
            width: 300px; /* Adjust width as needed */
            padding: 20px;
            background-color: #f0f0f0;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-container label,
        .form-container input {
            display: block;
            margin-bottom: 10px;
        }

        .form-container input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: all 0.3s ease; /* Add transition for smoother animation */
        }

        .form-container input[type="submit"]:hover {
            background-color: #0056b3;
            transform: translateY(-3px); /* Move the button slightly up on hover */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); /* Add shadow on hover for depth effect */
        }

        .error {
          color: red;
          font-size: 0.8em;
          margin-top: 0.25em;
        }
        .back-container{
            position: absolute;
            top: 5%;
            left:85%;
        }
        back-container a:hover {
    color: #00bff;
 text-decoration underline;
    transition: color 0.3 ease;
}

back-container a {
    color: green;
    text-decoration: none;
    transition: color 0.3s ease;
}

.back-container a::after {
    content: "";
    display: block;
    width: 0;
    height: 1px;
    background-color: green;
    transition: width 0.3s ease;
}

.back-container a:hover::after {
    width: 100%;
}
    </style>
</head>
<body>
    <div class="form-container">
       
        <form action="" method="post" onsubmit="return confirm('Save data?');">
        <h1>Register User</h1><br/>
            <label for="name">Name </label>
            <input type="text" name="Name" id="name" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" required>
            <?php if (isset($errors['name'])) echo '<div class="error">' . $errors['name'] . '</div>'; ?><br>
            <label for="email">Email</label>
            <input type="text" name="email" id="email" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
            <?php if (isset($errors['email'])) echo '<div class="error">' . $errors['email'] . '</div>'; ?><br>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <?php if (isset($errors['password'])) echo '<div class="error">' . $errors['password'] . '</div>'; ?><br>
            <label for="cpassword">Confirm Password</label>
            <input type="password" name="cpassword" id="cpassword" required>
            <?php if (isset($errors['cpassword'])) echo '<div class="error">' . $errors['cpassword'] . '</div>'; ?><br>
            <button type="submit">Submit</button>
        </form>
    </div>
    
    <div class="back-container">
        <form action="login.php" method="post">
            <a href="login.php">Already registered? Click here to login.</a>
        </form>
    </div>
</body>
</html>
