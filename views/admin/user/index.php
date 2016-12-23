<?php
$pagetitle = 'Пользователи';
$page_id = 'page_admin';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';

?>

<h2 align="center"><?= $pagetitle ?></h2>

<div class="full_width" id="admin_view">

    <?php require_once (ROOT. '/views/layouts/admin_menu_panel.php') ?>
    <span class="right_indent"></span>

    <div class="three_quarter inline font_size_twelve">

        <form method="GET">
            <div class="inline fl">
                <input type="search" name="search" placeholder="ФИО/Логин" class="quarter" value="<?= $search ?>"  /><span class="right_indent"></span>
                <input type="hidden" name="page" value="<?= $page ?>"  />
                <select class="quarter" name="office" style="margin-left: -3px;">
                    <option value="0" selected>Офис</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select><span class="right_indent"></span>
                <input type="submit" value="Найти" class="button one_eighth" style="margin-left: -3px;" />
            </div>
            <span class="right_indent"></span>
            <div class="inline fr">
                <a href="/admin/user_add">
                    <button class="button one_eighth" >
                        Добавить
                    </button>
                </a>
            </div>
        </form>
        <br /><br />



        <table class="view three_quarter">
            <tr class="head" align="center">
                <td class="one_sixteenth">№ п/п</td>
                <td class="quarter">
                    ФИО<div class="font_size_nine">[логин]</div>
                </td>
                <td class="quarter">Офис</td>
                <td class="one_eighth">Действие</td>
            </tr>
            <?php
            $i = 0;
            foreach($packages as $package):
                $i++;
                $index_number++;
                ?>
                <tr class="presentation">

                    <td align="center"><?= $index_number; ?></td>
                    <td title="<?= $package['package_note'] ?>">
                        <a href="/route/view?track=<?= $track ?>&page=<?= $page ?>&date_create=<?= $date_create ?>&package_type=<?= $package_type ?>&office=<?= $office ?>&pid=<?= $package['package_id'] ?>" title="Посмотреть маршрут">
                            <?= $package['package_number'] ?>
                        </a>
                    </td>
                    <td align="center">
                        <div class="bg_button inline">
                            <a href="/package/objects?track=<?= $track ?>&page=<?= $page ?>&date_create=<?= $date_create ?>&office=<?= $office ?>&pid=<?= $package['package_id'] ?>" title="Посмотреть объекты посылки">
                                <img src="/template/images/view_content.png">
                            </a>
                        </div>
                        <span class="right_indent"></span>
                        <div class="bg_button inline" title="Посмотреть сопроводительный лист"
                             onclick="window.open('/site/barcode_39?track=<?= $track ?>&page=<?= $page ?>&date_create=<?= $date_create ?>&office=<?= $office ?>&pid=<?= $package['package_id'] ?>', 'new', 'width=1100,height=800,top=50,left=50')">
                            <img src="/template/images/barcode.png">
                        </div>
                    </td>
                    <td>
                        <div title="<?= $string_utility->getAddressToView(1, $package, 'from_'); ?>"><?= $package['from_company_name'] ?></div>
                    </td>
                    <td>
                        <div title="<?= $string_utility->getAddressToView(1, $package, 'to_'); ?>"><?= $package['to_company_name'] ?></div>
                    </td>
                    <td align="center"><?= $date_converter->dateToString($package['package_creation_datetime']) ?></td>
                    <td><?= $package['user_lastname'].' '.$package['user_firstname'].' '.$package['user_middlename'] ?></td>
                </tr>
            <?php endforeach; //foreach($packages as $package): ?>

        </table>
        <br /><br />
        <div class="head font_size_twelve three_quarter" align="center">Показано: <?= $i ?> из <?= $total_packages ?></div>
        <br /><br />
        <div id="pagination" class="pagination three_quarter font_size_twelve"><?= ''// $pagination->get(); ?></div>

    </div>

</div>


<?php include ROOT . '/views/layouts/footer.php'; ?>


