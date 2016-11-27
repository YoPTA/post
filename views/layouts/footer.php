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
                предоставления государственных и муниципальных услуг", 2015-<?= date('Y'); ?>
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

</body>
</html>