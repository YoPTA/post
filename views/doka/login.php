<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= DEFAULT_ENCODING_UPPERCASE ?>" />
    <link rel="shortcut icon" href="/favicon.ico?00001" type="image/x-icon">
    <link rel="stylesheet" href="/template/css/chosen/chosen.css?00001">
    <link href="/template/css/input.css?00001" rel="stylesheet">
    <link href="/template/css/dimensions.css?00001" rel="stylesheet">
    <link href="/template/css/links.css?00001" rel="stylesheet">
    <link href="/template/css/main.css?00001" rel="stylesheet">
    <link href="/template/css/fonts.css?00001" rel="stylesheet">
    <link rel="stylesheet" href="/template/css/font-awesome.css?00001">
    <title><?= $pagetitle;?></title>
</head>
<body>
<table class="body" cellpadding="0" cellspacing="0" align="center">
    <tr id="header">
        <td align="center">

        </td>
    </tr>
    <tr id="content">
        <td style="vertical-align: text-top">
            <table class="content" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="font_size_zero">
                        <h2 class="" align="center">Добро пожаловать</h2>
                        <br /><br />
                        <?php if (isset($errors) && is_array($errors)): ?>
                            <div class="error font_size_twelve">
                                <div class="er"></div>
                                <h2 align="center">Ошибка ввода</h2>

                                <ul>
                                    <?php foreach ($errors as $error): ?>
                                        <li class="type_none"> - <?php echo $error; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <br /><br />
                        <?php endif; ?>
                        <?php if ($errors == false): ?>
                        <div class="full_width font_size_fourteen ">
                            <h3>Ваши данные были приняты для обработки.</h3>
                            Для дальнейшей работы с системой, необходимо подтверждение администратора.<br />
                            Попробуйте вернуться к работе с системой позже.
                        </div>
                        <br /><br />
                        <table class="view">
                            <tr class="presentation">
                                <td class="accent">Ваш логин</td>
                                <td class="quarter"><?= $doka_user['login']; ?></td>
                            </tr>
                            <tr class="presentation">
                                <td class="accent">Должность</td>
                                <td class="quarter"><?= $doka_user['workpost']; ?></td>
                            </tr>
                            <tr class="presentation">
                                <td class="accent">ФИО</td>
                                <td class="quarter">
                                    <?= $doka_user['lastname'] . ' ' .$doka_user['firstname']
                                    . ' ' . $doka_user['middlename']; ?>
                                </td>
                            </tr>
                        </table>
                        <?php endif; // if ($errors == false): ?>
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