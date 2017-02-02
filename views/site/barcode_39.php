<?php
$pagetitle = 'Сопроводительный лист';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= DEFAULT_ENCODING_UPPERCASE ?>" />
    <link href="/template/css/dimensions.css" rel="stylesheet">
    <link href="/template/css/main.css" rel="stylesheet">
    <link href="/template/css/fonts.css" rel="stylesheet">
    <title><?= $pagetitle; ?></title>
</head>
<body onload="print();">

    <h2 align="center"><?= ''; //$pagetitle ?></h2>

    <?php if (isset($errors) && is_array($errors)): ?>
        <div class="border error font_size_eighteen">
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
    <div class="font_size_twenty full_width">
        <?php
        if (is_array($package_info) && $package_info != null):
        ?>
        <div class="inline fl half">

            <img src="/temp/users/<?= $user_id ?>/<?= USER_BARCODE ?>.png" alt="Не удалось загрузить штрих-код" />
            <p>Посылка: <b><?= $package_info['p_note'] ?></b></p>
            <p>Трек-номер: <b><?= $package_info['p_number'] ?></b></p>
            <p>Количество объектов посылок: <b><?= $package_objects_count ?></b></p>

        </div>

        <span class="right_indent"></span>
        <div class="inline fr half">

            <p>Дата создания: <b><?= $date_converter->datetimeToString($package_info['p_creation_datetime']) ?></b></p>
            <p>Cоздал: <b><?= $package_info['u_lastname'].' '.$package_info['u_firstname'] .' '.$package_info['u_middlename'] ?></b></p>

        </div>
        <span class="right_indent"></span>
        <div class="full_width inline">
        <div class="">
            <p>Отправитель:
                <b>
                <?php
                if ($package_info['cf_full_name'] != null)
                    echo $package_info['cf_full_name'];
                if ($package_info['caf_address_region'] != null)
                    echo ', '.$package_info['caf_address_region'];
                if ($package_info['caf_address_city'] != null)
                    echo ', '.$package_info['caf_address_city'];
                if ($package_info['caf_address_town'] != null)
                    echo ', '.$package_info['caf_address_town'];
                if ($package_info['caf_address_street'] != null)
                    echo ', '.$package_info['caf_address_street'];
                if ($package_info['caf_address_home'] != null)
                    echo ', '.$package_info['caf_address_home'];

                ?>
                </b>
                <?php
                if ($package_info['caf_address_zip'] != null):
                ?>
                (Почтовый индекс:
                <?php
                echo $package_info['caf_address_zip'].')';
                endif;// if ($package_info['caf_address_zip'] != null):
                ?>
            </p>
        </div>
        <br /><br />
        <div class="">
            <p>Получатель:
                <b>
                    <?php
                    if ($package_info['ct_full_name'] != null)
                        echo $package_info['ct_full_name'];
                    if ($package_info['cat_address_region'] != null)
                        echo ', '.$package_info['cat_address_region'];
                    if ($package_info['cat_address_city'] != null)
                        echo ', '.$package_info['cat_address_city'];
                    if ($package_info['cat_address_town'] != null)
                        echo ', '.$package_info['cat_address_town'];
                    if ($package_info['cat_address_street'] != null)
                        echo ', '.$package_info['cat_address_street'];
                    if ($package_info['cat_address_home'] != null)
                        echo ', '.$package_info['cat_address_home'];

                    ?>
                </b>
                <?php
                if ($package_info['cat_address_zip'] != null):
                    ?>
                    (Почтовый индекс:
                    <?php
                    echo $package_info['cat_address_zip'].')';
                endif;// if ($package_info['caf_address_zip'] != null):
                ?>
            </p>
        </div>

        <?php if (is_array($package_objects) && $package_objects != null && 1 > 2): ?>
        <div class="">
            <hr class="type" />
            <h3>Объекты посылки</h3>
            <?php
            $i = 0;
            foreach ($package_objects as $p_object):
                $i++;
            ?>
                <p><?= $i.'. ' . $p_object['name']; ?></p>

            <?php endforeach; //foreach ($package_objects as $p_object); ?>
        </div>
        <?php endif; //if (is_array($package_objects) && $package_objects != null): ?>

        <?php
        endif; //if (is_array($package_info) && $package_info != null):
        ?>
    </div>
    </div>

</body>
</html>

