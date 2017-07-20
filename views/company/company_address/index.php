<?php
$pagetitle = 'Адреса: &#171;' . $company['name'] . '&#187;';
$page_id = 'page_index';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';
?>
    <script src="/template/js/jquery.maskedinput.min.js" type="text/javascript"></script>
    <h2 align="center"><?= $pagetitle ?></h2>
    <div class="font_size_twelve" align="center">
        <a href="/company/company_index?c_type=<?= $c_type ?>&search_value=<?= $search_param['search_value'] ?>&page=<?= $page ?>">
            &#8592; Вернуться к организациям
        </a>
    </div>
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

    <span class="right_indent"></span>
    <div class="fr inline">
        <a class="for_button" href="/company/company_address_add?c_type=<?= $c_type ?>&search_value=<?= $search_param['search_value'] ?>&page=<?= $page ?>&cid=<?= $cid ?>">
            <input type="button" class="button one_eighth" value="Добавить" />
        </a>
    </div>

    <br /><br />
    <br /><br />
    <form method="POST">
        <table class="view full_width">
            <tr class="head" align="center">
                <td class="one_sixteenth">№ п/п</td>
                <td class="three_quarter">Адрес</td>
                <td class="one_eighth">Действие</td>
            </tr>
            <?php
            $i = 0;
            $transit_count = 0;
            foreach ($company_addresses as $c_address):
                $i++;
                $index_number++;

                if ($c_address['is_transit'] == 1 && !$is_admin)
                {
                    $transit_count++;
                    continue;
                }

                ?>
                <tr class="presentation">
                    <td align="center"><?= $index_number; ?></td>
                    <td>
                        <?php if ($c_address['is_transit'] == 1): ?>
                        <b class="font_size_ten" title="Транзитная точка">[ТРАНЗИТ]</b>
                        <?php endif; // if ($c_address['is_transit'] == 1)?>
                        <?= $string_utility->getAddressToView(1, $c_address, null) ?>
                    </td>

                    <td align="center">
                        <button value="<?= $c_address['id'] ?>" class="button check" name="continue" title="Выбрать адрес" style="vertical-align: bottom">
                            <img src="/template/images/check.png" alt="Выбрать" />
                        </button>

                        <?php if ($is_change_company): ?>
                            <?php if ($c_address['flag'] != 2 || $is_admin): ?>
                                <div class="bg_button inline" title="Редактировать адрес">
                                    <a href="/company/company_address_edit?c_type=<?= $c_type ?>&search_value=<?= $search_param['search_value'] ?>&page=<?= $page ?>&cid=<?= $company['id'] ?>&caid=<?= $c_address['id'] ?>">
                                        <img src="/template/images/edit.png">
                                    </a>
                                </div>

                                <div class="bg_button inline" title="Удалить адрес">
                                    <a href="/company/company_address_delete?c_type=<?= $c_type ?>&search_value=<?= $search_param['search_value'] ?>&page=<?= $page ?>&cid=<?= $company['id'] ?>&caid=<?= $c_address['id'] ?>">
                                        <img src="/template/images/delete.png">
                                    </a>
                                </div>
                            <?php endif; //if ($c_address['flag'] != 2): ?>
                        <?php endif; // if ($is_change_company): ?>

                    </td>

                </tr>
            <?php endforeach; // foreach ($companies as $company): ?>

        </table>
    </form>
    <br /><br />
    <div class="head font_size_twelve full_width" align="center">Показано: <?= $i - $transit_count ?> из <?= $total_adresses - $transit_count ?></div>

<?php include ROOT . '/views/layouts/footer.php'; ?>