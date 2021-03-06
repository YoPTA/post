<?php
$pagetitle = 'Создать';
$page_id = 'page_index';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';

?>

<h2 align="center" xmlns="http://www.w3.org/1999/html"><?= $pagetitle ?></h2>
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

    <form method="POST" name="create_form" onload="selectChanged();">

    <div class="shadowed" style="padding: 5px; padding-bottom: 20px; padding-top: 20px;">
        <div class="inline half font_size_twelve" style="vertical-align: top;">
            <div class="inline"><h2>Отправитель</h2></div>
            <?php if(1 > 2): ?>
            &#160;
            <div class="inline"><span><a href="/site/select_company?c_t=f">(+ Выбрать точку назначения)</a></span></div><br />
            <?php endif; //if(1 > 2): ?>
            <div class="half border_only_bottom border_black" title='<?= $c_from['c_full_name'] ?>'><b class="font_size_fourteen">От кого:</b> <?= $c_from['c_name'] ?></div>
            <div class="half border_only_bottom border_black"><b class="font_size_fourteen">ИНН/СНИЛС:</b> <?= $c_from['c_key_field'] ?></div>
            <div class="half border_only_bottom border_black">
                <?php
                if(!is_array($c_from)) echo '&#160;';
                if($c_from['ca_country'] != null)
                    echo $c_from['ca_country'].', ';
                if($c_from['ca_region'] != null)
                    echo $c_from['ca_region'];
                if($c_from['ca_area'] != null)
                    echo ', '.$c_from['ca_area'];
                if($c_from['ca_city'] != null)
                    echo ', '.$c_from['ca_city'];
                if($c_from['ca_town'] != null)
                    echo ', '.$c_from['ca_town'];
                if($c_from['ca_street'] != null)
                    echo ', ' . $c_from['ca_street'];
                if($c_from['ca_home'] != null)
                    echo ', ' . $c_from['ca_home'];
                if($c_from['ca_case'] != null)
                    echo ', ' . $c_from['ca_case'];
                if($c_from['ca_build'] != null)
                    echo ', '.$c_from['ca_build'];
                if($c_from['ca_apartment'] != null)
                    echo ', '.$c_from['ca_apartment'];
                ?>
            </div>
            <div class="border border_black one_eighth">
                <span class="color_grey font_size_nine">Почтовый индекс</span><br />
                <?php
                if(!is_array($c_from)) echo '&#160;';
                if($c_from['ca_zip'] != null)
                    echo $c_from['ca_zip'];
                else
                    echo '&#160;';
                ?>
            </div>
        </div>
        <div class="inline half" align="right">
            <img src="/template/images/logo.png" />
        </div>

        <div class="inline half font_size_twelve" align="center" style="vertical-align: bottom;">
            <?php if($package_list == null && $package_objects == null): ?>
            <!-- <div class="box height_default quarter bg_light_red" align="left"> -->
            <?php else: ?>
            <!--<div class="box height_default quarter bg_light_green" align="left"> -->
            <?php endif; // if($package != null && $package_objects != null): ?>

            <!--</div> -->
        </div>

        <div class="inline half font_size_twelve" style="vertical-align: top;">
            <div class="inline"><h2>Получатель</h2></div>
            <?php if(1 > 2): ?>
            &#160;
            <div class="inline"><span><a href="/site/select_company?c_t=t">(+ Выбрать точку назначения)</a></span></div><br />
            <?php endif; //if(1 > 2): ?>
            <div class="half border_only_bottom border_black" title='<?= $c_to['c_full_name'] ?>'><b class="font_size_fourteen">Кому:</b> <?= $c_to['c_name'] ?></div>
            <div class="half border_only_bottom border_black"><b class="font_size_fourteen">ИНН/СНИЛС:</b> <?= $c_to['c_key_field'] ?></div>
            <div class="half border_only_bottom border_black">
                <?php
                if(!is_array($c_to)) echo '&#160;';
                if($c_to['ca_country'] != null)
                    echo $c_to['ca_country'].', ';
                if($c_to['ca_region'] != null)
                    echo $c_to['ca_region'];
                if($c_to['ca_area'] != null)
                    echo ', '.$c_to['ca_area'];
                if($c_to['ca_city'] != null)
                    echo ', '.$c_to['ca_city'];
                if($c_to['ca_town'] != null)
                    echo ', '.$c_to['ca_town'];
                if($c_to['ca_street'] != null)
                    echo ', ' . $c_to['ca_street'];
                if($c_to['ca_home'] != null)
                    echo ', ' . $c_to['ca_home'];
                if($c_to['ca_case'] != null)
                    echo ', ' . $c_to['ca_case'];
                if($c_to['ca_build'] != null)
                    echo ', '.$c_to['ca_build'];
                if($c_to['ca_apartment'] != null)
                    echo ', '.$c_to['ca_apartment'];
                ?>
            </div>
            <div class="border border_black one_eighth">
                <span class="color_grey font_size_nine">Почтовый индекс</span><br />
                <?php
                if(!is_array($c_to)) echo '&#160;';
                if($c_to['ca_zip'] != null)
                    echo $c_to['ca_zip'];
                else
                    echo '&#160;';
                ?>
            </div>
        </div>

        <br /><br /><br />
    </div>
    <?php if($to_company_id != null && $from_company_id != null): ?>
    <br />
    <h2 align="center">Способ доставки</h2>

    <div class="full_width font_size_twelve border border_black" style="vertical-align: top;" align="left">
    <label>
        <input type="radio" name="delivery_type" value="0" <?php if($delivery_type == 0 || $delivery_type == null) echo 'checked'; ?> />
        <?php if ($company_from['is_transit'] == 1): ?>
            <b class="font_size_nine text_simple">[ТРАНЗИТ]</b>
        <?php endif; //if ($company_from['is_transit'] == 1): ?>
        <span class="text_simple" title='<?= $c_from['c_full_name'] . ' ('.$c_from['c_key_field'].')'  ?>'><?= $c_from['c_name'] ?></span>
        <span class="color_grey font_size_nine">
        <?php
        echo $string_utility->getAddressToView(2, $c_from);
        ?>
        </span>
        <b class="font_size_eighteen text_simple"> &#8674; </b>
        <?php if ($company_to['is_transit'] == 1): ?>
            <b class="font_size_nine text_simple">[ТРАНЗИТ]</b>
        <?php endif; //if ($company_to['is_transit'] == 1): ?>
        <span class="text_simple" title='<?= $c_to['c_full_name'] . ' ('.$c_to['c_key_field'].')'  ?>'><?= $c_to['c_name'] ?></span>
        <span class="color_grey font_size_nine">
        <?php
        echo $string_utility->getAddressToView(2, $c_to);
        ?>
        </span>
    </label><br /><br /><br />

    <?php if (is_array($transit_points) && count($transit_points) > 0): ?>
        <?php foreach ($transit_points as $t_point): ?>
            <label>
                <input type="radio" name="delivery_type" value="<?= $t_point['ca_id'] ?>" <?php if($delivery_type == $t_point['ca_id']) echo 'checked'; ?> />
                <?php if ($company_from['is_transit'] == 1): ?>
                    <b class="font_size_nine text_simple">[ТРАНЗИТ]</b>
                <?php endif; //if ($company_from['is_transit'] == 1): ?>
                <span class="text_simple" title='<?= $c_from['c_full_name'] . ' ('.$c_from['c_key_field'].')' ?>'><?= $c_from['c_name'] ?></span>
                <span class="color_grey font_size_nine">
                <?php
                echo $string_utility->getAddressToView(2, $c_from);
                ?>
                </span>
                <b class="font_size_eighteen text_simple"> &#8674; </b>

                <span class="text_simple" title='<?= $t_point['full_name'] . ' ('.$t_point['key_field'].')'  ?>'><b class="font_size_nine">[ТРАНЗИТ]</b> <?= $t_point['name'] ?></span>
                <span class="color_grey font_size_nine">
                <?php
                echo $string_utility->getAddressToView(2, $t_point);
                ?>
                </span>

                <b class="font_size_eighteen text_simple"> &#8674; </b>
                <?php if ($company_to['is_transit'] == 1): ?>
                    <b class="font_size_nine text_simple">[ТРАНЗИТ]</b>
                <?php endif; //if ($company_to['is_transit'] == 1): ?>
                <span class="text_simple" title='<?= $c_to['c_full_name'] . ' ('.$c_to['c_key_field'].')'  ?>'><?= $c_to['c_name'] ?></span>
                <span class="color_grey font_size_nine">
                <?php
                echo $string_utility->getAddressToView(2, $c_to);
                ?>
                </span>

            </label>
            <br /><br /><br />
        <?php endforeach; // foreach ($transit_points as $t_point): ?>
    <?php  endif; //if (is_array($transit_points) && count($transit_points) > 0): ?>

    </div>

        <br /><br />
    <div class="full_width">
        <label><input type="checkbox" name="to_route_view" id="to_route_view"
                      <?php if ($to_route_view == 1) echo 'checked'; ?>
                      style="vertical-align: middle;" value="1" />После создания перейти сразу к маршруту</label>
    </div>
        <br />
    <h2>Желаете создать посылку?</h2>
    <div align="">
        <input type="submit" name="create" value="Да" class="button one_sixteenth" /><span class="right_indent"></span>
        <input type="submit" name="no" value="Нет" class="button one_sixteenth" />
    </div>
    <?php endif; // if($errors == null): ?>




</form>

<?php include ROOT . '/views/layouts/footer.php'; ?>


