<?php
$pagetitle = 'Отследить';
$page_id = 'page_index';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';

?>
<script src="/template/js/jquery.maskedinput.min.js" type="text/javascript"></script>
    <h2 align="center"><?= $pagetitle ?></h2>
    <form method="GET">
        <input type="search" name="track" placeholder="Введите трек-номер" class="quarter" value="<?= $track ?>" /><span class="right_indent"></span>
        <input type="hidden" name="page" value="<?= $page ?>"  />
        <input type="text" id="date_create" name="date_create" value="<?= $date_create ?>" class="tcal quarter" placeholder="Дата выдачи" /><span class="right_indent"></span>
        <select class="one_eighth" name="office">
            <option value="<?= OFFICE_ALL ?>" <?php if ($office == OFFICE_ALL) echo 'selected'; ?> >Все офисы</option>
            <option value="<?= OFFICE_NOW ?>" <?php if ($office == OFFICE_NOW) echo 'selected'; ?> >Текущий офис</option>
        </select><span class="right_indent"></span>

        <input type="submit" value="Найти" class="button one_eighth" />
    </form>
    <br /><br />



    <table class="view full_width">
        <tr class="head" align="center">
            <td class="one_sixteenth">№ п/п</td>
            <td class="one_sixteenth">Трек-<br />номер</td>
            <td class="quarter">Откуда</td>
            <td class="quarter">Куда</td>
            <td class="one_sixteenth">Дата создания</td>
            <td class="one_eighth">ФИО</td>
            <td class="one_eighth">Действие</td>
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
                <?= $package['package_number'] ?>
            </td>

            <td>
                <div title="<?= $string_utility->getAddressToView(1, $package, 'from_'); ?>"><?= $package['from_company_name'] ?></div>
            </td>
            <td>
                <div title="<?= $string_utility->getAddressToView(1, $package, 'to_'); ?>"><?= $package['to_company_name'] ?></div>
            </td>
            <td align="center"><?= $date_converter->dateToString($package['package_creation_datetime']) ?></td>
            <td><?= $package['user_lastname'].' '.$package['user_firstname'].' '.$package['user_middlename'] ?></td>

            <td align="center">
                <div class="bg_button inline" title="Посмотреть объекты посылки"
                    onclick="window.open('/package/objects?track=<?= $track ?>&page=<?= $page ?>&date_create=<?= $date_create ?>&office=<?= $office ?>&pid=<?= $package['package_id'] ?>', 'new', '<?= DEFAULT_WINDOW ?>')">
                        <img src="/template/images/view_content.png">
                </div>

                <div class="bg_button inline" title="Посмотреть сопроводительный лист"
                     onclick="window.open('/site/barcode_39?track=<?= $track ?>&page=<?= $page ?>&date_create=<?= $date_create ?>&office=<?= $office ?>&pid=<?= $package['package_id'] ?>', 'new', '<?= DEFAULT_WINDOW ?>')">
                    <img src="/template/images/barcode.png">
                </div>

                <div class="bg_button inline" title="Посмотреть маршрут">
                     <a href="/route/view?track=<?= $track ?>&page=<?= $page ?>&date_create=<?= $date_create ?>&package_type=<?= $package_type ?>&office=<?= $office ?>&pid=<?= $package['package_id'] ?>">
                        <img src="/template/images/location.png">
                     </a>
                </div>
            </td>

        </tr>
        <?php endforeach; //foreach($packages as $package): ?>

    </table>
    <br /><br />
    <div class="head font_size_twelve full_width" align="center">Показано: <?= $i ?> из <?= $total_packages ?></div>
    <br /><br />
    <div id="pagination" class="pagination full_width font_size_twelve"><?= $pagination->get(); ?></div>

<?php include ROOT . '/views/layouts/footer.php'; ?>


