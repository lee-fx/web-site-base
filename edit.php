<?php
session_start();

include("connection.php");

if (!isset($_SESSION['username'])) {
    header("location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>修改个人信息</title>
    <link rel="stylesheet" href="resource/css/font-awesome.min.css">
    <link href="resource/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="resource/css/style1.css">
    <style>
        body {
        margin: 0;
        padding: 0;
        min-height: 100vh;
        background-color: #f5f5f5;
        }
        
        .container {
        display: flex;
        justify-content: center;
        align-items: center;
        /* min-height: 100vh; */
        width: 100%;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="form-box box">

            <?php

            if (isset($_POST['update'])) {
                $username = $_POST['username'];
                $email = $_POST['email'];
                $password = md5($_POST['password']);

                $id = $_SESSION['id'];
                $edit_query = mysqli_query($conn, "UPDATE users SET username='$username', email='$email', password='$password' WHERE id = $id");

                if ($edit_query) {
                    echo "<div class='message'>
                <p>更新成功</p>
                </div><br>";
                    echo "<a href='home.php'><button class='btn'>跳转首页</button></a>";
                }
            } else {

                $id = $_SESSION['id'];
                $query = mysqli_query($conn, "SELECT * FROM users WHERE id = $id") or die("error occurs");

                while ($result = mysqli_fetch_assoc($query)) {
                    $res_username = $result['username'];
                    $res_email = $result['email'];
                    $res_password = $result['password'];
                    $res_id = $result['id'];
                }

                ?>

                <header>修改个人信息</header>
                <form action="#" method="POST" enctype="multipart/form-data">

                    <div class="form-box">

                        <div class="input-container">
                            <i class="fa fa-user icon"></i>
                            <input class="input-field" type="text" placeholder="用户名" name="username"
                                value="<?php echo $res_username; ?>" required>
                        </div>

                        <div class="input-container">
                            <i class="fa fa-envelope icon"></i>
                            <input class="input-field" type="email" placeholder="邮箱" name="email"
                                value="<?php echo $res_email; ?>" required>
                        </div>

                        <div class="input-container">
                            <i class="fa fa-lock icon"></i>
                            <input class="input-field password" type="password" placeholder="密码" name="password"
                                value="<?php echo $res_password; ?>" required>
                            <i class="fa fa-eye toggle icon"></i>
                        </div>

                    </div>


                    <div class="field">
                        <input type="submit" name="update" id="submit" value="修改" class="btn">
                    </div>


                </form>
            </div>
        <?php } ?>
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