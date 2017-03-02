<?php
$pagetitle = 'Отследить';
$page_id = 'page_index';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';

?>
<script src="/template/js/jquery.maskedinput.min.js" type="text/javascript"></script>
    <h2 align="center"><?= $pagetitle ?></h2>
    <form method="GET">
        <div class="full_width inline" style="vertical-align: bottom;">
                <div class="inline">
                    <label for="search_type">Режим поиска</label><br />
                    <select class="quarter" id="search_type" name="search_type" onchange="this.form.submit();">
                        <option value="<?= SEARCH_TYPE_COMMON ?>" <?php if ($search['search_type'] == SEARCH_TYPE_COMMON) echo 'selected'; ?> >Обычный</option>
                        <option value="<?= SEARCH_TYPE_SPECIAL ?>" <?php if ($search['search_type'] == SEARCH_TYPE_SPECIAL) echo 'selected'; ?> >Расширенный</option>
                    </select><span class="right_indent"></span>
                </div>

                <input type="hidden" name="page" value="<?= $page ?>"  />


                <div class="inline">
                    <label for="track">Поиск</label><br />
                    <input type="search" id="track" name="track" placeholder="Введите трек-номер или название" class="quarter" value="<?= $search['track'] ?>" /><span class="right_indent"></span>
                </div>

                <?php if ($search['search_type'] == SEARCH_TYPE_SPECIAL): ?>

                <div class="inline">
                    <label for="package_type">Тип</label><br />
                    <select class="quarter" id="" name="package_type" onchange="this.form.submit();">
                        <option value="<?= PACKAGE_INPUT ?>" <?php if ($search['package_type'] == PACKAGE_INPUT) echo 'selected'; ?> >Входящие</option>
                        <option value="<?= PACKAGE_OUTPUT ?>" <?php if ($search['package_type'] == PACKAGE_OUTPUT) echo 'selected'; ?> >Исходящие</option>
                    </select>
                </div><span class="right_indent"></span>

                <div class="inline">
                    <label for="active_flag">Статус</label><br />
                    <select class="quarter" id="" name="active_flag" onchange="this.form.submit();">
                        <option value="<?= ACTIVE_FLAG_ACTIVE ?>" <?php if ($search['active_flag'] == PACKAGE_INPUT) echo 'selected'; ?> >Активные</option>
                        <option value="<?= ACTIVE_FLAG_ARCHIVE ?>" <?php if ($search['active_flag'] == PACKAGE_OUTPUT) echo 'selected'; ?> >Архив</option>
                    </select>
                </div><span class="right_indent"></span>


                    <br />
                    <br />
                <div class="inline full_width">
                    <div class="inline">
                        <label for="search_relatively">Местоположение</label><br />
                        <select class="quarter" id="search_relatively" name="search_relatively" onchange="this.form.submit();">
                            <option value="<?= SEARCH_RELATIVELY_FROM_OR_TO ?>" <?php if ($search['search_relatively'] == SEARCH_RELATIVELY_FROM_OR_TO) echo 'selected'; ?> >Отправителя/Получателя</option>
                            <option value="<?= SEARCH_RELATIVELY_CURRENT ?>" <?php if ($search['search_relatively'] == SEARCH_RELATIVELY_CURRENT) echo 'selected'; ?> >Текущее</option>
                        </select>
                    </div>

                    <span class="right_indent"></span>

                    <div class="inline">
                        <label for="search_package_state">Состояние</label><br />
                        <select class="quarter" id="search_package_state" name="search_package_state" onchange="this.form.submit();">
                            <option value="<?= PACKAGE_STATE_ALL ?>" <?php if ($search['search_package_state'] == PACKAGE_STATE_ALL) echo 'selected'; ?> >[Все]</option>
                            <option value="<?= PACKAGE_STATE_RECEIVE ?>" <?php if ($search['search_package_state'] == PACKAGE_STATE_RECEIVE) echo 'selected'; ?> >Получено</option>
                            <option value="<?= PACKAGE_STATE_SEND ?>" <?php if ($search['search_package_state'] == PACKAGE_STATE_SEND) echo 'selected'; ?> >Отправлено</option>
                        </select>
                    </div>

                    <?php if ($search['active_flag'] == ACTIVE_FLAG_ARCHIVE): ?>

                    <span class="right_indent"></span>

                    <div class="inline">
                        <label for="date_create_begin">Период с</label><br />
                        <input type="text" id="date_create_begin" name="date_create_begin" value="<?= $search['date_create_begin'] ?>" class="tcal quarter" placeholder="с" />
                    </div>

                    <span class="right_indent"></span>

                    <div class="inline">
                        <label for="date_create_end">Период по</label><br />
                        <input type="text" id="date_create_end" name="date_create_end" value="<?= $search['date_create_end'] ?>" class="tcal quarter" placeholder="по" />
                    </div>
                    <?php endif; // if ($search['active_flag'] == ACTIVE_FLAG_ARCHIVE): ?>
                </div>
                <br />
                <br />



                <div class="inline full_width">

                    <div class="inline quarter">
                        <label for="search_place_from_or_to">Адрес</label><br />
                        <select class="quarter" id="search_place_from_or_to" name="search_place_from_or_to" onchange="this.form.submit();">
                            <option value="<?= SEARCH_PLACE_LOCAL ?>" <?php if ($search['search_place_from_or_to'] == SEARCH_PLACE_LOCAL) echo 'selected'; ?> >Района</option>
                            <option value="<?= SEARCH_PLACE_ADDRESS ?>" <?php if ($search['search_place_from_or_to'] == SEARCH_PLACE_ADDRESS) echo 'selected'; ?> >Организации</option>
                        </select>
                    </div>




                    <span class="right_indent"></span>

                    <div class="inline quarter"></div>

                    <span class="right_indent"></span>

                    <div class="inline quarter">
                        <label for="search_place_to_or_from">Адрес</label><br />
                        <select class="quarter" id="search_place_to_or_from" name="search_place_to_or_from" onchange="this.form.submit();">
                            <option value="<?= SEARCH_PLACE_LOCAL ?>" <?php if ($search['search_place_to_or_from'] == SEARCH_PLACE_LOCAL) echo 'selected'; ?> >Района</option>
                            <option value="<?= SEARCH_PLACE_ADDRESS ?>" <?php if ($search['search_place_to_or_from'] == SEARCH_PLACE_ADDRESS) echo 'selected'; ?> >Организации</option>
                        </select>
                    </div>

                    <span class="right_indent"></span>

                    <div class="inline quarter"></div>
                </div>
                <br />
                <br />


            <div class="full_width">

                <div class="inline">
                    <label for="from_or_to">
                        <?php if ($search['package_type'] == PACKAGE_INPUT): ?>
                            Для кого
                        <?php else: //if ($search['package_type'] == PACKAGE_INPUT): ?>
                            От кого
                        <?php endif; //if ($search['package_type'] == PACKAGE_INPUT): ?>
                    </label><br />
                    <select class="half" id="from_or_to" name="from_or_to" data-placeholder="Не выбрано" onchange="this.form.submit();"
                            <?php if (!$is_admin) echo 'disabled'; ?> >
                        <option value="0">[Все]</option>
                        <?php
                        if (count($only_companies) > 0):
                            foreach ($only_companies as $oc):
                                ?>
                                <optgroup label='<?= $oc['name'] ?>'>
                                    <?php
                                    foreach ($companies as $company):
                                        if ($company['company_id'] == $oc['company_id']):
                                            ?>
                                            <option value="<?= $company['id'] ?>" <?php if ($search['from_or_to'] == $company['id']) echo 'selected'; ?> >
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

                <span class="right_indent"></span>

                <div class="inline">
                    <label for="to_or_from">
                        <?php if ($search['package_type'] == PACKAGE_INPUT): ?>
                            От кого
                        <?php else: //if ($search['package_type'] == PACKAGE_INPUT): ?>
                            Для кого
                        <?php endif; //if ($search['package_type'] == PACKAGE_INPUT): ?>
                    </label><br />
                    <select class="half" id="to_or_from" name="to_or_from" data-placeholder="Не выбрано" onchange="this.form.submit();">
                        <option value="0">[Все]</option>
                        <?php
                        if (count($only_companies) > 0):
                            foreach ($only_companies as $oc):
                                ?>
                                <optgroup label='<?= $oc['name'] ?>'>
                                    <?php
                                    foreach ($companies as $company):
                                        if ($company['company_id'] == $oc['company_id']):
                                            ?>
                                            <option value="<?= $company['id'] ?>" <?php if ($search['to_or_from'] == $company['id']) echo 'selected'; ?> >
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
            </div>


        <?php endif; //if ($search_type == SEARCH_TYPE_SPECIAL): ?>

        </div>

        <br /><br /><br />

        <input type="submit" value="Найти" class="button one_eighth" />
    </form>
    <br /><br />



    <table class="view full_width">
        <tr class="head" align="center">
            <td style="width: 5%">№ п/п</td>
            <td style="width: 5%">Трек-<br />номер</td>
            <td style="width: 20%">Название</td>
            <td style="width: 40%">Откуда/Куда</td>
            <td style="width: 20%">Состояние</td>
            <td style="width: 10%">Действие</td>
        </tr>
        <?php
        $i = 0;
        foreach($packages as $package):
            $i++;
            $index_number++;
                ?>
        <tr class="presentation">

            <td align="center"><?= $index_number; ?></td>
            <td>
                <?= $package['package_number'] ?>
            </td>
            <td>
                <?= $package['package_note'] ?>
            </td>

            <td>
                Откуда:
                <?php if ($package['from_is_transit'] == 1) echo '[ТРАНЗИТ]'; ?>
                <?= $package['from_company_name'] ?><br />
                <span class="color_grey font_size_nine">
                    <?= $string_utility->getAddressToView(1, $package, 'from_'); ?>
                </span>
                <hr style="border: 0px none; border-bottom: 1px solid #d0d0d0; margin: 4px;" />
                Куда:
                <?php if ($package['to_is_transit'] == 1) echo '[ТРАНЗИТ]'; ?>
                <?= $package['to_company_name'] ?><br />
                <span class="color_grey font_size_nine">
                    <?= $string_utility->getAddressToView(1, $package, 'to_'); ?>
                </span>
            </td>
            <td>

                <div title="
                <?php
                if ($package['package_state'] == 1) echo $string_utility->getAddressToView(1, $package, 'now_from_');
                if ($package['package_state'] == 2) echo $string_utility->getAddressToView(1, $package, 'now_to_')
                ?>
                ">

                    <?php if ($package['package_state'] == 1): ?>

                        Получено в <?php if ($package['now_from_is_transit'] == 1) echo '[ТРАНЗИТ]'; ?>
                        <?= $package['now_from_company_name'] ?>
                        <?php if ($package['flag'] == 2) echo '<span class="fa fa-check-circle correct" title="Доставлено"></span>' ?>
                    <?php elseif ($package['package_state'] == 2): ?>

                        Отправлено в <?php if ($package['now_to_is_transit'] == 1) echo '[ТРАНЗИТ]'; ?>
                        <?= $package['now_to_company_name'] ?>

                    <?php endif; // if ($package['package_state'] == 1): ?>

                </div>

            </td>

            <td align="center">
                <div class="bg_button inline" title="Посмотреть объекты посылки"
                    onclick="window.open('/package/objects?pid=<?= $package['package_id'] ?>', 'new', '<?= DEFAULT_WINDOW ?>')">
                        <img src="/template/images/view_content.png">
                </div>

                <div class="bg_button inline" title="Посмотреть сопроводительный лист"
                     onclick="window.open('/site/barcode_39?pid=<?= $package['package_id'] ?>', 'new', '<?= DEFAULT_WINDOW ?>')">
                    <img src="/template/images/barcode.png">
                </div>

                <div class="bg_button inline" title="Посмотреть маршрут">
                     <a href="/route/view?<?= $link_get_param ?>&pid=<?= $package['package_id'] ?>">
                        <img src="/template/images/location.png">
                     </a>
                </div>
            </td>

        </tr>
        <?php endforeach; //foreach($packages as $package): ?>

    </table>
    <br /><br />
    <div class="head font_size_twelve full_width" align="center">Показано: <?= $i ?> из <?= $total_packages ?></div>
    <br /><br />
    <div id="pagination" class="pagination full_width font_size_twelve"><?= $pagination->get(); ?></div>

    <script src="/template/css/chosen/chosen.jquery.js" type="text/javascript"></script>
    <script type="text/javascript">
        $("#from_or_to").chosen({no_results_text: "Ничего не найдено", search_contains: true});

        $("#to_or_from").chosen({no_results_text: "Ничего не найдено", search_contains: true});
    </script>

<?php include ROOT . '/views/layouts/footer.php'; ?>