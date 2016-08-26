{foreach from=$Events item=row}
    <div class="E-wrap">
        <div class="H-event">{$row.event_name}</div>
        <div class="E-subtitle"><span>{$row.event_date|date_format:"%e %B"|date_ru}</span>, {$row.event_date|date_format:"%A"|date_ru}, в {$row.event_date|date_format:"%H:%M"}</div>
        <div>{if $row.event_image != ''}<div class="event-photo"><img src="/thumbnails/events/{$row.event_image}.jpg" alt="" /></div>{/if}</div>
        <div class="E-text">{$row.event_description|links:''|nl2br}</div>
        <div class="E-text">{$row.event_address|links:''|nl2br}</div>
    </div>
{/foreach}
{if $NextEventsPage > 0}
<div class="E-wrap"><a href="/m/e{$NextEventsPage}" target="_replace">Get 10 More Events...</a></div>
{/if}

