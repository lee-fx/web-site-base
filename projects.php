<?php
session_start();

include("connection.php");

// 检查登录状态
if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit();
}

// 处理发布服务表单// 分页设置 - 每页显示 8 个项目（两行，每行 4 个）
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
    <title>服务大厅</title>

    <link href="resource/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <link rel="stylesheet"
        href="resource/fonts/bootstrap-icons.min.css">

    <link rel="stylesheet" href="resource/css/style.css">
    <style>
        body {
            background-color: white;
            font-family: 'Segoe UI', 'Microsoft YaHei', sans-serif;
        }
        
        .navbar-section {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .project-section {
            padding: 40px 0;
            background-color: rgba(133, 160, 126, 0.1);
        }
        
        .card {
            transition: transform 0.3s ease;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(233, 213, 213, 0.1);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }
        
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        
        .card-body {
            padding: 20px;
            background-color: #ffffff;
        }
        
        .card-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #333;
        }
        
        .card-text {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }
        
        .card button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .card button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
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
        
        .section-header {
            margin-bottom: 40px;
        }
        
        .section-header h3 {
            color: #667eea;
            font-weight: 600;
        }
        
        .section-header h1 {
            color: #333;
            font-weight: 700;
        }
        
        .section-header p {
            color: #666;
            line-height: 1.6;
        }
        
        /* 发布服务按钮样式 - 提高优先级 */
        a.publish-btn, button.publish-btn, .publish-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            border: none !important;
            padding: 12px 30px !important;
            border-radius: 25px !important;
            font-weight: 600 !important;
            font-size: 1rem !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3) !important;
            margin-bottom: 30px !important;
            text-decoration: none !important;
            display: inline-block !important;
        }
        
        a.publish-btn:hover, button.publish-btn:hover, .publish-btn:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.45) !important;
            color: white !important;
        }
    </style>
</head>

<body>

    <!-- navbar section   -->
    <header class="navbar-section">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="home.php"><i class="bi bi-chat"></i> XXXXXXXXXX</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="home.php">主页</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="home.php#services">快速发布</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="projects.php">服务大厅</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="home.php#about">关于我们</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="home.php#contact">联系我们</a>
                        </li>
                        <li class="nav-item">
                            <div class="dropdown">
                                <?php
                                $id = $_SESSION['id'];
                                $query = mysqli_query($conn, "SELECT * FROM users WHERE id = $id");
                                $result = mysqli_fetch_assoc($query);
                                $res_id = $result['id'];
                                ?>
                                <a class='nav-link dropdown-toggle' href='#' id='dropdownMenuLink'
                                    data-bs-toggle='dropdown' aria-expanded='false'>
                                    <i class='bi bi-person'></i> <?php echo htmlspecialchars($result['username']); ?>
                                </a>
                                <ul class="dropdown-menu mt-2 mr-0" aria-labelledby="dropdownMenuLink">
                                    <li><a class="dropdown-item" href="edit.php?id=<?php echo $res_id; ?>">修改个人信息</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="logout.php">登出</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- project section  -->
    <section class="project-section" id="projects">
        <div class="container">
            <!-- 页面标题 -->
            <div class="row section-header">
                <div class="col-lg-6 col-md-12">
                    <h3>购买服务</h3>
                    <h1>好的服务应该被大众发现</h1>
            </div>
                                         <div class="col-lg-6 col-md-12 d-flex align-items-end justify-content-end">
                    <a href="add_project.php" class="publish-btn">
                        <i class="bi bi-plus-circle"></i> 发布服务
                    </a>
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
                    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
                        <i class="bi bi-arrow-up-short"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script src="resource/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>
