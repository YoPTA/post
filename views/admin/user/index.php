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
            <div class="inline three_quarter fl">
                <input type="search" name="fio_or_login" placeholder="ФИО/Логин" class="half" value="<?= $search['fio_or_login'] ?>"  /><span class="right_indent"></span>
                <input type="hidden" name="page" value="<?= $page ?>"  />
                <input type="submit" value="Найти" class="button one_eighth" style="margin-left: -3px;" />
                <span class="right_indent"></span>
                <?php if ($admin_rights['can_create']): ?>
                <a href="/admin/user_add?<?= $get_params ?>" style="margin-left: -3px;">
                    <input type="button" value="Добавить" class="button one_eighth" />
                </a>
                <?php endif; // if ($admin_rights['can_create']): ?>
            </div>
            <br /><br /><br />

            <div class="inline three_quarter">
                <select class="half" id="office" name="office" data-placeholder="Не выбрано" onchange="this.form.submit();">

                    <?php
                    if (count($only_companies) > 0):
                        foreach ($only_companies as $oc):
                            ?>
                            <optgroup label='<?= $oc['name'] ?>'>
                                <?php
                                foreach ($companies as $company):
                                    if ($company['company_id'] == $oc['company_id']):
                                        ?>
                                        <option value="<?= $company['id'] ?>" <?php if ($search['office'] == $company['id']) echo 'selected'; ?> >
                                            <?php if ($company['is_transit'] == 1): ?>
                                                [ТРАНЗИТ]
                                            <?php endif; //if ($company['is_transit'] == 1): ?>
                                            <?= $string_utility->getAddressToView(3, $company); ?>
                                        </option>
                                        <?php
                                    endif; // if ($company['company_id'] == $oc['company_id']):
                                endforeach; //foreach ($companies as $company):
                                ?>
                            </optgroup>
                            <?php
                        endforeach; //foreach ($only_companies as $oc):
                    endif; //if (count($only_companies) > 0):
                    ?>

                </select>
            </div>

            <br />
        </form>




        <table class="view three_quarter">
            <tr class="head" align="center">
                <td class="one_sixteenth">№ п/п</td>
                <td class="quarter">
                    ФИО<div class="font_size_nine">[логин]</div>
                </td>
                <td class="quarter">Роль</td>
                <td class="one_eighth">Действие</td>
            </tr>
            <?php
            $i = 0;
            foreach($users as $u_item):
                $i++;
                $index_number++;
                ?>
                <tr class="presentation">

                    <td align="center"><?= $index_number; ?></td>
                    <td>
                        <?= $u_item['lastname'].' '.$u_item['firstname'].' '.$u_item['middlename'] ?>
                        <div class="font_size_nine">[<?= $u_item['login'] ?>]</div>
                    </td>
                    <td>
                        <?= $u_item['role_name'] ?>
                    </td>
                    <td>
                        <?php if ($admin_rights['can_edit']): ?>
                            <div class="bg_button inline">
                                <a href="/admin/user_edit?<?= $get_params ?>" title="Редактировать пользователя">
                                    <img src="/template/images/edit.png" />
                                </a>
                            </div>
                        <?php endif //if ($admin_rights['can_edit']): ?>

                        <?php if ($admin_rights['can_delete']): ?>

                        <?php endif //if ($admin_rights['can_delete']): ?>
                    </td>
                </tr>
            <?php endforeach; //foreach($packages as $package): ?>

        </table>
        <br /><br />
        <div class="head font_size_twelve three_quarter" align="center">Показано: <?= $i ?> из <?= $total ?></div>
        <br /><br />
        <div id="pagination" class="pagination three_quarter font_size_twelve"><?= $pagination->get(); ?></div>

    </div>

</div>

<script src="/template/css/chosen/chosen.jquery.js" type="text/javascript"></script>
<script type="text/javascript">
    $("#office").chosen({no_results_text: "Ничего не найдено", search_contains: true});
</script>


<?php include ROOT . '/views/layouts/footer.php'; ?>


