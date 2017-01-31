<?php
$pagetitle = 'Уведомления';
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
    <link rel="stylesheet" href="/template/css/font-awesome.css">
    <link href="/template/css/pagination.css" rel="stylesheet">
    <link href="/template/css/hide-blocks.css" rel="stylesheet">
    <title><?= $pagetitle;?></title>
</head>
<body>
<table class="body" cellpadding="0" cellspacing="0" align="center">
    <tr id="header">
        <td align="center">
            <div class="font_size_eighteen"><?= $pagetitle ?></div>
        </td>
    </tr>
    <tr id="content">
        <td style="vertical-align: text-top">
            <table class="content" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="font_size_zero">
                        <?php if (count($notifications) > 0): ?>
                        <table class="view full_width">
                            <?php
                            $i = 0;
                            foreach($notifications as $notification):
                                $i++;

                                ?>
                                <tr class="presentation">

                                    <td>
                                        <br />
                                        <div>
                                            <?php
                                            $not_datetime = $date_converter->datetimeToString($notification['created_datetime']);
                                            ?>
                                            <span class="color_grey font_size_nine">
                                                Уведомление от <?= $date_converter->datetimeToDateOrTime($not_datetime, 1) ?>
                                                в <?= $date_converter->datetimeToDateOrTime($not_datetime, 2) ?>
                                            </span>
                                        </div>
                                        <br />
                                        <div class="text_simple font_size_fourteen">
                                            <b><?= $notification['name'] ?></b>
                                        </div>
                                        <br />
                                        <div class="text_simple font_size_twelve">
                                            <?= $notification['text_message'] ?>:
                                        </div>
                                        <br />
                                        <input type="checkbox" id="hd-<?= $i ?>" class="hide"/>
                                        <label for="hd-<?= $i ?>" >Подробно</label>
                                        <div>
                                            <br />
                                            <?= $notification['detail_text_message'] ?>
                                        </div>
                                        <br /><br />
                                    </td>

                                </tr>
                            <?php endforeach; //foreach($notifications as $notification): ?>

                        </table>
                        <?php else: // if (count($notifications) > 0): ?>
                            <h3 class="font_size_twelve">Для вас нет уведомлений</h3>
                        <?php endif; // if (count($notifications) > 0): ?>

                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr id="footer">
        <td align="center">
            <div class="footer">
                <div class="la disp_inline"></div>
                <div class="ca disp_inline">
                    &copy; Государственное автономное учреждение Пензенской области "Многофункциональный центр
                    предоставления государственных и муниципальных услуг", <?= date('Y'); ?>
                </div>
                <div class="ra disp_inline"></div>
            </div>
        </td>
    </tr>
</table>

</body>
</html>