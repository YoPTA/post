<?php
$pagetitle = 'Добавить адрес';
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
        <label for="address_country">Страна*</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="address_country_correct"></span><br />
        <input type="text" placeholder="Страна" id="address_country" name="address_country" class="quarter <?php if (isset($errors['address_country'])) echo 'error'; ?>" value="<?= $company_address['address_country'] ?>"
               onblur="InputCount('address_country', 'quarter', 128, 'address_country_correct', 'Страна не может быть такой длины', 'Необходимо заполнить страну', '')"
            /><br /><br />

        <label for="address_region">Регион*</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="address_region_correct"></span><br />
        <input type="text" placeholder="Регион" id="address_region" name="address_region" class="quarter <?php if (isset($errors['address_region'])) echo 'error'; ?>" value="<?= $company_address['address_region'] ?>"
               onblur="InputCount('address_region', 'quarter', 256, 'address_region_correct', 'Регион не может быть такой длины', 'Необходимо заполнить регион', '')"
            /><br /><br />

        <label for="address_area">Район</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="address_area_correct"></span><br />
        <input type="text" placeholder="Район" id="address_area" name="address_area" class="quarter <?php if (isset($errors['address_area'])) echo 'error'; ?>" value="<?= $company_address['address_area'] ?>"
               onblur="InputCount('address_area', 'quarter', 256, 'address_area_correct', 'Район не может быть такой длины', 'Необходимо заполнить район', '')"
            /><br /><br />

        <label for="address_city">Город</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="address_city_correct"></span><br />
        <input type="text" placeholder="Город" id="address_city" name="address_city" class="quarter <?php if (isset($errors['address_city'])) echo 'error'; ?>" value="<?= $company_address['address_city'] ?>"
               onblur="InputCount('address_city', 'quarter', 128, 'address_city_correct', 'Город не может быть такой длины', 'Необходимо заполнить город', '')"
            /><br /><br />

        <label for="address_town">Населенный пункт</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="address_town_correct"></span><br />
        <input type="text" placeholder="Населенный пункт" id="address_town" name="address_town" class="quarter <?php if (isset($errors['address_town'])) echo 'error'; ?>" value="<?= $company_address['address_town'] ?>"
               onblur="InputCount('address_town', 'quarter', 128, 'address_town_correct', 'Населенный пункт не может быть такой длины', 'Необходимо заполнить населенный пункт', '')"
            /><br /><br />

        <label for="address_street">Улица*</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="address_street_correct"></span><br />
        <input type="text" placeholder="Улица" id="address_street" name="address_street" class="quarter <?php if (isset($errors['address_street'])) echo 'error'; ?>" value="<?= $company_address['address_street'] ?>"
               onblur="InputCount('address_street', 'quarter', 256, 'address_street_correct', 'Улица не может быть такой длины', 'Необходимо заполнить улицу', '')"
            /><br /><br />

        <label for="address_home">Дом</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="address_home_correct"></span><br />
        <input type="text" placeholder="Дом" id="address_home" name="address_home" class="one_eighth <?php if (isset($errors['address_home'])) echo 'error'; ?>" value="<?= $company_address['address_home'] ?>"
               onblur="InputCount('address_home', 'one_eighth', 32, 'address_home_correct', 'Дом не может быть такой длины', 'Необходимо заполнить дом', '')"
            />

        <span class="right_indent"></span>

        <label for="address_case">Корпус</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="address_case_correct"></span><br />
        <input type="text" placeholder="Корпус" id="address_case" name="address_case" class="one_eighth <?php if (isset($errors['address_case'])) echo 'error'; ?>" value="<?= $company_address['address_case'] ?>"
               onblur="InputCount('address_case', 'one_eighth', 16, 'address_case_correct', 'Корпус не может быть такой длины', 'Необходимо заполнить корпус', '')"
            />
        <br /><br />

        <label for="address_build">Дом</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="address_home_correct"></span><br />
        <input type="text" placeholder="Дом" id="address_home" name="address_home" class="one_eighth <?php if (isset($errors['address_home'])) echo 'error'; ?>" value="<?= $company_address['address_home'] ?>"
               onblur="InputCount('address_home', 'one_eighth', 32, 'address_home_correct', 'Дом не может быть такой длины', 'Необходимо заполнить дом', '')"
            />

        <span class="right_indent"></span>

        <label for="address_case">Корпус</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="address_case_correct"></span><br />
        <input type="text" placeholder="Корпус" id="address_case" name="address_case" class="one_eighth <?php if (isset($errors['address_case'])) echo 'error'; ?>" value="<?= $company_address['address_case'] ?>"
               onblur="InputCount('address_case', 'one_eighth', 16, 'address_case_correct', 'Корпус не может быть такой длины', 'Необходимо заполнить корпус', '')"
            />
        <br /><br />


        <br />
        <input type="submit" name="add" value="Добавить" class="button one_eighth" /><br /><br />

    </form>

<?php include ROOT . '/views/layouts/footer.php'; ?>