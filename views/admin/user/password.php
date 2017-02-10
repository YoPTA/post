<?php
$full_name =  $name_case->q($user['lastname'].' '.$user['firstname'].' '.$user['middlename']);
$pagetitle = 'Изменить пароль ' . $full_name[1];
$page_id = 'page_admin';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';

?>
<script src="/template/js/inputValidate.js"></script>

<h2 align="center"><?= $pagetitle ?></h2>
<div class="font_size_twelve" align="center">
    <a href="/admin/user_index?<?= $get_params ?>">
        &#8592; Вернуться к пользователям
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

    <label for="password">Пароль*</label>
    <span class="right_indent"></span>
    <span class="acorrect" id="password_correct"></span><br />
    <input type="password" placeholder="Пароль" id="password" name="password" class="quarter <?php if (isset($errors['password'])) echo 'error'; ?>" value="<?= $user['password'] ?>"
           onblur="InputCount('password', 'quarter', 20, 'password_correct', 'Пароль не может быть такой длины', 'Необходимо заполнить пароль', '')"
           onkeyup="CompareFields('password', 'password_confirm', 'quarter', 'password_confirm_correct')"
        /><br /><br />

    <label for="password_confirm">Подтверждение пароля*</label>
    <span class="right_indent"></span>
    <span class="acorrect" id="password_confirm_correct"></span><br />
    <input type="password" placeholder="Подтверждение пароля" id="password_confirm" name="password_confirm" class="quarter <?php if (isset($errors['password_confirm'])) echo 'error'; ?>" value="<?= $user['password_confirm'] ?>"
           onblur="CompareFields('password', 'password_confirm', 'quarter', 'password_confirm_correct')"
           onkeyup="CompareFields('password', 'password_confirm', 'quarter', 'password_confirm_correct')"
        />


    <br /><br /><br />

    <input type="submit" class="one_eighth button" name="edit" value="Сохранить" />


</form>
<br /><br /><br />
<script src="/template/css/chosen/chosen.jquery.js" type="text/javascript"></script>
<script type="text/javascript">
    $("#company_address_id").chosen({no_results_text: "Ничего не найдено", search_contains: true});
</script>

<?php include ROOT . '/views/layouts/footer.php'; ?>