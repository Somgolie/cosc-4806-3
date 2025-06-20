<?php  
require_once 'app/views/templates/headerPublic.php'
session_start();
session_destroy();

$user = new User();
$message = "";

function is_password_strong($password) {
    $hasNumber = preg_match('/\d/', $password);
    return strlen($password) >= 5 && $hasNumber;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (!empty($username) && !empty($password)) {
        if (!is_password_strong($password)) {
            $message = "Password must be at least 5 words and contain at least 1 number.";
        } elseif ($user->username_exists($username)) {
            $message = "Username already taken. Please choose another.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            if ($user->create_user($username, $hashedPassword)) {
                header("Location: index.php");
                exit();
            } else {
                $message = "Failed to create account.";
            }
        }
    } else {
        $message = "Please fill in both fields.";
    }
}
?>
    <?php require_once 'app/views/templates/footer.php' ?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
</head>
<body>

<h1>Create an Account</h1>
<?php if (!empty($message)): ?>
  <p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<form action="" method="post">
  <label for="username">Username:</label><br>
  <input type="text" id="username" name="username" required><br>

  <label for="password">Password:</label><br>
  <input type="password" id="password" name="password" required><br><br>

  <input type="submit" value="Submit">
</form> 

</body>
</html>
