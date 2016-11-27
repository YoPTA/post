<?php
$pagetitle = 'Выбор пункта';
$page_id = 'page_index';

//Подключаем шапку
include ROOT . '/views/layouts/header.php';

?>

<h2 align="center"><?= $pagetitle ?></h2>
<div class="full_width font_size_twelve">
    <form method="POST" class="font_size_zero">
        <!-- <input class="half right_indent" type="text" placeholder="Организация/Адрес" name="search_company" value="" /><span class="right_indent"></span>
        <input class="button one_eighth" type="submit" value="Найти"> -->
        <table class="font_size_twelve full_width">
            <tr class="font_size_fourteen">
                <th></th>
                <th class="half">Организация</th>
                <th class="half">Адрес</th>
            </tr>
            <?php if(is_array($companies) && $companies != null): ?>
                <?php
                $i = 0;
                foreach($companies as $company):
                    $i++;
                    if($i%2==0):
                        ?>
                        <tr class="bgGray">
                    <?php else: ?>
                        <tr class="bgGray_double">
                    <?php endif; //if($i%2==0): ?>

                    <td>
                        <input type="radio" name="company" value="<?= $company['ca_id'] ?>" <?php if($company['ca_id'] == $select_company) echo 'checked' ?> />
                    </td>

                    <td title='<?= $company['c_full_name'] ?>'><?= $company['c_name']. '('.$company['c_key_field'].')' ?></td>
                    <td>
                        <?php
                        if($company['ca_address_country'] != null)
                            echo $company['ca_address_country'];
                        if($company['ca_address_zip'] != null)
                            echo ', '.$company['ca_address_zip'];
                        if($company['ca_address_region'] != null)
                            echo ', '.$company['ca_address_region'];
                        if($company['ca_address_area'] != null)
                            echo ', '.$company['ca_address_area'];
                        if($company['ca_address_city'] != null)
                            echo ', '.$company['ca_address_city'];
                        if($company['ca_address_town'] != null)
                            echo ', '.$company['ca_address_town'];
                        if($company['ca_address_street'] != null)
                            echo ', ' . $company['ca_address_street'];
                        if($company['ca_address_home'] != null)
                            echo ', ' . $company['ca_address_home'];
                        if($company['ca_address_case'] != null)
                            echo ', ' . $company['ca_address_case'];
                        if($company['ca_address_build'] != null)
                            echo ', '.$company['ca_address_build'];
                        if($company['ca_address_apartment'] != null)
                            echo ', '.$company['ca_address_apartment'];
                        ?>
                    </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; //if(is_array($companies) && $companies != null) ?>
        </table><br />
        <input type="submit" class="one_eighth button" value="Выбрать" name="select" />
    </form>
</div>

<?php include ROOT . '/views/layouts/footer.php'; ?>
