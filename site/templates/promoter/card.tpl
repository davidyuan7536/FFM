<div id="ProfileCard">
    <div class="A-card-image">
        {if $Promoter.promoter_status == $smarty.const.PROMOTER_STATUS_CLUB}<div class="AR-club"></div>{else}<div class="AR-person"></div>{/if}
        <img src="{$Promoter|promoter_picture}" alt="" width="130" height="130"/></div>
    <div class="A-card-info">
        <div class="A-text">{$Promoter.promoter_description|escape|nl2br}</div>
        <div class="A-links">
            <div class="A-links-ul">
                {$Promoter.promoter_links|escape|nl2br|links:'<span>$?</span>'}
            </div>
        </div>
        {if !empty($Promoter.promoter_extra)}
            <div class="Profile-extra-info">
                {$Promoter.promoter_extra|escape|nl2br|links:'<span>$?</span>'}
            </div>
        {/if}
    </div>
</div>
