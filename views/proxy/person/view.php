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
    <div class="inline">
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
    </div>
    <span class="right_indent"></span>
    <div class="inline">
        <div class="bg_button inline">
            <a href="/proxy/person_edit?track=<?= $track ?>&site_page=<?= $site_page ?>&date_create=<?= $date_create ?>&package_type=<?= $package_type ?>&office=<?= $office ?>&pid=<?= $pid ?>&rid=<?= $rid ?>&search=<?= $search ?>&p_pid=<?= $p_pid ?>" title="Редактировать доверенное лицо">
                <img src="/template/images/edit.png" />
            </a>
        </div>
        <span class="right_indent"></span>
        <div class="bg_button inline">
            <a href="/proxy/person_delete?track=<?= $track ?>&site_page=<?= $site_page ?>&date_create=<?= $date_create ?>&package_type=<?= $package_type ?>&office=<?= $office ?>&pid=<?= $pid ?>&rid=<?= $rid ?>&search=<?= $search ?>&p_pid=<?= $p_pid ?>" title="Удалить доверенное лицо">
                <img src="/template/images/delete.png" />
            </a>
        </div>
    </div>

    <br /><br /><br />
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


            <input type="text" placeholder="Дата выдачи" name="search_date_issued" class="tcal quarter" value="<?= $search_date_issued ?>" />
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
    <form method="POST">
    <div class="full_width">
        <button class="continue" name="continue" id="continue" title="Продолжить">
            <div class="bg_button">
            <img src="/template/images/arrow_forward.png" alt="ВПЕРЕД" style="vertical-align: middle" />
            </div>
        </button>
    </div>
    <br />
    <table class="view full_width" cellspacing="0" cellpadding="0">
        <tr class="head" align="center">
            <td class="one_sixteenth">...</td>
            <td class="one_sixteenth">№ п/п</td>
            <td class="one_eighth">Номер доверенности</td>
            <td class="quarter">Орган выдачи</td>
            <td class="quarter">Дата выдачи</td>
            <td class="quarter">Дата истечения</td>
            <?php if ($is_change_proxy): ?>
            <td class="one_eighth">Действие</td>
            <?php endif; // if ($is_change_proxy): ?>
        </tr>
        <?php
        if (is_array($proxy_list)):
            foreach ($proxy_list as $p_list):
                $i++;
        ?>

        <tr class="presentaion">
            <td align="center"><input type="radio" name="select_proxy" value="<?= $p_list['id'] ?>"/></td>
            <td align="center"><?= $i ?></td>
            <td><?= $p_list['number'] ?></td>
            <td><?= $p_list['authority_issued'] ?></td>
            <td><?= $date_converter->dateToString($p_list['date_issued']) ?></td>
            <td><?= $date_converter->dateToString($p_list['date_expired']) ?></td>
            <?php if ($is_change_proxy): ?>
            <td align="center">
                <div class="bg_button inline">
                    <a href="/proxy/proxy_edit?track=<?= $track ?>&site_page=<?= $site_page ?>&date_create=<?= $date_create ?>&package_type=<?= $package_type ?>&office=<?= $office ?>&pid=<?= $pid ?>&rid=<?= $rid ?>&search=<?= $search ?>&p_pid=<?= $p_pid ?>&search_date_issued=<?= $search_date_issued ?>&p_id=<?= $p_list['id'] ?>" title="Редактировать доверенность">
                        <img src="/template/images/edit.png" />
                    </a>
                </div>
                <div class="bg_button inline">
                    <a href="/proxy/proxy_delete?track=<?= $track ?>&site_page=<?= $site_page ?>&date_create=<?= $date_create ?>&package_type=<?= $package_type ?>&office=<?= $office ?>&pid=<?= $pid ?>&rid=<?= $rid ?>&search=<?= $search ?>&p_pid=<?= $p_pid ?>&search_date_issued=<?= $search_date_issued ?>&p_id=<?= $p_list['id'] ?>" title="Редактировать доверенность">
                        <img src="/template/images/delete.png" />
                    </a>
                </div>
            </td>
            <?php endif; //if ($is_change_proxy): ?>
        </tr>
        <?php
        if ($is_admin):
        ?>
        <tr>
            <td colspan="5"></td>
            <td colspan="2" class="under_row">
                <div>
                    Добавлена: <?= $p_list['created_datetime'] ?><br />
                    Изменена: <?= $p_list['changed_datetime'] ?>
                </div>
            </td>
        </tr>
        <?php
                endif; // if ($is_admin):
            endforeach; // foreach ($proxy_list as $p_list):
        endif; // if (is_array($proxy_list)):
        ?>

    </table>
    </form>
    <br /><br />
    <div class="head font_size_twelve full_width" align="center">Показано: <?= $i ?> из <?= $total_proxy ?></div>
    <br /><br />
    <div id="pagination" class="pagination full_width font_size_twelve"><?= ''//$pagination->get(); ?></div>


<script>
    $("input[name='select_proxy']").click(function () {

        var button_continue = 'continue';
        document.getElementById(button_continue).style.display = 'block';

    })
</script>

<?php include ROOT . '/views/layouts/footer.php'; ?>