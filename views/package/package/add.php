<?php
$pagetitle = 'Посылка';
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
        <label for="number">Посылка</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="number_correct"></span><br />
        <input type="text" placeholder="Посылка" id="number" name="number" class="quarter <?php if (isset($errors['number'])) echo 'error'; ?>" value="<?= $package['number'] ?>"
               onblur="InputCount('number', 'quarter', 128, 'number_correct', 'Посылка не может быть такой длины', 'Необходимо заполнить посылку', '')"
            /><br /><br /><br />

        <input type="submit" name="add" value="Сохранить" class="button one_eighth" /><br /><br />

    </form>

<?php include ROOT . '/views/layouts/footer.php'; ?>