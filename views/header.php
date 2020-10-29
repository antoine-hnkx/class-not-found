<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo TITLE ?> - You've got a question ? We've got you covered !</title>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="<?php echo IMAGES ?>favicon.png">
    <link rel="stylesheet" href="<?php echo VIEWS ?>css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo VIEWS ?>css/font-awesome-all.css">
    <link rel="stylesheet" href="<?php echo VIEWS ?>css/style.css">
</head>
<body>
<!-- Navigation -->
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="index.php"><i class="far fa-comment-alt"></i> ClassNotFound</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <?php if(!isset($memberLogin)) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=register">Register</a>
                </li>
                <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=profile"><?php echo $memberLogin ; ?></a>
                </li>
                <?php } ?>
                <?php if(isset($isAdmin)) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=admin">Admin</a>
                </li>
                <?php } ?>
                <li class="nav-item">
                    <a class="nav-link" href="index.php?action=<?php echo !isset($memberLogin) ? 'login">Login' : 'logout">Logout'?></a>
                </li>
                <li class="nav-item">
                    <a id="btn-new-question" class="btn btn-light" href="index.php?action=editQuestion">New Question</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Page Content -->
<section id="main">
    <div class="container">
        <div class="row">
            <!-- Categories Menu -->
            <div class="col-md-3">
                <h3 id="categories" class="my-4">Categories</h3>
                <div class="list-group">
                    <?php foreach ($categories as $i => $category) { ?>
                    <a href="index.php?action=homepage&category_id=<?php echo $category->id(); ?>" class="list-group-item category"><?php echo $category->html_name(); ?></a>
                    <?php } ?>
                </div>
            </div>
            <!-- Main Content + Search bar -->
            <div class="col-md-9">
                <!-- Search form -->
                <div class="container-fluid form-search">
                    <form action="index.php?action=homepage" method="post">
                        <div class="input-group">
                            <input class="form-control" type="text" placeholder="Search" name="search" <?php if(isset($search)) echo 'value="' . $search . '"' ?>>
                            <div class="input-group-btn">
                            <button id="btn-search" class="btn btn-default" type="submit" name="form_search"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
