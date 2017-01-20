<?php
$pagetitle = 'Удалить адрес организации';
$page_id = 'page_index';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';

?>
    <script src="/template/js/inputValidate.js"></script>

    <h2 align="center"><?= $pagetitle ?></h2>
    <div class="font_size_twelve" align="center">
        <a href="/company/company_address_index?c_type=<?= $c_type ?>&search_value=<?= $search_param['search_value'] ?>&page=<?= $page ?>&cid=<?= $cid ?>">
            &#8592; Вернуться к адресам
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
        <p class="font_size_twelve">Желаете удалить адрес организации:
            <b><?= $string_utility->getAddressToView(3, $company_address, null)  ?></b>?
        </p>
        <input type="submit" name="yes" value="Да" class="button one_sixteenth" />
        <span class="right_indent"></span>
        <input type="submit" name="no" value="Нет" class="button one_sixteenth" />
    </form>

<?php include ROOT . '/views/layouts/footer.php'; ?>