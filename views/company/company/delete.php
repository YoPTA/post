<?php
$pagetitle = 'Удалить организацию';
$page_id = 'page_index';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';

?>
    <script src="/template/js/inputValidate.js"></script>

    <h2 align="center"><?= $pagetitle ?></h2>
    <div class="font_size_twelve" align="center">
        <a href="/company/company_index?c_type=<?= $c_type ?>&search_value=<?= $search_param['search_value'] ?>&page<?= $page ?>">
            &#8592; Вернуться к организациям
        </a>
    </div>
    <br /><br />

    <form method="POST">
        <p class="font_size_twelve">Желаете удалить организацию:
            <?= $company['name']  ?>?
        </p>
        <input type="submit" name="yes" value="Да" class="button one_sixteenth" />
        <span class="right_indent"></span>
        <input type="submit" name="no" value="Нет" class="button one_sixteenth" />
    </form>

<?php include ROOT . '/views/layouts/footer.php'; ?>