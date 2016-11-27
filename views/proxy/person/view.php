<?php
$pagetitle = 'Доверенное лицо';
$page_id = 'page_index';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';

?>

    <h2 align="center"><?= $pagetitle ?></h2>
    <div class="font_size_twelve" align="center">
        <a href="/proxy/person_index?track=<?= $track ?>&site_page=<?= $site_page ?>&date_create=<?= $date_create ?>&package_type=<?= $package_type ?>&office=<?= $office ?>&pid=<?= $pid ?>&rid=<?= $rid ?>&search=<?= $search ?>">
            &#8592; Вернуться к доверенным лицам
        </a>
    </div>
    <br /><br />

    <table class="view half">
        <tr class="presentaion">
            <td class="accent">Доверенное лицо</td>
            <td class="quarter">
                <?= $proxy_person['lastname'].' '.$proxy_person['firstname'].' ' .$proxy_person['middlename'] ?>
            </td>
        </tr>
        <tr class="presentaion">
            <td class="accent">Серия и номер паспорта</td>
            <td>
                <?= $proxy_person['document_series'].' '.$proxy_person['document_number'] ?>
            </td>
        </tr>
        <tr class="presentaion">
            <td class="accent">Дата выдачи</td>
            <td><?= $date_converter->dateToString($proxy_person['date_issued']) ?></td>
        </tr>
        <tr class="presentaion">
            <td class="accent">Место выдачи</td>
            <td><?= $proxy_person['place_name'] ?></td>
        </tr>
        <tr class="presentaion">
            <td class="accent">Код выдачи</td>
            <td><?= $proxy_person['place_code'] ?></td>
        </tr>
        <tr class="presentaion">
            <td class="accent">Номер телефона</td>
            <td><?= $proxy_person['phone_number'] ?></td>
        </tr>
    </table>
    <br />
    <div>
        <a href=""><input type="button" class="button one_eighth" value="Редактировать" /></a>
        <span class="right_indent"></span>
        <a href=""><input type="button" class="button one_eighth" value="Удалить" /></a>
    </div>

    <br />
    <form method="GET">
        <div class="inline fl">
            <input type="hidden" name="track" value="<?= $track ?>">
            <input type="hidden" name="site_page" value="<?= $site_page ?>">
            <input type="hidden" name="date_create" value="<?= $date_create ?>">
            <input type="hidden" name="package_type" value="<?= $package_type ?>">
            <input type="hidden" name="office" value="<?= $office ?>">
            <input type="hidden" name="pid" value="<?= $pid ?>">
            <input type="hidden" name="rid" value="<?= $rid ?>">
            <input type="hidden" name="search" value="<?= $search ?>" />
            <input type="hidden" name="p_pid" value="<?= $p_pid ?>" />


            <input type="text" placeholder="Дата выдачи" name="search_date" class="tcal quarter" value="<?= $search_date ?>" />
            <span class="right_indent"></span>
            <input type="submit" value="Найти" class="button one_eighth" /><span class="right_indent"></span>
        </div>
        <div class="inline fr">
            <a href="/proxy/proxy_add?track=<?= $track ?>&site_page=<?= $site_page ?>&date_create<?= $date_create ?>&package_type=<?= $package_type ?>&office=<?= $office ?>&pid=<?= $pid ?>&rid=<?= $rid ?>&search=<?= $search ?>&p_pid=<?= $p_pid ?>">
                <input type="button" value="Добавить" class="button one_eighth" />
            </a>
        </div>

    </form>
    <br /><br /><br />

    <?php
    $i = 0;
    ?>

    <table class="view full_width" cellspacing="0" cellpadding="0">
        <tr class="head" align="center">
            <td class="one_sixteenth">...</td>
            <td class="one_sixteenth">№ п/п</td>
            <td class="quarter">Номер доверенности</td>
            <td class="quarter">Дата выдачи</td>
            <td class="quarter">Дата истечения</td>
            <?php if ($is_change_proxy): ?>
            <td class="one_eighth">Действие</td>
            <?php endif; // if ($is_change_proxy): ?>
        </tr>
        <tr class="presentaion">
            <td class="one_sixteenth" align="center"><input type="radio" /></td>
            <td class="one_sixteenth" align="center">2</td>
            <td class="quarter">24</td>
            <td class="quarter">2016-10-01</td>
            <td class="quarter">2017-10-01</td>
            <?php if ($is_change_proxy): ?>
            <td class="one_eighth" align="center">
                <div class="bg_button inline">
                    <img src="/template/images/edit.png" />
                </div>
                <div class="bg_button inline">
                    <img src="/template/images/delete.png" />
                </div>
            </td>
            <?php endif; //if ($is_change_proxy): ?>
        </tr>

    </table>
    <br /><br />
    <div class="head font_size_twelve full_width" align="center">Показано: <?= $i ?> из <?= $total_proxy ?></div>
    <br /><br />
    <div id="pagination" class="pagination full_width font_size_twelve"><?= ''//$pagination->get(); ?></div>


<?php include ROOT . '/views/layouts/footer.php'; ?>