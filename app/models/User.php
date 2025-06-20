<?php

class User {

    public $username;
    public $password;
    public $auth = false;

    public function __construct() {
        
    }

    public function test () {
      $db = db_connect();
      $statement = $db->prepare("select * from users;");
      $statement->execute();
      $rows = $statement->fetch(PDO::FETCH_ASSOC);
      return $rows;
    }

  public function authenticate($username, $password) {
      $username = strtolower($username);
      $db = db_connect();
    // Get number of failed attempts in last 60 seconds
    $stmt = $db->prepare("
        SELECT COUNT(*) AS fail_count, MAX(time) AS last_fail_time 
        FROM login_Attempts 
        WHERE username = :username AND attempt = 0 AND time > (NOW() - INTERVAL 60 SECOND)
    ");
    $stmt->bindValue(':username', $username);
    $stmt->execute();
    $failData = $stmt->fetch(PDO::FETCH_ASSOC);

    $failCount = $failData['fail_count'] ?? 0;
    $lastFailTime = $failData['last_fail_time'] ?? null;

    if ($failCount >= 3) {
        // User is locked out for 60 seconds since last fail
        $lockoutSeconds = 60;
        $timeSinceLastFail = time() - strtotime($lastFailTime);
        $secondsLeft = $lockoutSeconds - $timeSinceLastFail;

        if ($secondsLeft > 0) {
            $_SESSION['login_message'] = "Too many failed attempts. Please wait {$secondsLeft} seconds before trying again.";
            header('Location: /login');
            exit;
        }
    }
    
      $statement = $db->prepare("SELECT * FROM users WHERE username = :name");
      $statement->bindValue(':name', $username);
      $statement->execute();
      $user = $statement->fetch(PDO::FETCH_ASSOC);

      if ($user && password_verify($password, $user['password'])) {
          $_SESSION['auth'] = 1;
          $_SESSION['username'] = ucwords($username);
          unset($_SESSION['failedAuth']);
          $this->log_attempt($username, true);
          header('Location: /home');
          exit;
      } else {
          // log bad attempt


          if (isset($_SESSION['failedAuth'])) {
              $_SESSION['failedAuth']++;

          } else {
              $_SESSION['failedAuth'] = 1;
          }
        $this->log_attempt($username, false);
        $_SESSION['login_message'] = "Invalid login, try again.";
        
        header('Location: /login');
        exit;
      }
  }
  public function username_exists($username) {
      $db = db_connect();
      $statement = $db->prepare("SELECT * FROM users WHERE username = :username");
      $statement->bindValue(':username', strtolower($username));
      $statement->execute();
      return $statement->fetch(PDO::FETCH_ASSOC) !== false;
  }

  public function create_user($username, $hashedPassword) {
      $db = db_connect();
      $statement = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
      $statement->bindValue(':username', strtolower($username));
      $statement->bindValue(':password', $hashedPassword);
      return $statement->execute();
  }
  public function log_attempt($username, $result) {
      $db = db_connect();
      $statement = $db->prepare("INSERT INTO login_Attempts (username, attempt, time) VALUES   (:username, :attempt, NOW())");  
      $statement->bindValue(':username', strtolower($username));
      $statement->bindValue(':attempt', $result, PDO::PARAM_BOOL);
      
      $success = $statement->execute();
      if (!$success) {
          $errorInfo = $statement->errorInfo();
          error_log("Failed to log login attempt: " . implode(" | ", $errorInfo));
      }
      return $success;
  }
}
