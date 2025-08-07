<?php
session_start();
include('./server/connection.php');

// Redirect if already logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: homepage.php');
    exit;
}

// Handle login form
if (isset($_POST['login_btn'])) {
    $email = trim($_POST['email']);
    $input_password = $_POST['password'];

    // Get user by email
    $stmt = $conn->prepare("SELECT user_id, user_name, user_email, user_password, user_role FROM users WHERE user_email = ? LIMIT 1");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $user_name, $user_email, $hashed_password, $user_role);
        $stmt->fetch();

        // Verify password with password_verify
        if (password_verify($input_password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $user_name;
            $_SESSION['user_email'] = $user_email;
            $_SESSION['user_role'] = $user_role;
            $_SESSION['logged_in'] = true;

           

            header('Location: homepage.php?message=Logged+in+successfully');
            exit;
        } else {
            header('Location: loginpage.php?error=Incorrect+email+or+password');
            exit;
        }
    } else {
        header('Location: loginpage.php?error=Incorrect+email+or+password');
        exit;
    }
}
?>

<!doctype html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="./css/output.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
  <title>Login</title>
</head>
<body>
<div class="flex h-screen w-full items-center justify-center bg-white-900 bg-cover bg-no-repeat"
     style="background-image:url('https://i.pinimg.com/originals/85/e3/7f/85e37f679fc831f77076fc85ce9630c5.gif')">
  <div class="rounded-xl bg-white bg-opacity-50 px-16 py-10 shadow-lg backdrop-blur-md max-sm:px-8">
    <div class="text-white">
      <div class="mb-8 flex flex-col items-center">
        <img src="./picture/fulllogo_transparent.png" width="150" alt="Instagram Logo"/>
        <h1 class="mb-2 text-2xl">BloomSkin</h1>
        <span class="text-pink-300">Enter Login Details</span>
      </div>
      <form action="loginpage.php" method="POST">
        <?php if (isset($_GET['error'])): ?>
          <p class="text-red-500 mb-4 text-center"><?= htmlspecialchars($_GET['error']) ?></p>
        <?php endif; ?>

        <div class="mb-4 text-lg">
          <input class="rounded-3xl bg-pink-400 bg-opacity-50 px-12 py-2 text-center placeholder-white shadow-lg outline-none"
                 type="text" name="email" placeholder="Email" required />
        </div>

        <div class="mb-4 text-lg">
          <input class="rounded-3xl bg-pink-400 bg-opacity-50 px-12 py-2 text-center placeholder-white shadow-lg outline-none"
                 type="password" name="password" placeholder="Password" required />
        </div>

        <div class="mt-8 flex justify-center text-lg text-black">
          <button type="submit" name="login_btn"
                  class="rounded-3xl bg-pink-400 bg-opacity-50 px-12 py-2 text-white shadow-xl hover:bg-pink-600">
            Login
          </button>
        </div>

        <div class="pt-5 pb-5 text-center">
          <p>-------------- sign up --------------</p>
          <p><a class="hover:text-blue-400" href="./signuppage.php">Don't have an account yet? <span>Sign up</span></a></p>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
