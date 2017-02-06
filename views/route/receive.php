<?php
$pagetitle = 'Подтвердить получение';
$page_id = 'page_index';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';

?>
    <h2 align="center"><?= $pagetitle ?></h2>
    <div class="font_size_twelve" align="center">
        <a href="/route/view?<?= $link_to_back ?>&page=<?= $site_page ?>&pid=<?= $pid ?>">
            &#8592; Вернуться назад
        </a>
    </div>
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

<form method="POST">
        <div class="font_size_fourteen full_width">
            <h3>Посылка</h3>
            <hr />
            <table class="view">
                <tr class="presentation">
                    <td class="accent one_eighth">Посылка:</td>
                    <td class="quarter"><?= $p_note; ?></td>
                </tr>
                <tr class="presentation">
                    <td class="accent one_eighth">Трек-номер:</td>
                    <td class="quarter"><?= $p_number; ?></td>
                </tr>
            </table>
            <br /><br />
            <label>Тип передачи</label><br />
            <select class="quarter" name="with_or_without" id="with_or_without" onchange="this.form.submit();">
                <option value="0" <?php if ($with_or_without == 0) echo ' selected'; ?> >Выберите</option>
                <option value="2" <?php if ($with_or_without == 2) echo ' selected'; ?>>С доверенным лицо</option>
                <option value="1" <?php if ($with_or_without == 1) echo ' selected'; ?>>Без доверенного лица</option>
            </select>
            <?php if ($with_or_without == 2): ?>
                <div class="full_width inline" id="with_proxy">
                    <div class="half fl inline">
                        <h3>Доверенное лицо</h3>
                        <hr />
                        <table class="view" id="proxy_person">
                            <tr class="presentation">
                                <td class="accent one_eighth">ФИО:</td>
                                <td class="quarter dinamic_content">
                                    <?= $proxy_person['lastname'] . ' ' . $proxy_person['firstname'] . ' ' . $proxy_person['middlename'] ?>
                                </td>
                                <td class="bg_none one_eighth" valign="top">
                                    <div class="inline bg_button" title="Выбрать доверенное лицо" style="padding: 0">
                                        <a href="/proxy/person_index?<?= $link_to_back ?>&site_page=<?= $site_page ?>&pid=<?= $pid ?>&rid=<?= $rid ?>&user_ref=<?= USER_REFERENCE_RECEIVE ?>&wow=<?=$with_or_without ?>&search=<?= $proxy_person['lastname'] . ' '.$proxy_person['firstname'] . ' ' . $proxy_person['middlename'] ?>">
                                            <img src="/template/images/edit.png" alt="Выбрать доверенное лицо" />
                                        </a>
                                    </div><span class="right_indent negative_left_indent"></span>

                                    <?php if ($proxy != null && $proxy_person != null): ?>
                                        <div class="inline bg_button dinamic_content" id="clear" title="Отчистить">
                                            <img src="/template/images/besom.png" />
                                        </div>
                                    <?php endif; // if ($proxy == null || $proxy_person == null): ?>
                                </td>
                            </tr>

                            <tr class="presentation">
                                <td colspan="2" align="center"><b>Паспортные данные</b></td>
                                <td class="bg_none one_eighth"></td>
                            </tr>
                            <tr class="presentation">
                                <td class="accent one_eighth">Серия и номер:</td>
                                <td class="quarter dinamic_content">
                                    <?= $proxy_person['document_series'] . ' ' .$proxy_person['document_number']  ?>
                                </td>
                                <td class="bg_none one_eighth"></td>
                            </tr>
                            <tr class="presentation">
                                <td class="accent one_eighth">Место выдачи:</td>
                                <td class="quarter dinamic_content">
                                    <?= $proxy_person['place_name']  ?>
                                </td>
                                <td class="bg_none one_eighth"></td>
                            </tr>
                            <tr class="presentation">
                                <td class="accent one_eighth">Дата выдачи:</td>
                                <td class="quarter dinamic_content">
                                    <?php
                                    if ($date_converter->dateToString($proxy_person['date_issued']) != '00.00.0000')
                                        echo $date_converter->dateToString($proxy_person['date_issued'])
                                    ?>
                                </td>
                                <td class="bg_none one_eighth"></td>
                            </tr>

                        </table>
                    </div>
                    <span class="right_indent"></span>
                    <div class="half fr inline">
                        <h3>Доверенность</h3>
                        <hr />
                        <table class="view" id="proxy">
                            <tr class="presentation">
                                <td class="accent one_eighth">Орган выдачи:</td>
                                <td class="quarter dinamic_content">
                                    <?= $proxy['authority_issued'] ?>
                                </td>
                                <td class="bg_none one_eighth" valign="top">
                                    <?php if ($proxy != null && $proxy_person != null): ?>
                                        <div class="inline bg_button dinamic_content" title="Выбрать доверенность" style="padding: 0">
                                            <a href="/proxy/person_view?<?= $link_to_back ?>&site_page=<?= $site_page ?>&pid=<?= $pid ?>&rid=<?= $rid ?>&user_ref=<?= USER_REFERENCE_RECEIVE ?>&wow=<?= $with_or_without ?>&search=&p_pid=<?= $proxy_person_id ?>">
                                                <img src="/template/images/edit.png" alt="Выбрать доверенность" />
                                            </a>
                                        </div>
                                    <?php endif; // if ($proxy == null || $proxy_person == null): ?>
                                </td>
                            </tr>
                            <tr class="presentation">
                                <td class="accent one_eighth">Дата выдачи:</td>
                                <td class="quarter dinamic_content">
                                    <?php
                                    if ($date_converter->dateToString($proxy['date_issued']) != '00.00.0000')
                                        echo $date_converter->dateToString($proxy['date_issued'])
                                    ?>
                                </td>
                                <td class="bg_none one_eighth"></td>
                            </tr>
                            <tr class="presentation">
                                <td class="accent one_eighth">Дата истечения:</td>
                                <td class="quarter dinamic_content">
                                    <?php
                                    if ($date_converter->dateToString($proxy['date_expired']) != '00.00.0000')
                                        echo $date_converter->dateToString($proxy['date_expired'])
                                    ?>
                                </td>
                                <td class="bg_none one_eighth"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            <?php endif; //if ($with_or_without == 2): ?>

            <br /><br /><br />

            <button class="button one_eighth" name="receive">
                <img src="/template/images/mail-receive.png">
                Принять
            </button>

            <br />

        </div>
    </form>

    <script>
        $(document).ready(function(){
            $("#clear").click(function () {
                $.post("/route/clear_proxy", {}, function (data) {
                    $(".dinamic_content").html(data);
                });
                return false;
            });
        });
    </script>

<?php include ROOT . '/views/layouts/footer.php'; ?>