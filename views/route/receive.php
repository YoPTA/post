<?php
$pagetitle = 'Подтвердить получение';
$page_id = 'page_index';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';

?>
    <h2 align="center"><?= $pagetitle ?></h2>
    <div class="font_size_twelve" align="center">
        <a href="/route/view?track=<?= $track ?>&site_page=<?= $site_page ?>&date_create=<?= $date_create ?>&package_type=<?= $package_type ?>&office=<?= $office ?>&pid=<?= $pid ?>">
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

    <div class="font_size_fourteen full_width">
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
        <table class="view">
            <tr class="presentation">
                <td class="accent one_eighth">Доверенное лицо:</td>
                <td class="quarter dinamic_content">
                    <div id="proxy_person"><?= $proxy_person['lastname'] . ' ' . $proxy_person['firstname'] . ' ' . $proxy_person['middlename'] ?></div>
                </td>
                <td class="bg_none one_eighth ">
                    <div class="inline bg_button" title="Выбрать доверенное лицо" style="padding: 0">
                        <a href="/proxy/person_index?track=<?= $track ?>&site_page=<?= $site_page ?>&date_create=<?= $date_create ?>&package_type=<?= $package_type ?>&office=<?= $office ?>&pid=<?= $pid ?>&rid=<?= $rid ?>&user_ref=<?= USER_REFERENCE_RECEIVE ?>">
                            <img src="/template/images/proxy_person.png" alt="Выбрать доверенное лицо" />
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
                <td class="accent one_eighth">Доверенность</td>
                <td class="quarter dinamic_content">
                    <?php if ($proxy != null && $proxy_person != null): ?>
                        <div  id="proxy">
                            <p>Орган выдачи: <?= $proxy['authority_issued'] ?></p>
                            <p>Дата выдачи: <?= $date_converter->dateToString($proxy['date_issued']) ?></p>
                            <p>Дата истечения: <?= $date_converter->dateToString($proxy['date_expired']) ?></p>
                        </div>
                    <?php endif; // if ($proxy == null || $proxy_person == null): ?>
                </td>
                <td class="bg_none"></td>
            </tr>
        </table>
        <br /><br />


        <form method="POST">
            <label style="color: #1D2B37">
                <input type="checkbox" name="without_proxy" value="1" id="without_proxy"
                    <?php if ($without_proxy == 1) echo 'checked';  ?>
                       style="vertical-align: middle"/>
                Без доверенного лица
            </label>

            <br /><br />
            <button class="button one_eighth" name="receive">
                <img src="/template/images/mail-receive.png">
                Принять
            </button>
        </form>
        <br />

    </div>

    <script>
        $(document).ready(function(){
            $("#clear").click(function () {
                $.post("/route/clear_proxy", {}, function (data) {
                    $(".dinamic_content").html(data);
                });
                return false;
            });
        });

        document.getElementById('without_proxy').onclick = function() {
            var proxy = document.getElementById('proxy');
            var proxy_person = document.getElementById('proxy_person');

            if ( this.checked ) {
                proxy.className = 'color_grey';
                proxy_person.className = 'color_grey';
            } else {
                proxy.className = '';
                proxy_person.className = '';
            }
        };
    </script>

<?php include ROOT . '/views/layouts/footer.php'; ?>