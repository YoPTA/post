<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= DEFAULT_ENCODING_UPPERCASE ?>" />
    <link href="/template/css/input.css" rel="stylesheet">
    <link href="/template/css/dimensions.css" rel="stylesheet">
    <link href="/template/css/links.css" rel="stylesheet">
    <link href="/template/css/main.css" rel="stylesheet">
    <link href="/template/css/fonts.css" rel="stylesheet">
    <link href="/template/css/pagination.css" rel="stylesheet">
    <link href="/template/css/tcal.css" rel="stylesheet">
    <script src="/template/js/jquery-1.8.3.min.js"></script>
    <script src="/template/js/tcal.js"></script>
    <title><?= $pagetitle;?></title>
</head>
<body>
<table class="body" cellpadding="0" cellspacing="0" align="center">
    <tr id="header">
        <td align="center">
            <?php if($user_id > 0): ?>
            <div class="menu full_width" align="left" id="menu">
                <div class="fl">
                    <ul class="parent">
                        <li class="menu_item">
                            <a href="/site/index">
                                <div id="page_index" class="one_eighth right_indent header_btn">
                                    <span style="font-size: 10px;">&#9993;</span>&#160;Посылка
                                    <span class="fr" style="margin-right: 10px; margin-left: -20px; font-size: 12px;">&#9660;</span></div>
                                <ul class="children">
                                    <li><a href="/site/index"><div class="one_eighth header_btn"><span style="font-size: 10px;">&#9993;</span>&#160;Отследить</div></a></li>
                                    <?php if($is_create): ?>
                                    <li><a href="/site/create"><div class="one_eighth header_btn"><span>&#9998;</span>&#160;Создать</div></a></li>
                                    <?php endif; ?>
                                </ul>
                            </a>
                        </li>

                        <!-- <li class="menu_item"><a href="/profile/index"><div id="page_profile" class="one_eighth right_indent header_btn"><span>&#9786;</span>&#160;Профиль</div></a></li> -->

                    </ul>
                </div>
                <div class="fr">
                    <ul class="parent">
                        <?php if(isset($is_admin) && $is_admin): ?>
                        <li class="menu_item">
                            <a href="/admin/index">
                                <div id="page_admin" class="one_eighth right_indent header_btn">
                                    <span style="font-size: 10px;">&#9812;</span>&#160;Админка
                                    <span class="fr" style="margin-right: 10px; margin-left: -20px; font-size: 12px;">&#9660;</span>
                                </div>
                                <ul class="children">
                                    <li><a href=""><div class="one_eighth header_btn">Ссыль 1</div></a></li>
                                    <li><a href=""><div class="one_eighth header_btn">Ссыль 2</div></a></li>
                                </ul>
                            </a>
                        </li>
                        <?php endif; ?>
                        <li class="menu_item"><a href="/site/logout"><div id="page_logout" class="one_eighth header_btn "><span style="font-size: 11px;">&#8674;</span>&#160;Выход</div></a></li>
                    </ul>

                </div>
            </div>
            <?php else:?>
            <div class="welcome" align="center">
                <h3>Пожалуйста авторизуйтесь</h3>
            </div>
            <?php endif; ?>
        </td>
    </tr>
    <tr id="content">
        <td style="vertical-align: text-top">
            <table class="content" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="font_size_zero">