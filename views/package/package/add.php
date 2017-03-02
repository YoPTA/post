<?php
$pagetitle = 'Посылка';
$page_id = 'page_index';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';

?>
    <script src="/template/js/inputValidate.js"></script>

    <h2 align="center"><?= $pagetitle ?></h2>
    <div class="font_size_twelve" align="center">
        <a href="/site/choose">
            &#8592; Вернуться к созданию посылки
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
        <label for="note">Название*</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="note_correct"></span><br />
        <input type="text" placeholder="Название" id="note" name="note" class="half <?php if (isset($errors['note'])) echo 'error'; ?>" value="<?= $package['note'] ?>"
               onblur="InputCount('note', 'half', 128, 'note_correct', 'Название не может быть такой длины', 'Необходимо заполнить название', '')"
            /><br /><br />

        <label for="comment">Комментарий</label>
        <span class="right_indent"></span>
        <span class="acorrect" id="comment_correct"></span><br />
        <textarea placeholder="Комментарий" rows="7" name="comment" id="comment" class="half <?php if (isset($errors['comment'])) echo 'error'; ?>"
                  onblur="InputCountCanEmpty('comment', 'half', 512, 'comment_correct', 'Комментарий не может быть такой длины', '')"
            ><?= $package['comment'] ?></textarea>
        <br /><br />

        <input type="submit" name="add" value="Сохранить" class="button one_eighth" /><br /><br />

    </form>

<?php include ROOT . '/views/layouts/footer.php'; ?>