<?php global $dbh; ?>
<?php
include $_SERVER["DOCUMENT_ROOT"] . "/connection_database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $image = '';

    // Перевірка та обробка завантаженого файлу
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploadDir = $_SERVER["DOCUMENT_ROOT"] . '/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $uploadFile = $uploadDir . basename($_FILES['image']['name']);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            $image = '/uploads/' . basename($_FILES['image']['name']);
        } else {
            echo "Помилка при переміщенні файлу.";
            exit();
        }
    }

    // Prepare the SQL statement
    if (!empty($image)) {
        $stmt = $dbh->prepare("UPDATE users SET name = :name, email = :email, phone = :phone, image = :image WHERE id = :id");
        $stmt->bindParam(':image', $image);
    } else {
        $stmt = $dbh->prepare("UPDATE users SET name = :name, email = :email, phone = :phone WHERE id = :id");
    }

    // Bind the parameters
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':phone', $phone);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Користувач успішно оновлений.";
    } else {
        echo "Помилка при оновленні користувача.";
    }
}
?>

