<link rel="stylesheet" type="text/css" href="p.css?{$V}"/>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="/js/sources/jquery-ui.js?{$V}"></script>
<script type="text/javascript" src="js/utils.js?{$V}"></script>
<script type="text/javascript" src="js/pictures.js?{$V}"></script>
<script type="text/javascript">var PATH_PICTURES = '{$smarty.const.__FFM_PICTURES_FRONT__}';</script>
<script type="text/javascript">{literal}
$(document).ready(function() {
    loadYears();
});
{/literal}</script>

<h1>{$Title}</h1>

<div class="Filter">
    <table>
        <tr>
            <td><select id="Years"></select></td>
        </tr>
    </table>
</div>

<div style="padding: 10px 0;">
    <div id="List" class="Pic-wrap">Loading...</div>
</div>

<div id="Dialog" title="Picture" style="display: none;">
    <input type="hidden" id="Id" />
</div>
