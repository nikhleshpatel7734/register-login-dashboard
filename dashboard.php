<?php
session_start();

if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }
        
        .content {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            background-color: #fff;
            animation: fadeIn 1s ease-in-out;
        }
        
        h1 {
            margin-bottom: 20px;
            font-size: 24px;
        }
        
        p {
            margin-top: 20px;
        }
        
        a {
            color: #007bff;
            text-decoration: none;
        }
        
        a:hover {
            text-decoration: underline;
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="content">
        <h1>Welcome, <?php echo $_SESSION['name']; ?> (<?php echo $_SESSION['email']; ?>)</h1>
        <p><a href="index.php">Log Out</a></p>
    </div>
</body>
</html>
