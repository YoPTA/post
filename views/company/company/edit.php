<?php
$pagetitle = 'Редактировать организацию';
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
        <label for="name">Наименование*</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="name_correct"></span><br />
        <input type="text" placeholder="Наименование" id="name" name="name" class="half <?php if (isset($errors['name'])) echo 'error'; ?>" value="<?= $company['name'] ?>"
               onblur="InputCount('name', 'half', 256, 'name_correct', 'Наименование не может быть такой длины', 'Необходимо заполнить наименование', '')"
            /><br /><br />

        <label for="full_name">Полное наименование*</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="full_name_correct"></span><br />
        <input type="text" placeholder="Полное наименование" id="full_name" name="full_name" class="half <?php if (isset($errors['full_name'])) echo 'error'; ?>" value="<?= $company['full_name'] ?>"
               onblur="InputCount('full_name', 'half', 512, 'full_name_correct', 'Полное наименование не может быть такой длины', 'Необходимо заполнить полное наименование', '')"
            /><br /><br />

        <label for="key_field">ИНН организации*</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="key_field_correct"></span><br />
        <input type="text" placeholder="ИНН организации" id="key_field" name="key_field" class="one_eighth <?php if (isset($errors['key_field'])) echo 'error'; ?>" value="<?= $company['key_field'] ?>"
               onblur="InputCount('key_field', 'one_eighth', 10, 'key_field_correct', 'ИНН орагнизации не может быть такой длины', 'Необходимо заполнить ИНН организации', '')"
            /><br /><br />

        <label for="is_mfc">МФЦ?</label><br />
        <select name="is_mfc" id="is_mfc" class="one_eighth">
            <option value="0" <?php if ($company['is_mfc'] == 0) echo 'selected'; ?> >Нет</option>
            <option value="1" <?php if ($company['is_mfc'] == 1) echo 'selected'; ?>>Да</option>
        </select><br /><br /><br />

        <input type="submit" name="edit" value="Редактировать" class="button one_eighth" /><br /><br />

    </form>

<?php include ROOT . '/views/layouts/footer.php'; ?>