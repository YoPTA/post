<?php
$pagetitle = 'Маршрут';
$page_id = 'page_index';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';

?>
<h2 align="center"><?= $pagetitle ?></h2>
<div class="font_size_twelve" align="center">
    <a href="/site/index?<?= $link_to_back ?>&page=<?= $page; ?>" >
        &#8592; Вернуться назад
    </a>
</div>
<br /><br />
<div class="font_size_fourteen full_width">

<?php
$route_count = count($package_route);
$row_index = 0;
$point_index = -1;
$td_id = 0;
?>
    <table class="full_width" cellspacing="0" cellpadding="0">
        <?php
        $is_notfinish = 0;
        $this_row = 0;
        $package_route_id = 0;
        $local_place_id = 0;

        for ($i = $route_count - 1; $i >= 0; $i--)
        {
            if ($package_route[$i]['is_receive'] != 0 || $package_route[$i]['is_send'] != 0)
            {
                $point_index = $i;
                if ($package_route[$i]['is_send'] == 1)
                {
                    $package_route_id = $package_route[$i+1]['id'];
                    $local_place_id = $package_route[$i+1]['local_place_id'];
                }
                else
                {
                    $package_route_id = $package_route[$i]['id'];
                    $local_place_id = $package_route[$i]['local_place_id'];
                }
                break;
            }
        }

        for ($i = 0; $i < $route_count; $i++):
            $is_point_main = false;
            $is_point_separator = false;
            if ($i == $point_index)
            {
                if ($package_route[$i]['is_send'] == 0)
                {
                    $is_point_main = true;
                    $is_notfinish = 1;
                }
                else
                {
                    $is_point_separator = true;
                    $is_notfinish = 2;
                }
            }

            if ($point_index == ($route_count-1))
            {
                $is_notfinish = 0;
            }

        ?>
        <tr>
            <td class="one_sixteenth" align="right">
                <?php if ($is_point_main): ?>
                    <span class="arrow_right_red"></span>
                <?php endif; //if ($is_point_main): ?>
            </td>
            <td align="center" id="<?= $td_id ?>"  class="one_sixteenth">
                <?php if ($i == 0): ?>
                    <img src="/template/images/home.png" />
                <?php else: //if ($i == 0): ?>
                    <?php if ($i == ($route_count - 1)): ?>
                        <img src="/template/images/finish.png" />
                    <?php else: //if ($i == ($r_count - 1)): ?>
                        <img src="/template/images/mapmarker.png" />
                    <?php endif; //if ($i == ($r_count - 1)): ?>
                <?php endif; //if ($i == 0): ?>
            </td>
            <td>
                <?php
                if ($package_route[$i]['is_transit'] == 1) echo '<b class="font_size_twelve">[ТРАНЗИТ]</b>';
                ?>
                <?= $package_route[$i]['c_name'] ?>
                <span class="color_grey font_size_twelve">
                <?php
                echo $string_utility->getAddressToView(2, $package_route[$i]);
                ?>
                </span>
                <br />
                <div class="font_size_nine" style="position: absolute;">
                    <?php if ($package_route[$i]['is_receive'] == 1): ?>
                        <p class="under">
                            <span class="arrow_right" title="Получено"></span>
                            <?= $date_converter->datetimeToString($package_route[$i]['datetime_receive']) ?>,
                            <span title="От кого">
                                <?= $package_route[$i]['receive_pp_lastname'] . ' '. $package_route[$i]['receive_pp_firstname'] . ' ' . $package_route[$i]['receive_pp_middlename'] ?>
                            </span>
                            &#8594;
                            <span title="Кому">
                                <?= $package_route[$i]['receive_u_lastname'] . ' '. $package_route[$i]['receive_u_firstname'] . ' ' . $package_route[$i]['receive_u_middlename'] ?>
                            </span>
                        </p>
                    <?php endif; //if ($package_route[$i]['is_receive'] == 1): ?>

                    <?php if ($package_route[$i]['is_send'] == 1): ?>
                        <p class="under">
                            <span class="arrow_left" title="Отправлено"></span>
                            <?= $date_converter->datetimeToString($package_route[$i]['datetime_send']) ?>,
                            <span title="От кого">
                                <?= $package_route[$i]['send_u_lastname'] . ' '. $package_route[$i]['send_u_firstname'] . ' ' . $package_route[$i]['send_u_middlename'] ?>
                            </span>
                            &#8594;
                            <span title="Кому">
                                <?= $package_route[$i]['send_pp_lastname'] . ' '. $package_route[$i]['send_pp_firstname'] . ' ' . $package_route[$i]['send_pp_middlename'] ?>
                            </span>
                        </p>
                    <?php endif; //if ($package_route[$i]['is_send'] == 1): ?>
                </div>
            </td>
        </tr>
        <?php
        if ($i != ($route_count-1)):

        ?>

        <tr>
            <td align="right">
                <?php if ($is_point_separator): ?>
                    <span class="arrow_right_red"></span>
                <?php endif; //if ($is_point_separator): ?>
            </td>
            <td align="center" id="<?= $td_id ?>" class="one_sixteenth">
                <div style="border:0; border-left: 2px solid #333333; height: 40px; width: 0;"></div>
            </td>
            <td></td>
        </tr>

        <?php
            $td_id++;
        endif; //if ($i != ($r_count-1)): ?>

        <?php
            $td_id++;
        endfor; //for ($i = 0; $i < count($r_count); $i++):
        ?>


    </table>

    <br /><br />

    <?php
    if ($is_notfinish != 0):
        if ($user['local_place_id'] == $local_place_id || $is_admin):
        ?>

        <hr />
        <h2>Желаете <?php echo ($is_notfinish == 2) ? "подтвердить получение" : "отправить" ?>?</h2>
        <div>
            <?php if ($is_notfinish == 2): ?>
                <a href="/route/receive?<?= $link_to_back ?>&site_page=<?= $page ?>&pid=<?= $pid ?>&rid=<?= $package_route_id ?>">
            <?php else: //if ($is_notfinish == 2): ?>
                <a href="/route/send?<?= $link_to_back ?>&site_page=<?= $page ?>&pid=<?= $pid ?>&rid=<?= $package_route_id ?>">
            <?php endif; //if ($is_notfinish == 2): ?>
            <button class="button one_sixteenth">Да</button></a>
            <span class="right_indent"></span>
            <a href="/site/index?<?= $link_to_back ?>&page=<?= $page ?>"><button class="button one_sixteenth">Нет</button></a>
        </div>

    <?php
        endif; //if ($user['local_place_id'] == $local_place_id || $is_admin):
    endif;//if ($is_notfinish != 0):
    ?>

</div>

<?php include ROOT . '/views/layouts/footer.php'; ?>


