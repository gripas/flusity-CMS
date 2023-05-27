<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
  }
define('IS_ADMIN', true);

define('ROOT_PATH', realpath(dirname(__FILE__) . '/../../') . '/');

require_once ROOT_PATH . 'security/config.php';
require_once ROOT_PATH . 'core/functions/functions.php';
secureSession();
$db = getDBConnection($config);  
$language_code = getLanguageSetting($db);
$translations = getTranslations($db, $language_code);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if (deleteTranslation($db, $id)) {
        $_SESSION['success_message'] = t("Translation successfully deleted.");
    } else {
        $_SESSION['error_message'] = t("Failed to delete translation.");
    }
} else {
    $_SESSION['error_message'] = t("Error: Please specify translation ID.");
}

header("Location: language.php");
exit;
