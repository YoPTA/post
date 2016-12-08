<?php
$pagetitle = 'Отследить';
$page_id = 'page_index';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';

?>
<script src="/template/js/jquery.maskedinput.min.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function() {
        $.mask.definitions['~'] = "[+-]";

        $("#date_create").mask("9999-99-99");
    });
</script>

    <h2 align="center"><?= $pagetitle ?></h2>
    <form method="GET">
        <input type="search" name="track" placeholder="Введите трек-номер" class="quarter" value="<?= $track ?>" /><span class="right_indent"></span>
        <input type="hidden" name="page" value="<?= $page ?>"  />
        <input type="search" id="date_create" name="date_create" value="<?= $date_create ?>" class="one_eighth" placeholder="ГГГГ-ММ-ДД" /><span class="right_indent"></span>
        <select class="one_eighth" name="package_type">
            <option value="<?= PACKAGE_ALL ?>" <?php if ($package_type == PACKAGE_ALL) echo 'selected'; ?> >Все</option>
            <option value="<?= PACKAGE_INPUT ?>" <?php if ($package_type == PACKAGE_INPUT) echo 'selected'; ?> >Входящие</option>
            <option value="<?= PACKAGE_OUTPUT ?>" <?php if ($package_type == PACKAGE_OUTPUT) echo 'selected'; ?> >Исходящие</option>
        </select><span class="right_indent"></span>
        <select class="one_eighth" name="office">
            <option value="<?= OFFICE_NOW ?>" <?php if ($office == OFFICE_NOW) echo 'selected'; ?> >Текущий офис</option>
            <option value="<?= OFFICE_ALL ?>" <?php if ($office == OFFICE_ALL) echo 'selected'; ?> >Все офисы</option>
        </select><span class="right_indent"></span>

        <input type="submit" value="Найти" class="button one_eighth" />
    </form>
    <br /><br />



    <table class="view full_width">
        <tr class="head" align="center">
            <td class="one_sixteenth">№ п/п</td>
            <td class="one_eighth">Трек-номер</td>
            <td class="one_sixteenth">...</td>
            <td class="quarter">Откуда</td>
            <td class="quarter">Куда</td>
            <td class="one_eighth">Дата создания</td>
            <td class="one_eighth">ФИО</td>
        </tr>
        <?php
        $i = 0;
        foreach($packages as $package):
            $i++;
            $index_number++;
                ?>
        <tr class="presentation">

            <td align="center"><?= $index_number; ?></td>
            <td title="<?= $package['package_note'] ?>">
                <a href="/route/view?track=<?= $track ?>&page=<?= $page ?>&date_create=<?= $date_create ?>&package_type=<?= $package_type ?>&office=<?= $office ?>&pid=<?= $package['package_id'] ?>" title="Посмотреть маршрут">
                    <?= $package['package_number'] ?>
                </a>
            </td>
            <td>
                <a href="/package/objects?track=<?= $track ?>&page=<?= $page ?>&date_create=<?= $date_create ?>&package_type=<?= $package_type?>&office=<?= $office ?>&pid=<?= $package['package_id'] ?>" title="Посмотреть объекты посылки">
                    <img src="/template/images/view_content.png">
                </a>
            </td>
            <td>
                <div title="<?= $string_utility->getAddressToView(1, $package, 'from_'); ?>"><?= $package['from_company_name'] ?></div>
            </td>
            <td>
                <div title="<?= $string_utility->getAddressToView(1, $package, 'to_'); ?>"><?= $package['to_company_name'] ?></div>
            </td>
            <td align="center"><?= $package['package_creation_datetime'] ?></td>
            <td><?= $package['user_lastname'].' '.$package['user_firstname'].' '.$package['user_middlename'] ?></td>
        </tr>
        <?php endforeach; //foreach($packages as $package): ?>

    </table>
    <br /><br />
    <div class="head font_size_twelve full_width" align="center">Показано: <?= $i ?> из <?= $total_packages ?></div>
    <br /><br />
    <div id="pagination" class="pagination full_width font_size_twelve"><?= $pagination->get(); ?></div>

<?php include ROOT . '/views/layouts/footer.php'; ?>


