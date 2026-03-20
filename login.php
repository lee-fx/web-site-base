<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>登录</title>
  <link rel="stylesheet" href="css/style1.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    .login-button {
      display: block;
      width: 100%;
      max-width: 200px;
      margin: 20px auto;
      padding: 12px 24px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      border-radius: 25px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      transition: all 0.3s ease;
    }
    
    .login-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
      background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    }
    
    .login-button:active {
      transform: translateY(0);
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="form-box box">

      <?php
      include "connection.php";

      if (isset($_POST['登录'])) {

        $email = $_POST['email'];
        $pass = $_POST['password'];

        if($email == ''|| $pass == '') { 
            echo "<div class='message'>
                    <p>请输入账号密码</p>
                    </div><br>";

            echo "<a href='login.php'><button class='btn'>去登录</button></a>";
            return;
       }

        $sql = "select * from users where email='$email'";

        $res = mysqli_query($conn, $sql);

        if (mysqli_num_rows($res) > 0) {

          $row = mysqli_fetch_assoc($res);

          $password = $row['password'];


          if (md5($pass) == $password) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("location: home.php");


          } else {
            echo "<div class='message'>
                    <p>密码错误</p>
                    </div><br>";

            echo "<a href='login.php'><button class='btn'>重新登录</button></a>";
          }

        } else {
          echo "<div class='message'>
                    <p>邮箱账号不存在，请注册后再登录！</p>
                    </div><br>";

          echo "<a href='login.php'><button class='btn'>重新登录</button></a>";

        }


      } else {


        ?>

        <header>登录</header>
        <hr>
        <form action="#" method="POST">

          <div class="form-box">


            <div class="input-container">
              <i class="fa fa-envelope icon"></i>
              <input class="input-field" type="email" placeholder="邮箱" name="email">
            </div>

            <div class="input-container">
              <i class="fa fa-lock icon"></i>
              <input class="input-field password" type="password" placeholder="密码" name="password">
              <i class="fa fa-eye toggle icon"></i>
            </div>

          </div>



          <input type="submit" name="登录" value="登录" class="login-button">

          <div class="links">
            还没有账户? <a href="signup.php">注册</a>
          </div>

        </form>
      </div>
      <?php
      }
      ?>
  </div>
  <script>
    const toggle = document.querySelector(".toggle"),
      input = document.querySelector(".password");
    toggle.addEventListener("click", () => {
      if (input.type === "password") {
        input.type = "text";
        toggle.classList.replace("fa-eye-slash", "fa-eye");
      } else {
        input.type = "password";
      }
    })
  </script>
</body>

</html>