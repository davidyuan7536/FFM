{if $Editable}
<div style="padding: 10px 0; text-align: right;">
    <button class="F-button" onclick="addEventForm();">{$LANG.link.addEvent}</button>
</div>
<div id="EventEdit" style="display: none;" class="A-Events-row">
    <table>
    <colgroup>
        <col style="width: 32px;"/>
        <col style="width: 90px;"/>
        <col style="width: 125px;"/>
        <col style="width: 193px;"/>
        <col style="width: 130px;"/>
        <col style="width: 75px;"/>
    </colgroup>
    <tr style="vertical-align: top;">
        <td><input type="hidden" id="EventDeleteConfirmation" value="{$LANG.artist.eventDeleteConfirmation}" /><input type="hidden" id="EventId" /><input type="hidden" id="EventDate" /></td>
        <td>
            <div><span id="EventDateLabel" style="text-decoration: underline; cursor: pointer;" title="{$LANG.artist.date}">{$LANG.artist.date}</span></div>
            <div id="eventImageUploadNotification" style="font-size: 10px;line-height: 1em;padding-top: 10px;">{$LANG.artist.eventImageUploadNotification}</div>
            <div id="eventImageUpload" style="display: none; padding-top: 10px;position: relative;">
                <div style="height: 60px;"><img id="EventImage" width="80"></div>
                <div style="position: absolute; top: 10px; left: 0;"><div id="EventImageUploaderHolder">[upload]</div></div>
                <div id="EventImageUploaderStatus"></div>
            </div>
        </td>
        <td>
            <div><textarea class="F-text" maxlength="200" style="width: 110px; height: 35px; font-weight: bold;" placeholder="{$LANG.artist.eventName}" id="EventName"></textarea></div>
            <div>
                <input type="hidden" id="EventGeoSearchId"/>
                <input type="text" class="F-input" style="width: 110px;" id="EventGeoSearch" placeholder="{$LANG.artist.geoText}" />
            </div>
        </td>
        <td><textarea class="F-text" maxlength="500" style="width: 180px; height: 60px; font-size: 11px;" placeholder="{$LANG.artist.eventDescription}" id="EventDescription"></textarea></td>
        <td><textarea class="F-text" maxlength="500" style="width: 120px; height: 60px; font-size: 11px;" placeholder="{$LANG.artist.eventAddress}" id="EventAddress"></textarea></td>
        <td style="vertical-align: top;">
            <div style="padding-bottom: 4px;"><button class="F-button-mini-active" id="EventSave">{$LANG.link.saveMini}</button></div>
            <div><button class="F-button-mini" id="EventDelete" style="display: none;">{$LANG.link.delete}</button><button class="F-button-mini" id="EventCancel" style="display: none;">{$LANG.link.cancel}</button></div>
        </td>
    </tr>
    </table>
</div>
<script type="text/javascript">{literal}
$('#EventGeoSearch').autocomplete({
    source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex( request.term ), "i" );
        response( $.grep( availableTags, function( value ) {
            value = value.label || value.value || value;
            return matcher.test( value ) || matcher.test( normalize( value ) );
        }) );
    },
    focus: function( event, ui ) {
        $( '#EventGeoSearch' ).val( ui.item.label );
        return false;
    },
    select: function( event, ui ) {
        $( '#EventGeoSearch' ).val( ui.item.label );
        $( '#EventGeoSearchId' ).val( ui.item.value );
        return false;
    }
}).data('autocomplete')._renderItem = function( ul, item ) {
    return $('<li></li>')
        .data('item.autocomplete', item)
        .append('<a>' + item.label + '</a>')
        .appendTo(ul);
};
{/literal}</script>
{/if}
<div class="A-Events-wrap">
    <div id="EventsNew" class="A-Events-new"></div>
    <div id="EventList">{include file='profile/events_list.tpl'}</div>
</div>
{if $Editable}
<script type="text/javascript">{literal}initEventEdit();{/literal}</script>
{/if}
