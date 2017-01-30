                </td>
            </tr>
        </table>
    </td>
</tr>
<tr id="footer">
    <td align="center">
        <div class="footer">
            <div class="la disp_inline"></div>
            <div class="ca disp_inline">
                &copy; Государственное автономное учреждение Пензенской области "Многофункциональный центр
                предоставления государственных и муниципальных услуг", <?= date('Y'); ?>
            </div>
            <div class="ra disp_inline"></div>
        </div>
    </td>
</tr>
</table>

<script>
    function activeBtn(page_id)
    {
        if(document.getElementById(page_id))
        {
            document.getElementById(page_id).className += ' activeted';
        }
    }
    activeBtn('<?= $page_id ?>');
</script>

<script type="text/javascript">
    // menu
    $(document).ready(function(){
        $('#menu li').hover(
            function() {
                $(this).addClass("active");
                $(this).find('ul').stop(true, true);
                $(this).find('ul').slideDown('normal');
            },
            function() {
                $(this).removeClass("active");
                $(this).find('ul').slideUp('normal');
            }
        );
    });
</script>
<?php
if ($is_notification):
?>
<div id="dinamic_notification" hidden="hidden"></div>

<script type="text/javascript">

    var fn=function(){

        $.post("/site/notification", {}, function (data) {

            $("#dinamic_notification").html(data);

            if ($("#dinamic_notification").text() == '1')
            {
                var myNewWindow = window.open("/notification/index","notification","<?= DEFAULT_WINDOW ?>");
                myNewWindow.focus();
            }
        });
    }
    setInterval( fn,10*1000 );
</script>
<?php endif; //if ($is_notification): ?>

</body>
</html>