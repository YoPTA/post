<?php
$pagetitle = 'Объекты посылки';
$page_id = 'page_index';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';

?>

<h2 align="center"><?= $pagetitle ?></h2>
<div class="font_size_twenty">

<?php
if (is_array($p_objects) && $p_objects != null):
?>
    <p>Посылка: <b><?= $p_objects[0]['note'] ?></b></p>
    <p>Трек-номер: <b><?= $p_objects[0]['number'] ?></b></p>
    <ol class="undreline">
    <?php
    foreach ($p_objects as $p_object):
    ?>
    <li><?= $p_object['name'] ?></li>
<?php
    endforeach; // foreach ($p_objects as $p_object):
?>
    </ol>

<?php
else: // if (is_array($p_objects) && $p_objects != null):
?>
    <h3>Ничего не найдено</h3>
<?php
endif; // if (is_array($p_objects) && $p_objects != null):
?>

</div>
<?php include ROOT . '/views/layouts/footer.php'; ?>