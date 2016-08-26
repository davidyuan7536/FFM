{if $Editable}
<input type="hidden" id="PhotoDeleteConfirmation" value="{$LANG.artist.photoDeleteConfirmation}">
{/if}
<div style="padding: 10px 0;">
{if $Editable}
    <div style="position: relative; float: right; width: 180px;">
        <div style="position: absolute; top: 0; left: 0; height: 35px;"><span id="PhotoUploaderHolder"></span></div>
        <button class="F-button" style="width: 180px;">{$LANG.link.addPhoto}</button>
        <div id="PhotoUploaderStatus" style="min-height: 1.5em;"></div>
    </div>
{/if}
    <div class="clear"></div>
</div>
<div id="PhotoList">{include file='profile/photos_list.tpl'}</div>
<script type="text/javascript">{literal}
    $('.Photos-wrap a').lightBox();
{/literal}</script>
{if $Editable}
<script type="text/javascript">{literal}initPhotoEdit();{/literal}</script>
{/if}
