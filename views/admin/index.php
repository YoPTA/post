<?php
$pagetitle = 'Администраторская';
$page_id = 'page_admin';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';

?>

    <h2 align="center"><?= $pagetitle ?></h2>

    <div class="full_width" id="admin_view">

        <?php require_once (ROOT. '/views/layouts/admin_menu_panel.php') ?>
        <span class="right_indent"></span>

        <div class="three_quarter inline font_size_twelve">
            <p>Добро пожаловать в панель администратора. Можете выбрать действие в панели меню.</p>
        </div>

    </div>


<?php include ROOT . '/views/layouts/footer.php'; ?>


