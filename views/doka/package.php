<?php
$pagetitle = 'Посылка';
$page_id = 'page_index';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';

?>

<h2 align="center"><?= $pagetitle ?></h2>
<div class="full_width font_size_twelve" align="center">
    <a href="/site/create" align="center">&larr; Вернуться назад</a>
</div>
<br/>
<form method="POST">
    <input type="search" name="package" placeholder="Введите номер ведомости" class="quarter" value="<?= $package ?>" /><span class="right_indent"></span>

    <input type="submit" value="Найти" name="search" class="button one_eighth" /><br/><br/>

    <?php if (isset($errors) && is_array($errors)): ?>
        <div class="border error font_size_twelve">
            <div class="er"></div>
            <h2 align="center">Ошибка</h2>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li class="type_none"> - <?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <br /><br />
    <?php endif; ?>

    <?php if($from_company != null && $to_company != null && $p_object != null && !is_array($errors)): ?>
    <div class="full_width font_size_twelve">
        <div class="half  inline"  style="vertical-align: top;">
            <table align="left" class="font_size_fourteen">
                <tr>
                    <th align="center" class="one_sixteenth">№ п/п</th>
                    <th align="center" class="quarter">Дело</th>
                </tr>
                <?php
                $i = 0;
                foreach($p_object as $p_obj):
                    $i++;
                    if($i%2==0):
                        ?>
                        <tr class="bgGray_one">
                    <?php else: ?>
                        <tr class="bgGray_two">
                    <?php endif; //if($i%2==0): ?>
                    <td><?= $i; ?></td>
                    <td><?= $p_obj; ?></td>
                    </tr>
                <?php endforeach;//foreach($p_object as $p_obj): ?>
            </table>
        </div>
        <div class="half inline">
            <h2 class="font_size_fourteen">Отправитель</h2>
            <div class="half"><?= $from_company['full_name'] ?></div>
            <hr>
            <h2 class="font_size_fourteen">Получатель</h2>
            <div class="half"><?= $to_company['full_name'] ?></div>
        </div>
    </div>
        <br />
        <br />
        <hr>
        <h2>Продолжить?</h2>
        <input type="submit" name="yes" value="Да" class="one_sixteenth button" /><span class="right_indent"></span>
        <input type="submit" name="no" value="Нет" class="one_sixteenth button" />
    <?php endif; //if($from_company != null && $to_company != null && $p_object != null): ?>



</form>

<?php include ROOT . '/views/layouts/footer.php'; ?>


