<?php
$pagetitle = 'Добавить доверенность';
$page_id = 'page_index';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';

?>
    <script src="/template/js/inputValidate.js"></script>

    <h2 align="center"><?= $pagetitle ?></h2>
    <div class="font_size_twelve" align="center">
        <a href="/proxy/person_index?track=<?= $track ?>&site_page=<?= $site_page ?>&date_create<?= $date_create ?>&package_type=<?= $package_type ?>&office=<?= $office ?>&pid=<?= $pid ?>&rid=<?= $rid ?>&search=<?= $search ?>">
            &#8592; Вернуться назад
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
        <label for="number">Номер доверенности</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="number_correct"></span><br />
        <input type="text" placeholder="Номер доверенности" id="number" name="number" class="quarter <?php if (isset($errors['number'])) echo 'error'; ?>" value="<?= $proxy['number'] ?>"
               onblur="InputCount('number', 'quarter', 128, 'number_correct', 'Номер доверенности не может быть такой длины', 'Необходимо заполнить номер доверенности', '')"
            /><br /><br />


        <label for="date_issued">Дата выдачи</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="date_issued_correct"></span><br />
        <input type="text" placeholder="Дата выдачи" id="date_issued" name="date_issued" class="tcal quarter" value="<?= $proxy['date_issued'] ?>"

            /><br /><br />

        <label for="date_expired">Дата истечения</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="date_expired_correct"></span><br />
        <input type="text" placeholder="Дата выдачи" id="date_expired" name="date_expired" class="tcal quarter" value="<?= $proxy['date_expired'] ?>"

            /><br /><br />

        <label for="authority_issued">Орган выдачи</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="authority_issued_correct"></span><br />
        <textarea placeholder="Место выдачи" class="quarter <?php if (isset($errors['authority_issued'])) echo 'error'; ?>" id="authority_issued" name="authority_issued" rows="5"
                  onblur="InputCount('authority_issued', 'quarter', 256, 'authority_issued_correct', 'Орган выдачи не может быть такой длины', 'Необходимо заполнить орган выдачи', '')"
            ><?= $proxy_person['place_name'] ?></textarea><br /><br /><br />

        <input type="submit" name="add" value="Добавить" class="button one_eighth" /><br /><br />

    </form>

<?php include ROOT . '/views/layouts/footer.php'; ?>