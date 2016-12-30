<?php
$pagetitle = 'Маршрут';
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

<?php
$route_count = count($package_route);
$row_index = 0;
$point_index = -1;
?>
    <table class="full_width" cellspacing="0" cellpadding="0">
        <?php
        $is_notfinish = 0;
        $this_row = 0;

        for ($i = $route_count - 1; $i >= 0; $i--)
        {
            if ($package_route[$i]['is_receive'] != 0 || $package_route[$i]['is_send'] != 0)
            {
                $point_index = $i;
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

            if ($point_index == 0)
            {
                if ($is_point_separator)
                {
                    $row_index++;
                }
            }

            if ($point_index == 1)
            {
                if ($is_point_main)
                {
                    $row_index = $point_index+1;
                }
                if ($is_point_separator)
                {
                    $row_index = $point_index+2;
                }
            }

            if ($point_index > 1)
            {
                if ($is_point_main)
                {
                    $row_index = ($point_index * 2);
                }
                if ($is_point_separator)
                {
                    $row_index = ($point_index * 2) + 1;
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
                    <img src="/template/images/pointer.png" />
                <?php endif; //if ($is_point_main): ?>
            </td>
            <td class="one_sixteenth" align="center">
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

            </td>
        </tr>
        <?php
        if ($i != ($route_count-1)):
        ?>

        <tr>
            <td align="right">
                <?php if ($is_point_separator): ?>
                    <img src="/template/images/pointer.png" />
                <?php endif; //if ($is_point_separator): ?>
            </td>
            <td align="center">
                <div style="border:0; border-left: 2px solid #333333; height: 40px; width: 0;"></div>
            </td>
            <td></td>
        </tr>

        <?php endif; //if ($i != ($r_count-1)): ?>

        <?php
        endfor; //for ($i = 0; $i < count($r_count); $i++):
        ?>


    </table>
    <?php
    echo 'row index: '.$row_index.'<br />';
    ?>
    <br /><br />
    <hr />

    <?php if ($is_notfinish != 0): ?>
        <h2>Желаете <?php echo ($is_notfinish == 2) ? "подтвердить получение" : "отправить" ?>?</h2>
        <?php

        ?>

    <?php endif;//if ($is_notfinish != 0): ?>

</div>

<?php include ROOT . '/views/layouts/footer.php'; ?>


