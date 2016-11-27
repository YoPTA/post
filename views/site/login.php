<?php
$pagetitle = 'Авторизация';
$page_id = 'page_login';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';

?>

    <h2 align="center" >Авторизация</h2>
    <?php if (isset($errors) && is_array($errors)): ?>
        <div class="error font_size_twelve">
            <div class="er"></div>
            <h2 align="center">Ошибка входа</h2>

            <ul>
                <?php foreach ($errors as $error): ?>
                    <li class="type_none"> - <?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <br /><br />
    <?php endif; ?>

    <form method="post">
        <label for="login_name">Логин:</label><br />
        <input type="text" id="login_name" name="login_name" class="quarter" value="<?= $login_name ?>" /><br /><br />

        <label for="password">Пароль:</label><br />
        <input type="password" id="password" name="password" class="quarter" /><br /><br />

        <input type="submit" name="login" class="button one_eighth" value="Авторизоваться" />
    </form>


<?php include ROOT . '/views/layouts/footer.php'; ?>


