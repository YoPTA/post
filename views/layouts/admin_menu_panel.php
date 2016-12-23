<div id="admin_menu_panel" class="quarter inline font_size_twelve shadowed">
<h4 align="center">Панель меню</h4>
<?php
if (isset($admin_menu_panel) && is_array($admin_menu_panel)):
    foreach ($admin_menu_panel as $a_m_p_item):
?>
    <?php
        if ((($a_m_p_item['member'] == CAN_CHANGE_USER) && $admin_rights['can_change_user']) ||
            (($a_m_p_item['member'] == CAN_CHANGE_STUFF) && $admin_rights['can_change_stuff'])):
    ?>
    <a href="<?= $a_m_p_item['url_address'] ?>" title="<?= $a_m_p_item['title'] ?>">&#8226; <?= $a_m_p_item['name'] ?></a><br />
    <?php endif;  ?>

<?php

    endforeach; // foreach ($admin_menu_panel as $a_m_p_item):
endif; //if (isset($admin_menu_panel) && is_array($admin_menu_panel)):
?>
    <br />
</div>