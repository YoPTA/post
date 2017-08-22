<?php
$pagetitle = 'Удалить посылку';
$page_id = 'page_index';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';

?>
    <script src="/template/js/inputValidate.js"></script>

    <h2 align="center"><?= $pagetitle ?></h2>
    <div class="font_size_twelve" align="center">
        <a href="/site/index?<?= $link_get_param ?>">
            &#8592; Вернуться на главную
        </a>
    </div>
    <br /><br />

    <form method="POST">
        <p class="font_size_twelve">Желаете удалить посылку: <?= $package['number'] ?>?</p>
        <input type="submit" name="yes" value="Да" class="button one_sixteenth" />
        <span class="right_indent"></span>
        <input type="submit" name="no" value="Нет" class="button one_sixteenth" />
    </form>

<?php include ROOT . '/views/layouts/footer.php'; ?>