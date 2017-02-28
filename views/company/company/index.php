<?php
$pagetitle = 'Организации';
$page_id = 'page_index';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';
?>
    <script src="/template/js/jquery.maskedinput.min.js" type="text/javascript"></script>
    <h2 align="center"><?= $pagetitle ?></h2>
    <div class="font_size_twelve" align="center">
        <a href="/site/choose">
            &#8592; Вернуться назад
        </a>
    </div>
    <br /><br />
    <form method="GET">
        <div class="fl inline">
            <input type="hidden" name="c_type" value="<?= $c_type ?>"  />
            <input type="hidden" name="page" value="<?= $page ?>"  />
            <input type="search" name="search_value" placeholder="Полное или короткое наиманование/ИНН организации" class="half" value="<?= $search_param['search_value'] ?>" /><span class="right_indent"></span>

            <input type="submit" value="Найти" class="button one_eighth" />
        </div>
        <span class="right_indent"></span>
        <div class="fr inline">
            <a class="for_button" href="/company/company_add?c_type=<?= $c_type ?>&search_value=<?= $search_param['search_value'] ?>&page=<?= $page ?>">
                <input type="button" class="button one_eighth" value="Добавить" />
            </a>
        </div>
    </form>
    <br /><br />
    <br />

    <table class="view full_width">
        <tr class="head" align="center">
            <td class="one_sixteenth">№ п/п</td>
            <td class="quarter">Наименование</td>
            <td class="quarter">Полное наименование</td>
            <td class="one_eighth">ИНН</td>
            <td class="one_eighth">Действие</td>
        </tr>
        <?php
        $i = 0;
        foreach ($companies as $company):
            $i++;
            $index_number++;
            ?>
            <tr class="presentation">

                <td align="center"><?= $index_number; ?></td>
                <td>
                    <?php if ($company['is_mfc'] == 1): ?>
                        <img src="/template/images/logo-color.PNG" alt="МФЦ" />
                    <?php endif;// if ($company['is_mfc'] == 1): ?>
                    <?= $company['name'] ?>
                </td>

                <td>
                    <?= $company['full_name'] ?>
                </td>
                <td>
                    <?= $company['key_field'] ?>
                </td>

                <td align="center">
                    <div class="bg_button inline" title="Выбрать организацию">
                        <a href="/company/company_address_index?c_type=<?= $c_type ?>&search_value=<?= $search_param['search_value'] ?>&page=<?= $page ?>&cid=<?= $company['id'] ?>">
                            <img src="/template/images/check.png" />
                        </a>
                    </div>

                    <?php if ($is_change_company): ?>
                    <div class="bg_button inline" title="Редактировать организацию">
                         <a href="/company/company_edit?c_type=<?= $c_type ?>&search_value=<?= $search_param['search_value'] ?>&page=<?= $page ?>&cid=<?= $company['id'] ?>">
                             <img src="/template/images/edit.png">
                         </a>
                    </div>

                    <div class="bg_button inline" title="Удалить организацию">
                        <a href="/company/company_delete?c_type=<?= $c_type ?>&search_value=<?= $search_param['search_value'] ?>&page=<?= $page ?>&cid=<?= $company['id'] ?>">
                            <img src="/template/images/delete.png">
                        </a>
                    </div>

                    <?php if ($company['is_mfc'] == 1): ?>
                        <?php if ($is_admin): ?>
                        <div class="bg_button inline" title="Ip-адрес организации">
                            <a href="/company/company_ip_address?c_type=<?= $c_type ?>&search_value=<?= $search_param['search_value'] ?>&page=<?= $page ?>&cid=<?= $company['id'] ?>">
                                <img src="/template/images/ip.png">
                            </a>
                        </div>
                        <?php endif; //if ($is_admin): ?>
                    <?php endif; //if ($company['is_mfc'] == 1): ?>

                    <?php endif; // if ($is_change_company): ?>

                </td>

            </tr>
        <?php endforeach; // foreach ($companies as $company): ?>

    </table>
    <br /><br />
    <div class="head font_size_twelve full_width" align="center">Показано: <?= $i ?> из <?= $total_companies ?></div>
    <br /><br />
    <div id="pagination" class="pagination full_width font_size_twelve"><?= $pagination->get(); ?></div>

<?php include ROOT . '/views/layouts/footer.php'; ?>