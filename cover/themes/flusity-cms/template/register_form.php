<?php 
 if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
    }
/*
 @MiniFrame css karkasas Lic GNU
 Author Darius Jakaitis, author web site http://www.manowebas.lt
 fix-content
*/
  ?>
<header class="header easy-header">
    <div class="overlay"></div>
    <canvas id="canvas"></canvas>
<?php require_once 'menu-horizontal.php';  
?>
        <div class="col-md-12 easy-hello-box">
            <h1>
              <span class="easy-word">Flusity free CMS for all</span>
          </h1>
        </div>
</header>
<main class="main" id="main">
  <div class="container">
    <div class="row">
        <div class="col-sm-4">
        <h2><?php echo t("Registration system");?></h2>
        <p><?php echo t("Content management system for personal websites");?></p>
        <img src="/core/tools/img/flusity-b.png" alt="Flusity logo">
    </div>
        <div class="col-sm-4">
<h1 class="h3 mb-3 fw-normal"><?php echo t("Registration");?></h1>
<?php if (isset($error_message)): ?>
    <div id="error_message" class="alert alert-danger" role="alert">
        <?php echo htmlspecialchars($error_message); ?>
    </div>
 
<?php endif; ?>
<?php if($registration_enable == 1) { ?>
<form method="POST" action="">
    <div class="form-floating">
        <input type="text" class="form-control" id="login_name" name="login_name" placeholder="<?php echo t("Login Name");?>" required>
        <label for="login_name"><?php echo t("Login Name");?></label>
    </div>
    <br>
    <div class="form-floating">
        <input type="text" class="form-control" id="username" name="username" placeholder="<?php echo t("User Name");?>" required>
        <label for="username"><?php echo t("User Name");?></label>
    </div>
    <br>
    <div class="form-floating">
        <input type="text" class="form-control" id="surname" name="surname" placeholder="<?php echo t("Surname");?>" required>
        <label for="surname"><?php echo t("Surname");?></label>
    </div>
    <br>
    <div class="form-floating">
        <input type="text" class="form-control" id="phone" name="phone" placeholder="<?php echo t("Phone");?>" required>
        <label for="phone"><?php echo t("Phone");?></label>
    </div>
    <br>
    <div class="form-floating">
        <input type="email" class="form-control" id="email" name="email" placeholder="<?php echo t("Email");?>" required>
        <label for="email"><?php echo t("Email");?></label>
    </div>
    <br>
    <div class="form-floating">
        <input type="password" class="form-control" id="password" name="password" placeholder="<?php echo t("Password");?>" required>
        <label for="password"><?php echo t("Password");?></label>
    </div>
    <br>
    <div class="form-floating">
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="<?php echo t("Repeat the password");?>" required>
        <label for="confirm_password"><?php echo t("Repeat the password");?></label>
    </div>
    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
    <br>
    <button class="w-100 btn btn-lg btn-primary mb-3" type="submit"><?php echo t("Register");?></button>
</form>
<?php } else {
    echo "<p>" . t("Registration is currently suspended. Please try again later") . "</p>
    <div class='container mt-5 mb-3 no-register'></div>";
}?>
<p><?php echo t("Back to"); ?>&nbsp;<a href="/" class="btn-link"><?php echo strtolower(t("Home page")); ?></a>&nbsp;<?php echo t("or"); ?>&nbsp;
&nbsp;<a href="login.php" class="btn-link"><?php echo strtolower(t("Log In")); ?></a>&nbsp;<p>

</div>
</div>
</div>
</main>