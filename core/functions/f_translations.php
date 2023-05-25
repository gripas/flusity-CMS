<?php
function getTranslations($db, $language_code) {
    $query = $db->prepare("SELECT translation_key, translation_value FROM translations WHERE language_code = :language_code");
    $query->execute(['language_code' => $language_code]);

    $translations = $query->fetchAll(PDO::FETCH_KEY_PAIR);
    return $translations;
}
function t($key) {
    global $translations;
    global $settings;

    // patikrinti, ar 'language' yra nustatyta $settings masyve
    if (isset($settings['language']) && $settings['language'] === 'en') {
        return $key;
    }

    if (isset($translations[$key])) {
        return $translations[$key];
    }

    return $key;
}


function getTranslationsWords($db, $limit = 15, $offset = 0, $search_term = '') {
    if ($search_term === '') {
        $stmt = $db->prepare("SELECT * FROM `translations` ORDER BY id DESC LIMIT :limit OFFSET :offset");
    } else {
        $stmt = $db->prepare("SELECT * FROM `translations` WHERE translation_key LIKE :search_term OR translation_value LIKE :search_term ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':search_term', '%' . $search_term . '%', PDO::PARAM_STR);
    }

    $stmt->bindValue(':limit', (int) $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function addTranslation($db, $language_code, $translation_key, $translation_value) {
$stmt = $db->prepare("INSERT INTO `translations` (language_code, translation_key, translation_value) VALUES (:language_code, :translation_key, :translation_value)");
$stmt->bindParam(':language_code', $language_code);
$stmt->bindParam(':translation_key', $translation_key);
$stmt->bindParam(':translation_value', $translation_value);
return $stmt->execute();
}

function deleteTranslation($db, $id) {
$stmt = $db->prepare("DELETE FROM `translations` WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
return $stmt->execute();
}
function countTranslations($db) {
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM translations");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}
function getAllLanguages($db) {
    $stmt = $db->prepare("SELECT DISTINCT language_code FROM translations");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTranslationById($db, $id) {
    $stmt = $db->prepare("SELECT * FROM `translations` WHERE `id` = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function updateTranslation($db, $id, $language_code, $translation_key, $translation_value) {
    $stmt = $db->prepare("UPDATE `translations` SET `language_code` = :language_code, `translation_key` = :translation_key, `translation_value` = :translation_value WHERE `id` = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':language_code', $language_code, PDO::PARAM_STR);
    $stmt->bindParam(':translation_key', $translation_key, PDO::PARAM_STR);
    $stmt->bindParam(':translation_value', $translation_value, PDO::PARAM_STR);
    $stmt->execute();
}
function getLanguageSetting($db) {
    $stmt = $db->prepare("SELECT `language` FROM `settings`");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return isset($result['language']) ? $result['language'] : 'en';
}