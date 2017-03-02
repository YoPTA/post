<?php
$pagetitle = 'Добавить объект посылки';
$page_id = 'page_index';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';

?>
    <script src="/template/js/inputValidate.js"></script>

    <h2 align="center"><?= $pagetitle ?></h2>
    <div class="font_size_twelve" align="center">
        <a href="/site/choose">
            &#8592; Вернуться к созданию посылки
        </a>
    </div>
    <br /><br />

<?php if (isset($errors) && is_array($errors)): ?>
    <div class="error font_size_twelve">
        <div class="er"></div>
        <h2 align="center">Ошибка ввода</h2>

        <ul>
            <?php foreach ($errors as $error): ?>
                <li class="type_none"> - <?php echo $error; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <br /><br />
<?php endif; ?>


    <form method="POST">
        <label for="name">Объект посылки</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="name_correct"></span><br />
        <input type="text" placeholder="Объект посылки" id="name" name="name" class="half <?php if (isset($errors['name'])) echo 'error'; ?>" value="<?= $package_object['name'] ?>"
               onblur="InputCount('name', 'half', 512, 'name_correct', 'Объект посылки не может быть такой длины', 'Необходимо заполнить объект посылки', '')"
            /><br /><br /><br />

        <input type="submit" name="add" value="Сохранить" class="button one_eighth" /><br /><br />

    </form>

<?php include ROOT . '/views/layouts/footer.php'; ?>