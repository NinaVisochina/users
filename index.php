<?php global $dbh; ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Список користувачів</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/site.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php include $_SERVER["DOCUMENT_ROOT"] . "/_header.php"; ?>
<?php include $_SERVER["DOCUMENT_ROOT"] . "/connection_database.php"; ?>
<div class="container">
    <h1 class="text-center">
        Список користувачів
    </h1>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Id</th>
            <th scope="col">ПІБ</th>
            <th scope="col">Фото</th>
            <th scope="col">Пошта</th>
            <th scope="col">Телефон</th>
            <th scope="col">Дії</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $sql = 'SELECT * FROM users';
        foreach ($dbh->query($sql) as $row) {
            $id = $row['id'];
            $name = $row['name'];
            $image = $row['image'];
            $email = $row['email'];
            $phone = $row['phone'];

            echo "
            <tr>
                <th scope='row'>$id</th>
                <td>$name</td>
                <td>
                    <img src='$image' alt='Фото' width='150'>
                </td>
                <td>$email</td>
                <td>$phone</td>
                <td>
                    <button class='btn btn-primary edit-btn' data-id='$id' data-name='$name' data-image='$image' data-email='$email' data-phone='$phone'>Редагувати</button>
                    <button class='btn btn-danger delete-btn' data-id='$id'>Видалити</button>
                </td>
            </tr>
            ";
        }
        ?>
        </tbody>
    </table>
</div>

<!-- Модальне вікно для редагування користувача -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Редагувати користувача</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" enctype="multipart/form-data">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="mb-3">
                        <label for="edit-name" class="form-label">ПІБ</label>
                        <input type="text" class="form-control" id="edit-name" name="name">
                    </div>
                    <div class="mb-3">
                        <label for="edit-image" class="form-label">Фото</label>
                        <input type="file" class="form-control" id="edit-image" name="image">
                    </div>
                    <div class="mb-3">
                        <label for="edit-email" class="form-label">Пошта</label>
                        <input type="text" class="form-control" id="edit-email" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="edit-phone" class="form-label">Телефон</label>
                        <input type="text" class="form-control" id="edit-phone" name="phone">
                    </div>
                    <button type="submit" class="btn btn-primary">Зберегти зміни</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // Показати модальне вікно для редагування
        $('.edit-btn').click(function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var image = $(this).data('image');
            var email = $(this).data('email');
            var phone = $(this).data('phone');

            $('#edit-id').val(id);
            $('#edit-name').val(name);
            $('#edit-email').val(email);
            $('#edit-phone').val(phone);

            $('#editModal').modal('show');
        });

        // Надіслати форму для редагування через AJAX
        $('#editForm').submit(function(event) {
            event.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: '/update.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#editModal').modal('hide');
                    location.reload();
                },
                error: function() {
                    alert('Виникла помилка при редагуванні користувача.');
                }
            });
        });

        // Видалення користувача через AJAX
        $('.delete-btn').click(function() {
            if (confirm('Ви впевнені, що хочете видалити цього користувача?')) {
                var id = $(this).data('id');

                $.ajax({
                    url: '/delete.php',
                    type: 'POST',
                    data: { id: id },
                    success: function(response) {
                        location.reload();
                    },
                    error: function() {
                        alert('Виникла помилка при видаленні користувача.');
                    }
                });
            }
        });
    });
</script>
</body>
</html>


