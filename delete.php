<?php global $dbh; ?>
<?php
include $_SERVER["DOCUMENT_ROOT"] . "/connection_database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];

    // Отримати інформацію про користувача
    $stmt = $dbh->prepare("SELECT image FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Видалити зображення, якщо воно існує
    if ($user && !empty($user['image'])) {
        $imagePath = $_SERVER["DOCUMENT_ROOT"] . $user['image'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    // Видалити користувача з бази даних
    $stmt = $dbh->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo "Користувач успішно видалений.";
    } else {
        echo "Помилка при видаленні користувача.";
    }
}
?>

