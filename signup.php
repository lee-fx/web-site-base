<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>注册</title>
  <link rel="stylesheet" href="resource/css/style1.css">
  <link rel="stylesheet" href="resource/css/font-awesome.min.css">

</head>

<body>
  <div class="container">
    <div class="form-box box">


      <header>注册</header>
      <hr>

      <form action="#" method="POST">


        <div class="form-box">

          <?php

          session_start();

          include "connection.php";

          if (isset($_POST['register'])) {

            $name = $_POST['username'];
            $email = $_POST['email'];
            $pass = $_POST['password'];
            $cpass = $_POST['cpass'];


            $check = "select * from users where email='{$email}'";

            $res = mysqli_query($conn, $check);

            $passwd = md5($pass);

            $key = bin2hex(random_bytes(12));



            if (mysqli_num_rows($res) > 0) {
              echo "<div class='message'>
        <p>邮箱已存在，请使用此邮箱/密码登录即可!</p>
        </div><br>";

              echo "<a href='javascript:self.history.back()'><button class='btn'>返回上级</button></a>";


            } else {

              if ($pass === $cpass) {

                $sql = "insert into users(username,email,password) values('$name','$email','$passwd')";

                $result = mysqli_query($conn, $sql);

                if ($result) {
                  // 注册成功后直接跳转到登录页面
                  header("location: login.php");
                  exit();

                } else {
                  echo "<div class='message'>
        <p>邮箱已被使用，请去登陆或用其他邮箱注册!</p>
        </div><br>";

                  echo "<a href='javascript:self.history.back()'><button class='btn'>返回上级</button></a>";
                }

              } else {
                echo "<div class='message'>
      <p>两次密码不一致，请返回重新输入注册.</p>
      </div><br>";

                echo "<a href='signup.php'><button class='btn'>返回上级</button></a>";
              }
            }
          } else {

            ?>

            <div class="input-container">
              <i class="fa fa-user icon"></i>
              <input class="input-field" type="text" placeholder="用户名" name="username" required>
            </div>

            <div class="input-container">
              <i class="fa fa-envelope icon"></i>
              <input class="input-field" type="email" placeholder="邮箱地址" name="email" required>
            </div>

            <div class="input-container">
              <i class="fa fa-lock icon"></i>
              <input class="input-field password" type="password" placeholder="密码" name="password" required>
              <i class="fa fa-eye icon toggle"></i>
            </div>

            <div class="input-container">
              <i class="fa fa-lock icon"></i>
              <input class="input-field" type="password" placeholder="再次输入密码" name="cpass" required>
              <i class="fa fa-eye icon"></i>
            </div>

          </div>


          <center><input type="submit" name="register" id="submit" value="注册" class="btn"></center>


          <div class="links">
            已有账户，去登录? <a href="login.php">点击登录</a>
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