<?php
$pagetitle = 'Доверенные лица';
$page_id = 'page_index';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';

?>

    <h2 align="center"><?= $pagetitle ?></h2>
    <div class="font_size_twelve" align="center">
        <a href="/route/<?= $page_name ?>?<?= $link_to_back ?>&site_page=<?= $site_page ?>&pid=<?= $pid ?>&rid=<?= $rid ?>&wow=<?= $wow ?>">
            &#8592; Вернуться назад
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

            <input type="search" name="search" placeholder="ФИО" class="quarter" value="<?= $search ?>" /><span class="right_indent"></span>
            <input type="submit" value="Найти" class="button one_eighth" /><span class="right_indent"></span>
        </div>
        <div class="inline fr">
            <a class="for_button" href="/proxy/person_add?<?= $link_to_back ?>&site_page=<?= $site_page ?>&pid=<?= $pid ?>&rid=<?= $rid ?>&user_ref=<?= $user_ref ?>&wow=<?= $wow ?>&search=<?= $search ?>">
                <input type="button" class="button one_eighth" value="Добавить" />
            </a>
        </div>

    </form>
    <br /><br /><br />

<?php
$i = 0;
?>

    <table class="view full_width">
        <tr class="head" align="center">
            <td class="one_sixteenth">№ п/п</td>
            <td>ФИО</td>
            <?php if ($is_change_proxy) : ?>
            <td class="one_eighth">Действие</td>
            <?php endif; // if ($is_change_proxy) : ?>
        </tr>
        <?php
        if (is_array($proxy_persons) && $proxy_persons != null):
            foreach ($proxy_persons as $p_person):
                $i++;
        ?>
        <tr class="presentation">
            <td align="center"><?= $i; ?></td>
            <td>
                <?= $p_person['lastname']. ' ' . $p_person['firstname'] . ' ' . $p_person['middlename'] ?>
            </td>

            <td class="one_eighth" align="center">
                <div class="bg_button inline">
                    <a href="/proxy/person_view?<?= $link_to_back ?>&site_page=<?= $site_page ?>&pid=<?= $pid ?>&rid=<?= $rid ?>&user_ref=<?= $user_ref ?>&wow=<?= $wow ?>&search=<?= $search ?>&p_pid=<?= $p_person['id'] ?>" title="Выбрать доверенное лицо">
                        <img src="/template/images/check.png" />
                    </a>
                </div>

                <?php if ($is_change_proxy) : ?>
                <div class="bg_button inline">
                    <a href="/proxy/person_edit?<?= $link_to_back ?>&site_page=<?= $site_page ?>&pid=<?= $pid ?>&rid=<?= $rid ?>&user_ref=<?= $user_ref ?>&wow=<?= $wow ?>&search=<?= $search ?>&p_pid=<?= $p_person['id'] ?>" title="Редактировать доверенное лицо">
                        <img src="/template/images/edit.png" />
                    </a>
                </div>
                <div class="bg_button inline">
                    <a href="/proxy/person_delete?<?= $link_to_back ?>&site_page=<?= $site_page ?>&pid=<?= $pid ?>&rid=<?= $rid ?>&user_ref=<?= $user_ref ?>&wow=<?= $wow ?>&search=<?= $search ?>&p_pid=<?= $p_person['id'] ?>" title="Удалить доверенное лицо">
                        <img src="/template/images/delete.png" />
                    </a>
                </div>
                <?php endif; // if ($is_change_proxy) : ?>
            </td>

        </tr>
        <?php
            endforeach; // foreach ($proxy_persons as $p_person):
        endif; // if (is_array($proxy_persons) && $proxy_persons != null):
        ?>

    </table>
    <br /><br />
    <div class="head font_size_twelve full_width" align="center">Показано: <?= $i ?> из <?= $total_proxy_person ?></div>
    <br /><br />
    <div id="pagination" class="pagination full_width font_size_twelve"><?= ''//$pagination->get(); ?></div>


<?php include ROOT . '/views/layouts/footer.php'; ?>