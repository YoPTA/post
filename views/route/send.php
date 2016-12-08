<?php
$pagetitle = 'Отправить';
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

<div class="font_size_fourteen full_width">
    <div class="inline fl ">
        <span><b>Посылка:</b> <?= $p_note; ?></span><br /><br />
        <span><b>Трек-номер:</b> <?= $p_number; ?></span>
        <div class="more half font_size_twelve" align="left">
            <div align="center" class="bg_button inline view_content" id="more_btn"  title="Посмотреть содержимое посылки">
                <img src="/template/images/view_content.png" alt="Посмотреть содержимое посылки"/>
            </div>
            <div class="moreText font_size_twelve shadowed bg_envelope_inside" align="left">
                <?php
                if($package_objects != null && is_array($package_objects)): ?>
                    <ol class="undreline">
                        <?php foreach($package_objects as $p_obj): ?>
                            <li  class="font_size_twelve"><?= $p_obj['name'] ?></li>
                        <?php endforeach; //foreach($package_objects as $p_obj): ?>
                    </ol>
                <?php else: echo 'Конверт пуст';?>
                <?php endif;//if($package != null && $package_objects != null): ?>
            </div>
        </div>

        <script>
            $(document).click( function(event){
                if( $(event.target).closest(".moreText").length )
                    return;
                $(".moreText").slideUp("normal");
                event.stopPropagation();
            });
            $('#more_btn').click( function() {
                $(this).siblings(".moreText").slideToggle("normal");
                return false;
            });
        </script>
    </div>
    <span class="right_indent"></span>
    <div class="inline fr half">
        <div class="bg_button inline" title="Выбрать доверенное лицо">
            <a href="/proxy/person_index?track=<?= $track ?>&site_page=<?= $site_page ?>&date_create=<?= $date_create ?>&package_type=<?= $package_type ?>&office=<?= $office ?>&pid=<?= $pid ?>&rid=<?= $rid ?>">

                <img src="/template/images/proxy_person.png" alt="Выбрать доверенное лицо" />
            </a>
        </div>
        <span class="right_indent"></span>
        <div id="dinamic_content">
            <br />
            <div class="inline bg_button" id="clear" title="Отчистить">
                <img src="/template/images/besom.png" />
            </div>
            <?php if ($proxy != null && $proxy_person != null): ?>
                <br /><br />
                <table class="view half">
                    <tr class="presentation">
                        <td class="accent one_eighth">Доверенное лицо</td>
                        <td><?= $proxy_person['lastname'] . ' ' . $proxy_person['firstname'] . ' ' . $proxy_person['middlename'] ?></td>
                    </tr>
                    <tr class="presentation">
                        <td class="accent">Доверенность</td>
                        <td>
                            <p>Орган выдачи: <?= $proxy['authority_issued'] ?></p>
                            <p>Дата выдачи: <?= $proxy['date_issued'] ?></p>
                            <p>Дата истечения: <?= $proxy['date_expired'] ?></p>
                        </td>
                    </tr>
                </table>
                <br /><br />
                <div align="center">
                    <form method="POST">
                        <button class="button one_eighth" name="send">
                            <img src="/template/images/paper-plane.png">
                            Отправить
                        </button>
                    </form>
                </div>
            <?php endif; // if ($proxy == null || $proxy_person == null): ?>
        </div>

    </div>
    <br />

</div>

<?php if ($proxy != null && $proxy_person != null): ?>

<?php endif; // if ($proxy == null || $proxy_person == null): ?>
    <!-- Асинхронные запросы -->
    <script>
        $(document).ready(function(){
            $("#clear").click(function () {
                $.post("/route/clear_proxy", {}, function (data) {
                    $("#dinamic_content").html(data);
                });
                return false;
            });
        });
    </script>

<?php include ROOT . '/views/layouts/footer.php'; ?>