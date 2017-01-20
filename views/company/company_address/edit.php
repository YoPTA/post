<?php
$pagetitle = 'Редактировать адрес';
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
        <label for="address_zip">Почтовый индекс</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="address_zip_correct"></span><br />
        <input type="text" placeholder="Почтовый индекс" id="address_zip" name="address_zip" class="one_eighth <?php if (isset($errors['address_zip'])) echo 'error'; ?>" value="<?= $company_address['address_zip'] ?>"
               onblur="InputCountCanEmpty('address_zip', 'one_eighth', 8, 'address_zip_correct', 'Почтовый индекс не может быть такой длины', '')"
            /><br /><br />

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
               onblur="InputCountCanEmpty('address_area', 'quarter', 256, 'address_area_correct', 'Район не может быть такой длины', '')"
            /><br /><br />


        <label for="address_city">Город</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="address_city_correct"></span><br />
        <input type="text" placeholder="Город" id="address_city" name="address_city" class="quarter <?php if (isset($errors['address_city'])) echo 'error'; ?>" value="<?= $company_address['address_city'] ?>"
               onblur="InputCountCanEmpty('address_city', 'quarter', 128, 'address_city_correct', 'Город не может быть такой длины', '')"
            /><br /><br />

        <label for="address_town">Населенный пункт</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="address_town_correct"></span><br />
        <input type="text" placeholder="Населенный пункт" id="address_town" name="address_town" class="quarter <?php if (isset($errors['address_town'])) echo 'error'; ?>" value="<?= $company_address['address_town'] ?>"
               onblur="InputCountCanEmpty('address_town', 'quarter', 128, 'address_town_correct', 'Населенный пункт не может быть такой длины', '')"
            /><br /><br />

        <label for="address_street">Улица*</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="address_street_correct"></span><br />
        <input type="text" placeholder="Улица" id="address_street" name="address_street" class="quarter <?php if (isset($errors['address_street'])) echo 'error'; ?>" value="<?= $company_address['address_street'] ?>"
               onblur="InputCount('address_street', 'quarter', 256, 'address_street_correct', 'Улица не может быть такой длины', 'Необходимо заполнить улицу', '')"
            /><br /><br />

        <div class="quarter">
            <div class="inline one_eighth" style="vertical-align: top;">
                <label for="address_home">Дом</label>
                <span class="right_indent"></span>
                <span class="acorrect" id="address_home_correct"></span><br />
                <input type="text" placeholder="Дом" id="address_home" name="address_home" class="one_eighth <?php if (isset($errors['address_home'])) echo 'error'; ?>" value="<?= $company_address['address_home'] ?>"
                       onblur="InputCountCanEmpty('address_home', 'one_eighth', 32, 'address_home_correct', 'Дом не может быть такой длины', '')"
                    />
            </div>

            <span class="right_indent"></span>

            <div class="inline one_eighth" style="vertical-align: top;">
                <label for="address_case">Корпус</label>
                <span class="right_indent"></span>
                <span class="acorrect" id="address_case_correct"></span><br />
                <input type="text" placeholder="Корпус" id="address_case" name="address_case" class="one_eighth <?php if (isset($errors['address_case'])) echo 'error'; ?>" value="<?= $company_address['address_case'] ?>"
                       onblur="InputCountCanEmpty('address_case', 'one_eighth', 16, 'address_case_correct', 'Корпус не может быть такой длины', '')"
                    />
            </div>
        </div><br />

        <div class="quarter">
            <div class="inline one_eighth" style="vertical-align: top;">
                <label for="address_build">Строение</label>
                <span class="right_indent"></span>
                <span class="acorrect" id="address_build_correct"></span><br />
                <input type="text" placeholder="Строение" id="address_build" name="address_build" class="one_eighth <?php if (isset($errors['address_build'])) echo 'error'; ?>" value="<?= $company_address['address_build'] ?>"
                       onblur="InputCountCanEmpty('address_build', 'one_eighth', 16, 'address_build_correct', 'Строение не может быть такой длины', '')"
                    />
            </div>

            <span class="right_indent"></span>
            <div class="inline one_eighth" style="vertical-align: top;">
                <label for="address_apartment">Квартира</label>
                <span class="right_indent"></span>
                <span class="acorrect" id="address_apartment_correct"></span><br />
                <input type="text" placeholder="Квартира" id="address_apartment" name="address_apartment" class="one_eighth <?php if (isset($errors['address_apartment'])) echo 'error'; ?>" value="<?= $company_address['address_apartment'] ?>"
                       onblur="InputCountCanEmpty('address_case', 'one_eighth', 16, 'address_case_correct', 'Квартира не может быть такой длины', '')"
                    />
            </div>
        </div><br />

        <div class="quarter">
            <div class="inline one_eighth" style="vertical-align: top;">
                <label for="is_mfc">Офис МФЦ?</label><br />
                <select name="is_mfc" id="is_mfc" class="one_eighth">
                    <option value="0" <?php if ($company_address['is_mfc'] == 0) echo 'selected'; ?> >Нет</option>
                    <option value="1" <?php if ($company_address['is_mfc'] == 1) echo 'selected'; ?>>Да</option>
                </select>
            </div>

            <span class="right_indent"></span>

            <div class="inline one_eighth" style="vertical-align: top;">
                <label for="is_transit">Транзитная точка?</label><br />
                <select name="is_transit" id="is_transit" class="one_eighth">
                    <option value="0" <?php if ($company_address['is_transit'] == 0) echo 'selected'; ?> >Нет</option>
                    <option value="1" <?php if ($company_address['is_transit'] == 1) echo 'selected'; ?>>Да</option>
                </select>
            </div>
        </div><br />


        <br />
        <input type="submit" name="edit" value="Редактировать" class="button one_eighth" /><br /><br />

    </form>

<?php include ROOT . '/views/layouts/footer.php'; ?>