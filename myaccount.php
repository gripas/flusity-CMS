<?php
  if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
    }
    /*
    @MiniFrame css karkasas Lic GNU
    Author Darius Jakaitis, author web site http://www.manowebas.lt
    fix-content
    */
    require_once 'core/functions/functions.php';

    list($db, $config, $prefix) = initializeSystem();
    secureSession($db, $prefix);
    $language_code = getLanguageSetting($db, $prefix);
    $translations = getTranslations($db, $prefix, $language_code);

   require_once getThemePath($db, $prefix, '/template/header.php'); 
    $db = getDBConnection($config);

    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php');
        exit();
    } else {
        if (!checkUserRole($_SESSION['user_id'], 'user', $db, $prefix) && !checkUserRole($_SESSION['user_id'], 'admin', $db, $prefix) && !checkUserRole($_SESSION['user_id'], 'moderator', $db, $prefix)) {
            header('Location: index.php');
            exit();
        }
    }
?>
<?php require_once getThemePath($db, $prefix, 'template/myaccount_form.php'); ?>
      
<?php require_once getThemePath($db, $prefix, '/template/footer.php'); ?>