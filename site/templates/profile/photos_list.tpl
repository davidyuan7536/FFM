{if !empty($Photos)}
    <div class="Photos-wrap">
        {if $Artist}
            {foreach from=$Photos item=row}
                <div class="Photos-item">
                    {if $Editable}<div class="Photos-action" photo="{$row['photo_id']}">{$LANG.link.delete}</div>{/if}
                    <a href="{$smarty.const.__FFM_PROFILE_FRONT__}{$Artist['filename']}/b/{$row['photo_filename']}"><img src="{$smarty.const.__FFM_PROFILE_FRONT__}{$Artist['filename']}/m/{$row['photo_filename']}" width="130" height="130" /></a>
                </div>
            {/foreach}
        {/if}
        {if $Promoter}
            {foreach from=$Photos item=row}
                <div class="Photos-item">
                    {if $Editable}<div class="Photos-action" photo="{$row['photo_id']}">{$LANG.link.delete}</div>{/if}
                    <a href="{$smarty.const.__FFM_PROMOTER_FRONT__}{$Promoter['promoter_filename']}/b/{$row['photo_filename']}"><img src="{$smarty.const.__FFM_PROMOTER_FRONT__}{$Promoter['promoter_filename']}/m/{$row['photo_filename']}" width="130" height="130" /></a>
                </div>
            {/foreach}
        {/if}
        <div class="clear"></div>
    </div>
{/if}
