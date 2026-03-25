<?php
session_start();

include("connection.php");

// 检查登录状态
if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit();
}

$error = '';
$success = false;

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, trim($_POST['title']));
    $content = isset($_POST['content']) ? mysqli_real_escape_string($conn, $_POST['content']) : '';
    $image = isset($_POST['image']) ? mysqli_real_escape_string($conn, trim($_POST['image'])) : '';
    $price = isset($_POST['price']) ? mysqli_real_escape_string($conn, trim($_POST['price'])) : '';
    $user_id = $_SESSION['id'];
    
    if (!empty($title)) {
        // 如果图片为空，使用默认图片
        if (empty($image)) {
            $image = 'images/project1.jpg';
        }
        
        $insert_query = "INSERT INTO projects (title, content, image, price, user_id) VALUES ('$title', '$content', '$image', '$price', '$user_id')";
        if (mysqli_query($conn, $insert_query)) {
            $success = true;
            // 清空表单数据
            $_POST = array();
        } else {
            $error = "发布失败：" . mysqli_error($conn);
        }
    } else {
        $error = "请填写服务标题";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>发布新服务</title>

    <link href="resource/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <link rel="stylesheet"
        href="resource/fonts/bootstrap-icons.min.css">

    <!-- Quill 富文本编辑器样式 -->
    <link href="resource/css/quill.snow.css" rel="stylesheet">

    <link rel="stylesheet" href="resource/css/style.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', 'Microsoft YaHei', sans-serif;
            min-height: 100vh;
        }
        
        .navbar-section {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .add-project-section {
            padding: 60px 0;
        }
        
        .add-project-card {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .add-project-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .add-project-header h2 {
            margin: 0;
            font-weight: 700;
            font-size: 1.8rem;
        }
        
        .add-project-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }
        
        .add-project-body {
            padding: 40px;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-control {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .form-text {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 5px;
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            color: white !important;
            border: none !important;
            padding: 14px 40px !important;
            border-radius: 25px !important;
            font-weight: 600 !important;
            font-size: 1rem !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            width: 100% !important;
            margin-top: 20px !important;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4) !important;
        }
        
        .btn-cancel {
            background-color: #6c757d !important;
            color: white !important;
            border: none !important;
            padding: 12px 30px !important;
            border-radius: 25px !important;
            font-weight: 600 !important;
            font-size: 0.95rem !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            display: inline-block !important;
            text-decoration: none !important;
            margin-top: 15px !important;
        }
        
        .btn-cancel:hover {
            background-color: #5a6268 !important;
            color: white !important;
            transform: translateY(-2px) !important;
        }
        
        .alert {
            border-radius: 10px;
            margin-bottom: 25px;
            padding: 15px 20px;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .back-link:hover {
            color: #764ba2;
            transform: translateX(-5px);
        }
        
        /* 富文本编辑器样式 */
        .ql-editor {
            min-height: 200px;
            font-size: 0.95rem;
        }
        
        .ql-toolbar {
            border-radius: 8px 8px 0 0 !important;
            background-color: #f8f9fa;
        }
        
        .ql-container {
            border-radius: 0 0 8px 8px !important;
        }
        
        .ql-toolbar.ql-snow {
            border-color: #dee2e6 !important;
        }
        
        .ql-container.ql-snow {
            border-color: #dee2e6 !important;
        }
        
        .ql-editor.ql-blank::before {
            color: #6c757d;
            font-style: normal;
        }
        
        @media (max-width: 768px) {
            .add-project-body {
                padding: 30px 20px;
            }
            
            .add-project-header h2 {
                font-size: 1.5rem;
            }
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
                            <a class="nav-link" href="projects.php">服务大厅</a>
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

    <!-- add project section  -->
    <section class="add-project-section">
        <div class="container">
            <div class="add-project-card">
                <!-- 头部 -->
                <div class="add-project-header">
                    <h2><i class="bi bi-plus-circle"></i> 发布新服务</h2>
                    <p>填写以下信息来创建您的服务项目</p>
                </div>
                
                <!-- 主体表单 -->
                <div class="add-project-body">
                    <!-- 返回链接 -->
                    <a href="projects.php" class="back-link">
                        <i class="bi bi-arrow-left"></i> 返回服务大厅
                    </a>
                    
                    <!-- 成功提示 -->
                    <?php if ($success): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> 服务发布成功！
                        <a href="projects.php" class="alert-link">查看服务列表</a>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    
                    <!-- 错误提示 -->
                    <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="add_project.php">
                        <div class="mb-4">
                            <label for="title" class="form-label">
                                <i class="bi bi-card-heading"></i> 服务标题 <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="title" 
                                   name="title" 
                                   placeholder="请输入服务标题（例如：网站开发、UI 设计）" 
                                   value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>"
                                   required>
                        </div>
                        
                        <!-- <div class="mb-4">
                            <label for="image" class="form-label">
                                <i class="bi bi-image"></i> 图片路径
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="image" 
                                   name="image" 
                                   placeholder="例如：images/project1.jpg"
                                   value="<?php echo isset($_POST['image']) ? htmlspecialchars($_POST['image']) : ''; ?>">
                            <small class="form-text">输入图片的路径地址，留空则使用默认图片</small>
                        </div> -->
                        
                        <div class="mb-4">
                            <label for="content" class="form-label">
                                <i class="bi bi-file-text"></i> 服务内容详情
                            </label>
                            <div id="editor-container">
                                <textarea id="content" name="content"><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
                            </div>
                            <small class="form-text">详细描述您的服务内容、特点和优势（支持富文本编辑）</small>
                        </div>
                        
                        <div class="mb-4">
                            <label for="price" class="form-label">
                                <i class="bi bi-currency-yuan"></i> 服务价格
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="price" 
                                   name="price" 
                                   placeholder="例如：99.00"
                                   value="<?php echo isset($_POST['price']) ? htmlspecialchars($_POST['price']) : ''; ?>">
                        </div>
                        
                        <button type="submit" class="btn-submit">
                            <i class="bi bi-send"></i> 立即发布服务
                        </button>
                        
                        <a href="projects.php" class="btn-cancel">
                            <i class="bi bi-x-circle"></i> 取消
                        </a>
                    </form>
                </div>
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
    
    <!-- Quill 富文本编辑器 -->
    <script src="resource/js/quill.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 初始化富文本编辑器 - 使用正确的 ES6 类实例化方式
            var editor = new Quill('#editor-container', {
                theme: 'snow',
                placeholder: '请详细描述您的服务内容、特点、优势...',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'align': [] }],
                        ['link', 'image'],
                        ['clean']
                    ]
                }
            });
            
            // 将编辑器内容同步到 textarea
            editor.on('text-change', function() {
                var content = editor.getText() ? editor.root.innerHTML : '';
                document.getElementById('content').value = content;
            });
            
            // 如果有保存的内容，加载到编辑器
            <?php if (isset($_POST['content']) && !empty($_POST['content'])): ?>
            var savedContent = <?php echo json_encode($_POST['content']); ?>;
            if (savedContent) {
                editor.clipboard.dangerouslyPasteHTML(savedContent);
            }
            <?php endif; ?>
            
            // 表单提交前确保内容已同步
            document.querySelector('form').addEventListener('submit', function(e) {
                var content = editor.getText() ? editor.root.innerHTML : '';
                document.getElementById('content').value = content;
            });
        });
    </script>
</body>

</html>