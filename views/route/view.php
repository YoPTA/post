<?php
$pagetitle = 'Посылка';
$page_id = 'page_index';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';

?>
<h2 align="center"><?= $pagetitle ?></h2>
<div class="font_size_twelve" align="center">
    <a href="/site/index?track=<?= $track ?>&page=<?= $page ?>&date_create=<?= $date_create ?>&package_type=<?= $package_type ?>&office=<?= $office ?>">
        &#8592; Вернуться назад
    </a>
</div>
<br /><br />
<div class="font_size_fourteen full_width">

    <table class="full_width" cellspacing="0" cellpadding="0">
    <?php
    if (is_array($package_route) && count($package_route) > 1):
        // Получаем общее кол-во точек
        $route_count = count($package_route);

        $route_serial_number = 0; // Порядковый номер маршрута

        for ($i = 0; $i < count($package_route); $i++):
            $route_serial_number++;
    ?>

        <?php if ($route_serial_number == 1): ?>
        <tr>
            <td class="one_sixteenth" align="center">
                <?php if ($package_route[$i]['is_send'] == 0): ?>
                    <div class="bg_button inline">
                    <a href="/route/send?track=<?= $track ?>&site_page=<?= $page ?>&date_create=<?= $date_create ?>&package_type=<?= $package_type ?>&office=<?= $office ?>&pid=<?= $pid ?>&rid=<?= $package_route[$i]['id'] ?>"
                       title="Отправить">
                        <img src="/template/images/paper-plane.png" />
                    </a>
                    </div>
                <?php endif; // if ($package_route[$i]['is_send'] == 0): ?>
            </td>
            <td class="one_sixteenth <?php
            if ($package_route[$i]['is_receive']) echo ' bg_light_green ';
            ?>" align="center"><img src="/template/images/home.png" /></td>
            <td ><?= $string_utility->getAddressToView(1, $package_route[$i]) ?></td>
        </tr>
        <tr>
            <td></td>
            <td class="one_sixteenth <?php
            if ($package_route[$i]['is_receive']) echo ' bg_light_green ';
            ?>
            " align="center"><div style="border:0; border-left: 2px solid #333333; height: 40px; width: 0;"></div></td>
            <td></td>
        </tr>
        <?php elseif ($route_serial_number == $route_count): //if ($route_serial_number == 1): ?>
        <tr>
            <td class="one_sixteenth" align="center">
            <?php if ($package_route[$i - 1]['is_send'] == 1): ?>
                <?php if ($package_route[$i]['is_receive'] == 0): ?>
                <div class="bg_button inline">
                    <a href="/route/send?track=<?= $track ?>&site_page=<?= $page ?>&date_create=<?= $date_create ?>&package_type=<?= $package_type ?>&office=<?= $office ?>&pid=<?= $pid ?>&rid=<?= $package_route[$i]['id'] ?>"
                       title="Подтвердить получение">
                        <img src="/template/images/mail-receive.png" />
                    </a>
                </div>
                <?php endif; // if ($package_route[$i]['is_receive'] == 0): ?>
            <?php endif; // if ($package_route[$i - 1]['is_send'] == 1): ?>
            </td>
            <td class="one_sixteenth <?php
            if ($package_route[$i]['is_receive']) echo ' bg_light_green ';
            ?>" align="center"><img src="/template/images/finish.png" /></td>
            <td ><?= $string_utility->getAddressToView(1, $package_route[$i]) ?></td>
        </tr>
        <?php else: //if ($route_serial_number == 1): ?>
        <tr>
            <td class="one_sixteenth" align="center">
                <?php if ($package_route[$i - 1]['is_send'] == 1): ?>
                    <?php if ($package_route[$i]['is_receive'] == 0): ?>
                     <div class="bg_button inline">
                        <a title="Подтвердить получение">
                            <img src="/template/images/mail-receive.png" />
                        </a>
                     </div>
                    <?php endif; // if ($package_route[$i]['is_receive'] == 0): ?>

                    <?php if ($package_route[$i]['is_receive'] == 1 && $package_route[$i]['is_send'] == 0): ?>
                    <div class="bg_button inline">
                        <a title="Подтвердить получение">
                            <img src="/template/images/paper-plane.png" />
                        </a>
                    </div>
                    <?php endif; // if ($package_route[$i]['is_receive'] == 1 && $package_route[$i]['is_send'] == 0): ?>
                <?php endif; // if ($package_route[$i - 1]['is_send'] == 1): ?>

            </td>
            <td class="one_sixteenth <?php
            if ($package_route[$i]['is_receive']) echo ' bg_light_green ';
            ?> " align="center"><img src="/template/images/mapmarker.png" /></td>
            <td ><?=$string_utility->getAddressToView(1, $package_route[$i]) ?></td>
        </tr>
        <tr>
            <td></td>
            <td class="one_sixteenth
            <?php
            if ($package_route[$i]['is_receive']) echo ' bg_light_green ';
            ?>
            " align="center"><div style="border:0; border-left: 2px solid #333333; height: 40px; width: 0;"></div></td>
            <td></td>
        </tr>
        <?php endif; //if ($route_serial_number == 1): ?>

        <?php endfor; //for ($i = 0; $i < count($package_route); $i++): ?>
    <?php endif; //if (is_array($package_route) && count($package_route) > 1): ?>
    </table>
</div>

<?php include ROOT . '/views/layouts/footer.php'; ?>


