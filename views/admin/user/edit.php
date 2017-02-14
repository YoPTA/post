<?php
$pagetitle = 'Редактирвоать пользователя';
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

<form method="POST" id="edit_form">
    <label for="lastname">Фамилия*</label>
    <span class="right_indent"></span>
    <span class="acorrect" id="lastname_correct"></span><br />
    <input type="text" placeholder="Фамилия" id="lastname" name="lastname" class="quarter <?php if (isset($errors['lastname'])) echo 'error'; ?>" value="<?= $user['lastname'] ?>"
           onblur="InputCount('lastname', 'quarter', 128, 'lastname_correct', 'Фамилия не может быть такой длины', 'Необходимо заполнить фамилию', '')"
        /><br /><br />

    <label for="firstname">Имя*</label>
    <span class="right_indent"></span>
    <span class="acorrect" id="firstname_correct"></span><br />
    <input type="text" placeholder="Имя" id="firstname" name="firstname" class="quarter <?php if (isset($errors['firstname'])) echo 'error'; ?>" value="<?= $user['firstname'] ?>"
           onblur="InputCount('firstname', 'quarter', 64, 'firstname_correct', 'Имя не может быть такой длины', 'Необходимо заполнить имя', '')"
        /><br /><br />

    <label for="middlename">Отчество (Необязательно)</label>
    <span class="right_indent"></span>
    <span class="acorrect" id="middlename_correct"></span><br />
    <input type="text" placeholder="Отчество (Необязательно)" id="middlename" name="middlename" class="quarter <?php if (isset($errors['middlename'])) echo 'error'; ?>" value="<?= $user['middlename'] ?>"
           onblur="InputCountCanEmpty('middlename', 'quarter', 128, 'middlename_correct', 'Отчество не может быть такой длины', '')"
        /><br /><br />

    <label for="login">Логин*</label>
    <span class="right_indent"></span>
    <span class="acorrect" id="login_correct"></span><br />
    <input type="text" placeholder="Логин" id="login" name="login" class="quarter <?php if (isset($errors['login'])) echo 'error'; ?>" value="<?= $user['login'] ?>"
           onblur="InputCount('login', 'quarter', 64, 'login_correct', 'Логин не может быть такой длины', 'Необходимо заполнить логин', '')"
        /><br /><br />

        <label for="company_address_id">Адрес организации*</label><br />
        <select class="half" id="company_address_id" name="company_address_id" data-placeholder="Не выбрано">

            <?php
            if (count($only_companies) > 0):
                foreach ($only_companies as $oc):
                    ?>
                    <optgroup label='<?= $oc['name'] ?>'>
                        <?php
                        foreach ($companies as $company):
                            if ($company['company_id'] == $oc['company_id']):
                                ?>
                                <option value="<?= $company['id'] ?>" <?php if ($user['company_address_id'] == $company['id']) echo 'selected'; ?> >
                                    <?php if ($company['is_transit'] == 1): ?>
                                        [ТРАНЗИТ]
                                    <?php endif; //if ($company['is_transit'] == 1): ?>
                                    <?= $string_utility->getAddressToView(3, $company); ?>
                                </option>
                                <?php
                            endif; // if ($company['company_id'] == $oc['company_id']):
                        endforeach; //foreach ($companies as $company):
                        ?>
                    </optgroup>
                    <?php
                endforeach; //foreach ($only_companies as $oc):
            endif; //if (count($only_companies) > 0):
            ?>

        </select><br /><br />

        <div class="half">
            <div class="quarter inline">
                <label for="role_id">Роль*</label><br />
                <select name="role_id" id="role_id" class="quarter">

                    <?php if (count($user_roles) < 1): ?>

                        <option value="0" selected>
                            Ничего нет
                        </option>

                    <?php else: //if (count($user_roles) < 1): ?>

                        <?php foreach ($user_roles as $user_role): ?>

                            <option value="<?= $user_role['id'] ?>" <?php if ($user_role['id'] == $user['role_id']) echo 'selected' ?>><?= $user_role['name'] ?></option>

                        <?php endforeach; //foreach ($user_roles as $user_role): ?>

                    <?php endif; //if (count($user_roles) < 1): ?>

                </select>
            </div>

            <span class="right_indent"></span>

            <div class="quarter inline">
                <label for="group_id">Группа*</label><br />
                <select name="group_id" id="group_id" class="quarter">

                    <?php if (count($user_groups) < 1): ?>

                        <option value="0" selected>
                            Ничего нет
                        </option>

                    <?php else: //if (count($user_groups) < 1): ?>

                        <?php foreach ($user_groups as $user_group): ?>

                            <option value="<?= $user_group['id'] ?>" <?php if ($user_group['id'] == $user['group_id']) echo 'selected' ?>><?= $user_group['name'] ?></option>

                        <?php endforeach; //foreach ($user_groups as $user_group): ?>

                    <?php endif; //if (count($user_groups) < 1): ?>

                </select>
            </div>
        </div>
        <br /><br /><br />

        <input type="submit" class="one_eighth button" name="edit" value="Редактировать" />

</form>
<br /><br /><br />
<script src="/template/css/chosen/chosen.jquery.js" type="text/javascript"></script>
<script type="text/javascript">
    $("#company_address_id").chosen({no_results_text: "Ничего не найдено", search_contains: true});
</script>
<?php if ($user['flag'] == 2): ?>
    <script>
        document.getElementById('role_id').disabled=true;
    </script>
<?php endif; // if ($user['flag'] == 2): ?>
<?php include ROOT . '/views/layouts/footer.php'; ?>