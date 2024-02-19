<?php
session_start();
include('config.php');

$errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email is not valid.";
    }

    if (empty($password)) {
        $errors['password'] = "Password is required.";
    }

    if (empty($errors)) {
        // Check if the email exists in the database
        $sql = "SELECT * FROM registration WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            // Check if the password matches
            if ($password === $user['password']) { // Comparing plain text passwords
                // Set session variables
                $_SESSION['email'] = $user['email'];
                $_SESSION['loggedin'] = true;
                header("Location: dashboard.php");
                exit;
            } else {
                $errors['login'] = "Invalid email or password.";
            }
        } else {
            $errors['login'] = "Invalid email or password.";
        }

        $stmt->close();
    }
}
?>
<div class="form-container">
<form action="" method="post" onsubmit="return confirm('Submit login?');">
<h1>Login user</h1>
    <label for="email">Email </label>
    <input type="text" name="email" id="email" required>
    <?php if (!empty($errors['email'])) echo '<div class="error">' . $errors['email'] . '</div>'; ?><br>
    <label for="password">Password</label>
    <input type="password" name="password" id="password" required>
    <?php if (!empty($errors['password'])) echo '<div class="error">' . $errors['password'] . '</div>'; ?><br>
    <button type="submit">Submit</button>
    <?php if (!empty($errors['login'])) echo '<div class="error">' . $errors['login'] . '</div>'; ?>
</form>
</div>
<style>
.error {
  color: red;
  font-size: 0.8em;
  margin-top: 0.25em;
}
.form-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
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

<div class="back-container">
    <a href="index.php" class="btn-mt4">Back to Registration</a>
</div>
</form>
