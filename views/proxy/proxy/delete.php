<?php
$pagetitle = 'Удалить доверенность';
$page_id = 'page_index';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';

?>
    <script src="/template/js/inputValidate.js"></script>

    <h2 align="center"><?= $pagetitle ?></h2>
    <div class="font_size_twelve" align="center">
        <a href="/proxy/person_view?<?= $link_to_back ?>&site_page=<?= $site_page ?>&pid=<?= $pid ?>&rid=<?= $rid ?>&user_ref=<?= $user_ref ?>&wow=<?= $wow ?>&search=<?= $search ?>&p_pid=<?= $p_pid ?>&search_date_issued=<?= $search_date_issued ?>">
            &#8592; Вернуться к доверенностям
        </a>
    </div>
    <br /><br />

    <form method="POST">
        <p class="font_size_twelve">Желаете удалить доверенность выданную: <?= $proxy['date_issued'] . ' (Орган выдачи: '. $proxy['authority_issued'] .')' ?>?</p>
        <input type="submit" name="yes" value="Да" class="button one_sixteenth" />
        <span class="right_indent"></span>
        <input type="submit" name="no" value="Нет" class="button one_sixteenth" />
    </form>

<?php include ROOT . '/views/layouts/footer.php'; ?>