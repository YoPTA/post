<?php
$pagetitle = 'Редактировать ip-адрес';
$page_id = 'page_index';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';

?>
    <script src="/template/js/inputValidate.js"></script>

    <h2 align="center"><?= $pagetitle ?></h2>
    <div class="font_size_twelve" align="center">
        <a href="/company/company_index?c_type=<?= $c_type ?>&search_value=<?= $search_param['search_value'] ?>&page=<?= $page ?>">
            &#8592; Вернуться к организациям
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
        <label for="ip_address">Ip-адрес</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="ip_address_correct"></span><br />
        <input type="text" placeholder="Ip-адрес" id="ip_address" name="ip_address" class="one_eighth <?php if (isset($errors['ip_address'])) echo 'error'; ?>" value="<?= $company['ip_address'] ?>"
               onblur="InputCountCanEmpty('ip_address', 'one_eighth', 21, 'ip_address_correct', 'Ip-адрес не может быть такой длины', '')"
            /><br /><br />

        <input type="submit" name="edit" value="Редактировать" class="button one_eighth" /><br /><br />

    </form>

<?php include ROOT . '/views/layouts/footer.php'; ?>