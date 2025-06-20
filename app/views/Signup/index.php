<!DOCTYPE html>
<html>
<head>
  <title>Sign Up</title>
</head>
<body>

  <h1>Create an Account</h1>

  <!-- Placeholder for future messages -->
  <?php if (!empty($message)): ?>
    <p style="color:red;"><?php echo htmlspecialchars($message); ?></p>
  <?php endif; ?>

  <form action="/signup/create" method="post">
    <label for="username">Username:</label><br>
    <input type="text" id="username" name="username" required><br>

    <label for="password">Password:</label><br>
    <input type="password" id="password" name="password" required><br><br>

    <input type="submit" value="Submit">
  </form> 

</body>
</html>