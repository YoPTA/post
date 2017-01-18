<?php
$pagetitle = 'Создание посылки';
$page_id = 'page_index';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';
?>
<h2 align="center"><?= $pagetitle ?></h2>

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

<div class="font_size_fourteen full_width">

    <div class="inline three_quarter">
        <table class="view half">
            <tr>
                <td class="one_eighth accent">Отправитель</td>
                <td class="">
                    <div class="bg_button inline">
                        <a href="/company/company_index?c_type=<?= FROM_COMPANY ?>" title="Выбрать">
                            <img src="/template/images/edit.png" />
                        </a>
                    </div>
                </td>
            </tr>

            <?php if ($company_from != null && is_array($company_from)): ?>
            <tr>
                <td colspan="2">
                    <?= $company_from['c_full_name'] ?>
                    <br />
                    <span class="color_grey font_size_twelve">
                        <?= $string_utility->getAddressToView(1, $company_from) ?>
                    </span>
                </td>
            </tr>
            <?php endif; // if ($company_from != null && is_array($company_from)): ?>
            <tr>
                <td colspan="2">
                <hr />
                </td>
            </tr>
        </table>

        <br />

        <table class="view half">
            <tr>
                <td class="one_eighth accent">Получатель</td>
                <td class="">
                    <div class="bg_button inline">
                        <a href="/company/company_index?c_type=<?= TO_COMPANY ?>" title="Выбрать">
                            <img src="/template/images/edit.png" />
                        </a>
                    </div>
                </td>
            </tr>

            <?php if ($company_to != null && is_array($company_to)): ?>
                <tr>
                    <td colspan="2">
                        <?php if ($company_to['is_transit'] == 1): ?>
                            <b class="font_size_ten" title="Транзитная точка">[ТРАНЗИТ]</b>
                        <?php endif; //if ($company_to['is_transit']): ?>
                        <?= $company_to['c_full_name'] ?>
                        <br />
                    <span class="color_grey font_size_twelve">
                        <?= $string_utility->getAddressToView(1, $company_to) ?>
                    </span>
                    </td>
                </tr>
            <?php endif; // if ($company_to != null && is_array($company_to)): ?>
            <tr>
                <td colspan="2">
                    <hr />
                </td>
            </tr>
        </table>

        <br />

        <table class="view half">
            <tr>
                <td class="one_eighth accent">Посылка</td>
                <td class="">
                    <div class="bg_button inline">
                        <a href="/package/index" title="Выбрать">
                            <img src="/template/images/edit.png" />
                        </a>
                    </div>
                </td>
            </tr>

            <?php if ($package_list != null && is_array($package_list)): ?>
            <tr class="presentation">
                <td></td>
                <td></td>
            </tr>
            <?php endif; //if ($package_list != null && is_array($package_list)): ?>

        </table>
    </div>

    <span class="right_indent negative_left_indent"></span>

    <div class="quarter inline negative_left_indent">
        <table class="view">
            <tr>
                <td class="accent one_eighth" colspan="2" align="center">
                    Заполнить форму автоматически
                </td>
            </tr>
            <tr>
                <td class="one_eighth accent">АИС "ДОКА"</td>
                <td class="one_eighth">
                    <div class="bg_button inline">
                        <a href="/doka/package" title="Из АИС &#34;ДОКА&#34;">
                            <img src="/template/images/edit.png" />
                        </a>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
    <br /><br />
<hr />
<h2>Желаете продолжить создание посылки?</h2>
<div align="">
    <form method="POST">
        <input type="submit" name="yes" value="Да" class="button one_sixteenth" /><span class="right_indent"></span>
        <input type="submit" name="no" value="Нет" class="button one_sixteenth" />
    </form>
</div>
<br /><br />

<?php include ROOT . '/views/layouts/footer.php'; ?>