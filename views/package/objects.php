<?php
$pagetitle = 'Объекты посылки';
$page_id = 'page_index';

?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?= DEFAULT_ENCODING_UPPERCASE ?>" />
        <link rel="icon" href="/favicon.ico">
        <link href="/template/css/input.css" rel="stylesheet">
        <link href="/template/css/dimensions.css" rel="stylesheet">
        <link href="/template/css/links.css" rel="stylesheet">
        <link href="/template/css/main.css" rel="stylesheet">
        <link href="/template/css/fonts.css" rel="stylesheet">
        <title><?= $pagetitle;?></title>
    </head>
<body>
<table class="body" cellpadding="0" cellspacing="0" align="center">
    <tr id="content">
    <td style="vertical-align: text-top">
    <table class="content" align="center" cellpadding="0" cellspacing="0">
    <tr>
    <td class="font_size_zero">
        <h2 align="center"><?= $pagetitle ?></h2>

        <div class="font_size_twenty">

        <?php
        if (is_array($p_objects) && $p_objects != null):
        ?>
            <p>Посылка: <b><?= $p_objects[0]['note'] ?></b></p>
            <p>Трек-номер: <b><?= $p_objects[0]['number'] ?></b></p>
            <table align="left" class="font_size_fourteen">
                <tr>
                    <th align="center" class="one_sixteenth">№ п/п</th>
                    <th align="center" class="quarter">Дело</th>
                </tr>
                <?php
                $i = 0;
                foreach($p_objects as $p_obj):
                    $i++;
                ?>
                    <tr class="presentation">
                    <td><?= $i; ?></td>
                    <td><?= $p_obj['name']; ?></td>
                    </tr>
                <?php endforeach;//foreach($p_objects as $p_obj): ?>
            </table>


        <?php
        else: // if (is_array($p_objects) && $p_objects != null):
        ?>
            <h3>Ничего не найдено</h3>
        <?php
        endif; // if (is_array($p_objects) && $p_objects != null):
        ?>

        </div>
    </td>
    </tr>
    </table>
    </td>
    </tr>
</table>

</body>
</html>