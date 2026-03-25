<?php
session_start();

include("connection.php");

if (!isset($_SESSION['username'])) {
    header("location:login.php");
}

// 分页设置 - 改为每页显示 8 个项目（两行，每行 4 个）
$limit = 8; // 每页显示的项目数量
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// 获取总项目数
$total_query = "SELECT COUNT(*) as total FROM projects";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_projects = $total_row['total'];
$total_pages = ceil($total_projects / $limit);

// 获取当前页的项目数据
$projects_query = "SELECT * FROM projects ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$projects_result = mysqli_query($conn, $projects_query);
$projects = [];
while ($row = mysqli_fetch_assoc($projects_result)) {
    $projects[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>主页</title>

    <link href="resource/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <link rel="stylesheet"
        href="resource/fonts/bootstrap-icons.min.css">

    <link rel="stylesheet" href="resource/css/style.css">
    <style>
        .pagination-container {
            margin-top: 50px;
            margin-bottom: 40px;
        }
        .pagination-row {
            display: flex;
            justify-content: center;
            margin-bottom: 15px;
        }
        .page-numbers {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
        }
        .page-number-item {
            display: inline-block;
            padding: 8px 12px;
            text-decoration: none;
            color: #333;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            transition: all 0.2s;
        }
        .page-number-item:hover {
            background-color: #e9ecef;
            color: #333;
        }
        .page-number-item.active {
            background-color: #667eea;
            color: white;
            border-color: #667eea;
        }
        .nav-arrow {
            padding: 8px 16px;
            text-decoration: none;
            color: #333;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            margin: 0 10px;
        }
        .nav-arrow:hover {
            background-color: #e9ecef;
            color: #333;
        }
    </style>
</head>

<body>

    <!-- navbar section   -->

    <header class="navbar-section">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="#"><i class="bi bi-chat"></i> 杭州电鳐网络科技有限公司</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="#home">主页</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#services">快速发布</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="projects.php">服务大厅</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#about">关于我们</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#contact">联系我们</a>
                        </li>
                        <li class="nav-item">
                            <div class="dropdown">
                                <a class='nav-link dropdown-toggle' href='edit.php?id=$res_id' id='dropdownMenuLink'
                                    data-bs-toggle='dropdown' aria-expanded='false'>
                                    <i class='bi bi-person'></i>
                                </a>


                                <ul class="dropdown-menu mt-2 mr-0" aria-labelledby="dropdownMenuLink">

                                    <li>
                                        <?php

                                        $id = $_SESSION['id'];
                                        $query = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");

                                        while ($result = mysqli_fetch_assoc($query)) {
                                            $res_username = $result['username'];
                                            $res_email = $result['email'];
                                            $res_id = $result['id'];
                                        }


                                        echo "<a class='dropdown-item' href='edit.php?id=$res_id'>修改个人信息</a>";


                                        ?>

                                    </li>
                                    <li><a class="dropdown-item" href="logout.php">登出</a></li>
                                </ul>
                            </div>

                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>


    <div class="name">
        <center>欢迎用户：
            <?php
            // echo $_SESSION['valid'];
            
            echo $_SESSION['username'];

            ?>
            !
        </center>
    </div>

    <!-- hero section  -->

    <section id="home" class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-12 col-sm-12 text-content">
                    <!-- <h1>the digital service you really want</h1> -->
                    <p>我们是一个信息发布网站，您可以在这里发布信息，并查看其他用户发布的信息.
                    </p>
                    <button class="btn"><a href="#projects">探索服务</a></button>
                </div>
                <div class="col-lg-8 col-md-12 col-sm-12">
                    <img src="images/hero-image.png" alt="" class="img-fluid">
                </div>

            </div>
        </div>
    </section>

    <!-- services section  -->

    <section class="services-section" id="services">
        <div class="container">
            <div class="row">

                <div class="col-lg-6 col-md-12 col-sm-12 services">

                    <div class="row row1">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="card">
                                <img src="images/research.png" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h4 class="card-title">发现服务</h4>
                                    <p class="card-text">我们制定有效的策略，
                                        帮助您
                                        在整个范围内接触客户和潜在客户.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="card">
                                <img src="images/brand.png" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h4 class="card-title">品牌化</h4>
                                    <p class="card-text">品牌标识代表了视觉元素和资产
那
                                        区分一个品牌.</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row row2">

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="card">
                                <img src="images/ux.png" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h4 class="card-title">UI/UX Design</h4>
                                    <p class="card-text">UI/UX设计服务专注于打造直观且
                                        以用户为中心
                                        数字产品的接口.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="card">
                                <img src="../images/app-development.png" class="card-img-top" alt="...">
                                <div class="card-body">
                                    <h4 class="card-title">发展</h4>
                                    <p class="card-text">一个概念通过各种服务得以实现
                                        阶段，如
                                        作为规划、测试和部署.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-lg-6 col-md-12 col-sm-12 text-content">
                    <h3>services</h3>
                    <h1>我们可以通过我们的服务帮助您解决问题.</h1>
                    <p>我们是一家品牌战略与数字设计机构，致力于打造在文化中举足轻重的品牌
                        拥有超过多年的经验.</p>
                    <button class="btn"><a href="add_project.php" class="publish-btn" style="color: white;">
                        <i class="bi bi-plus-circle"></i> 发布服务
                    </a></button>
                </div>

            </div>
        </div>
    </section>

    <!-- about section  -->

    <section class="about-section" id="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <img src="images/about.jpg" alt="" class="img-fluid">
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 text-content">
                    <h3>我们是谁</h3>
                    <h1>为成长中的品牌提供创意和技术服务.</h1>

                    <p>我们公司专注于研究、品牌识别设计、用户界面/用户体验设计、开发以及图形设计
设计。为了帮助我们的客户提升业务，我们与全球各地的客户合作.</p>
                    <button>了解更多</button>
                </div>
            </div>
        </div>
    </section>

    
    <!-- project section  -->
    <section class="project-section" id="projects">
        <div class="container">
            <!-- 页面标题 -->
            <div class="row section-header">
                <div class="col-lg-6 col-md-12">
                    <h3>购买服务</h3>
                    <h1>好的服务应该被大众发现</h1>
                    <hr>
                </div>
                <div class="col-lg-6 col-md-12">
                    <p>我们倾注心血打造信息发布平台。我们让您的创意落地生根，助您梦想成就辉煌，拥有卓越体验。</p>
                </div>
            </div>
            
            <!-- 项目卡片 -->
            <div class="row project">
                <?php if (count($projects) > 0): ?>
                    <?php foreach ($projects as $project): ?>
                    <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                        <div class="card">
                            <img src="<?php echo !empty($project['image']) ? htmlspecialchars($project['image']) : 'images/project1.jpg'; ?>" 
                                 class="card-img-top" alt="<?php echo htmlspecialchars($project['title']); ?>">
                            <div class="card-body">
                                <div class="text">
                                    <h4 class="card-title"><?php echo htmlspecialchars($project['title']); ?></h4>
                                    <p class="card-text">时间 <?php echo date('Y-m-d H:i:s', strtotime($project['created_at'])); ?></p>
                                    <button>点击 购买</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p>暂无服务项目</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- 分页导航 -->
            <?php if ($total_pages > 1): ?>
            <div class="row">
                <div class="col-12 text-center pagination-container">
                    <!-- 第一行：上一页/下一页 -->
                    <div class="pagination-row">
                        <?php if ($page > 1): ?>
                        <a class="nav-arrow" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                            &laquo; 上一页
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($page < $total_pages): ?>
                        <a class="nav-arrow" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                            下一页 &raquo;
                        </a>
                        <?php endif; ?>
                    </div>
                    
                    <!-- 第二行：页码 -->
                    <div class="page-numbers">
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <?php if ($i == $page): ?>
                            <span class="page-number-item active"><?php echo $i; ?></span>
                            <?php else: ?>
                            <a class="page-number-item" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- contact section  -->

    <section class="contact-section" id="contact">
        <div class="container">

            <div class="row gy-4">

                <h1>联系我们</h1>
                <div class="col-lg-12">

                    <div class="row gy-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <i class="bi bi-geo-alt"></i>
                                <h3>地址</h3>
                                <p>A108 Adam Street,<br>New Delhi, 535022</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <i class="bi bi-telephone"></i>
                                <h3>手机号</h3>
                                <p>+91 9876545672<br>+91 8763456243</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <i class="bi bi-envelope"></i>
                                <h3>邮箱</h3>
                                <p>bragspot@gmail.com<br>brag@gmail.com</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <i class="bi bi-clock"></i>
                                <h3>Open Hours</h3>
                                <p>Monday - Friday<br>9:00AM - 05:00PM</p>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- <div class="col-lg-6 form">
                    <form action="contact.php" method="post" class="php-email-form">
                        <div class="row gy-4">

                            <div class="col-md-6">
                                <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                            </div>

                            <div class="col-md-6 ">
                                <input type="email" class="form-control" name="email" placeholder="Your Email" required>
                            </div>

                            <div class="col-md-12">
                                <input type="text" class="form-control" name="subject" placeholder="Subject" required>
                            </div>

                            <div class="col-md-12">
                                <textarea class="form-control" name="message" rows="5" placeholder="Message"
                                    required></textarea>
                            </div>

                            <div class="col-md-12 text-center">
                                <button type="submit">Send Message</button>
                            </div>

                        </div>
                    </form>

                </div> -->

            </div>

        </div>
    </section>

    <!-- footer section  -->

    <footer>
        <div class="container">
            <div class="row">

                <div class="col-lg-2 col-md-12 col-sm-12">
                    <p>备案号：</p>
                </div>

                <div class="col-lg-2 col-md-12 col-sm-12">
                    <p>2026-company</p>
                </div>

                <div class="col-lg-1 col-md-12 col-sm-12">
                    <!-- back to top  -->

                    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
                            class="bi bi-arrow-up-short"></i></a>
                </div>

            </div>

        </div>

    </footer>

    <script src="resource/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>
    
    <script>
        // 页面加载时检查 URL 是否有 projects 锚点并滚动到相应位置
        document.addEventListener('DOMContentLoaded', function() {
            if (window.location.hash === '#projects') {
                const projectsSection = document.getElementById('projects');
                if (projectsSection) {
                    // 使用 setTimeout 确保页面完全渲染后再滚动
                    setTimeout(function() {
                        projectsSection.scrollIntoView({ 
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }, 200);
                }
            }
        });
        
        // 监听分页链接点击事件，确保平滑滚动
        document.querySelectorAll('a[href*="?page="]').forEach(function(link) {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href && href.includes('#projects')) {
                    // 让链接正常跳转，浏览器会自动处理锚点
                    // 但我们添加平滑滚动效果
                    setTimeout(function() {
                        const projectsSection = document.getElementById('projects');
                        if (projectsSection) {
                            projectsSection.scrollIntoView({ 
                                behavior: 'smooth',
                                block: 'start'
                            });
                        }
                    }, 100);
                }
            });
        });
    </script>
</body>

</html>