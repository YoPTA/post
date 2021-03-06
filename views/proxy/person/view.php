<?php
$full_name =  $name_case->q($proxy_person['lastname'].' '.$proxy_person['firstname'].' '.$proxy_person['middlename']);
$pagetitle = 'Доверенности ' . $full_name[1];
$page_id = 'page_index';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';

?>

    <h2 align="center"><?= $pagetitle ?></h2>
    <div class="font_size_twelve" align="center">
        <a href="/proxy/person_index?<?= $link_to_back ?>&site_page=<?= $site_page ?>&pid=<?= $pid ?>&rid=<?= $rid ?>&user_ref=<?= $user_ref ?>&wow=<?= $wow ?>&search=<?= $search ?>">
            &#8592; Вернуться к доверенным лицам
        </a>
    </div>
    <br /><br />
    <form method="GET">
        <div class="inline fl">


            <input type="hidden" name="search_type" value="<?= $index_search['search_type'] ?>">
            <input type="hidden" name="track" value="<?= $index_search['track'] ?>">

            <?php if ($index_search['search_type'] == SEARCH_TYPE_SPECIAL): ?>

                <input type="hidden" name="package_type" value="<?= $index_search['package_type'] ?>">
                <input type="hidden" name="active_flag" value="<?= $index_search['active_flag'] ?>">
                <input type="hidden" name="date_create_begin" value="<?= $index_search['date_create_begin'] ?>">
                <input type="hidden" name="date_create_end" value="<?= $index_search['date_create_end'] ?>">
                <input type="hidden" name="search_relatively" value="<?= $index_search['search_relatively'] ?>">
                <input type="hidden" name="search_package_state" value="<?= $index_search['search_package_state'] ?>">
                <input type="hidden" name="search_place_from_or_to" value="<?= $index_search['search_place_from_or_to'] ?>">
                <input type="hidden" name="search_place_to_or_from" value="<?= $index_search['search_place_to_or_from'] ?>">
                <input type="hidden" name="from_or_to" value="<?= $index_search['from_or_to'] ?>">
                <input type="hidden" name="to_or_from" value="<?= $index_search['to_or_from'] ?>">

            <?php endif;  ?>

            <input type="hidden" name="site_page" value="<?= $site_page ?>">
            <input type="hidden" name="pid" value="<?= $pid ?>">
            <input type="hidden" name="rid" value="<?= $rid ?>">
            <input type="hidden" name="wow" value="<?= $wow ?>">
            <input type="hidden" name="user_ref" value="<?= $user_ref ?>">
            <input type="hidden" name="search" value="<?= $search ?>" />
            <input type="hidden" name="p_pid" value="<?= $p_pid ?>" />


            <input type="text" placeholder="Дата выдачи" name="search_date_issued" class="tcal quarter" value="<?= $search_date_issued ?>" />
            <span class="right_indent"></span>
            <input type="submit" value="Найти" class="button one_eighth" /><span class="right_indent"></span>
        </div>
        <div class="inline fr">
            <a class="for_button" href="/proxy/proxy_add?<?= $link_to_back ?>&site_page=<?= $site_page ?>&pid=<?= $pid ?>&rid=<?= $rid ?>&user_ref=<?= $user_ref ?>&wow=<?= $wow ?>&search=<?= $search ?>&p_pid=<?= $p_pid ?>">
                <input type="button" class="button one_eighth" value="Добавить" />
            </a>
        </div>

    </form>
    <br /><br /><br />

    <form method="POST">
    <br />
    <table class="view full_width" cellspacing="0" cellpadding="0">
        <tr class="head" align="center">
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
        $i = 0;
        if (is_array($proxy_list)):
            foreach ($proxy_list as $p_list):
                $i++;
        ?>

        <tr class="presentation">
            <td align="center"><?= $i ?></td>
            <td><?= $p_list['number'] ?></td>
            <td><?= $p_list['authority_issued'] ?></td>
            <td><?= $date_converter->dateToString($p_list['date_issued']) ?></td>
            <td><?= $date_converter->dateToString($p_list['date_expired']) ?></td>

            <td align="center">
                <button value="<?= $p_list['id'] ?>" class="button check" name="continue" title="Выбрать доверенность" style="vertical-align: bottom">
                    <img src="/template/images/check.png" alt="Выбрать" />
                </button>
                <?php if ($is_change_proxy): ?>
                <div class="bg_button inline">
                    <a href="/proxy/proxy_edit?<?= $link_to_back ?>&site_page=<?= $site_page ?>&pid=<?= $pid ?>&rid=<?= $rid ?>&user_ref=<?= $user_ref ?>&wow=<?= $wow ?>&search=<?= $search ?>&p_pid=<?= $p_pid ?>&search_date_issued=<?= $search_date_issued ?>&p_id=<?= $p_list['id'] ?>" title="Редактировать доверенность">
                        <img src="/template/images/edit.png" />
                    </a>
                </div>
                <div class="bg_button inline">
                    <a href="/proxy/proxy_delete?<?= $link_to_back ?>&site_page=<?= $site_page ?>&pid=<?= $pid ?>&rid=<?= $rid ?>&user_ref=<?= $user_ref ?>&wow=<?= $wow ?>&search=<?= $search ?>&p_pid=<?= $p_pid ?>&search_date_issued=<?= $search_date_issued ?>&p_id=<?= $p_list['id'] ?>" title="Редактировать доверенность">
                        <img src="/template/images/delete.png" />
                    </a>
                </div>
                <?php endif; //if ($is_change_proxy): ?>
            </td>
        </tr>
        <?php
        if ($is_admin):
        ?>
        <tr>
            <td colspan="4"></td>
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
    $("input[name='selected_proxy']").click(function () {

        var button_continue = 'continue';
        document.getElementById(button_continue).style.display = 'block';

    })
</script>

<?php include ROOT . '/views/layouts/footer.php'; ?>