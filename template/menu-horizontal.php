<nav class="navbar">   
     <a href="http://localhost/"  class="navbar-brand"><?php require_once 'assets/logo-50.php'?></a>
<div class="navbar-header">
    <button class="navbar-toggle" id="navUp">
              <span class="bar bar1"></span>
              <span class="bar bar2"></span>
              <span class="bar bar3"></span>
            </button>
    <div class="navbar-menu nav-items">
    <?php
        $menuItems = getMenuItems($db);
        $current_page_url = getCurrentPageUrl($db);

    foreach ($menuItems as $item):
        $active = $current_page_url === $item['page_url'] ? 'active' : '';
        $generatedUrl = generateMenuUrl($db, $item['page_url']);
     ?>
        <a class="nav-item <?php echo $active; ?>" href="<?php echo $generatedUrl; ?>"><?php echo htmlspecialchars($item['name']); ?></a>
   
<?php endforeach; ?>
<?php if (isset($_SESSION['user_id'])): 
    $isAdmin = checkUserRole($_SESSION['user_id'], 'admin', $db);
    $isModerator = checkUserRole($_SESSION['user_id'], 'moderator', $db);
    $isUser = checkUserRole($_SESSION['user_id'], 'user', $db);
?>
    <?php if ($isAdmin || $isModerator): ?>
        <a class="nav-item" href="admin.php"><?php echo t("Dashboard"); ?></a>
    <?php endif; ?>
        <?php if ($isUser): ?>
    <?php $user_name = getUserNameById($db, $_SESSION['user_id']); ?>
    <p class="nav-item" style="margin-top: 15px;"><?php echo t("Hello!")." "; ?>, <?php echo htmlspecialchars($user_name); ?> <a class="nav-item" href="myaccount.php"><?php echo t("Profile"); ?></a></p>
<?php endif; ?>

<a class="nav-item" href="logout.php"><?php echo t("Sign out"); ?></a>
<?php else: ?>
    <a class="nav-item" href="login.php"><?php echo t("Log In"); ?></a>
<?php endif; ?>
</div>
</div>
</nav>
