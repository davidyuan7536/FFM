<script type="text/javascript" src="/js/sources/jquery-ui.js?{$V}"></script>

<h1>{$Title}</h1>

<div style="padding: 2px 0 4px;">
    <div id="RatingButton"><div style="background-color: #ffcc00; padding: 4px 8px;">Loading...</div></div>
</div>

<div style="padding: 8px 0 0;" id="Rating">
<table class="T-panel">
<col style="width: 20px;" />
<col style="width: 50px;" />
<col />
<col style="width: 80px;" />
<col style="width: 80px;" />
<col style="width: 100px;" />
<tr>
    <th><input type="checkbox" checked="checked" disabled="disabled"></th>
    <th></th>
    <th>Name</th>
    <th>Likes</th>
    <th>Rating</th>
    <th>Status</th>
</tr>
{foreach from=$Artists item=row}
    {cycle values='Odd,Even' assign=RowStyle}
    <tr class="{$RowStyle}" artist_id="{$row.artist_id}">
        <td><input type="checkbox" class="RowCheckbox" checked="checked" disabled="disabled"></td>
        <td>{*<span><img src="{$row|artist_picture:"s"}" class="Thumbnail" alt="" width="32" height="32"/></span>*}</td>
        <td>
            <div class="Trunc"><a href="artist.php?id={$row.artist_id}">{$row.name|escape}</a></div>
            <small><div class="Trunc">{$row.name_ru|escape}</div></small>
        </td>
        <td><div class="Likes">{$row.artist_likes}</div></td>
        <td><div class="Rating">{$row.artist_rating}</div></td>
        <td><div class="Status Trunc"></div></td>
    </tr>
{/foreach}
</table>
</div>
<script>{literal}
$(function() {
    $('#Rating input[type=checkbox]').attr('disabled', false);
    $('#Rating th input[type=checkbox]').click(function() {
        var c = $(this).is(':checked');
        $('.RowCheckbox').attr('checked', c);
    });
    $('<button class="Button Small">Load</button>').appendTo($('#RatingButton').empty()).click(startLoad);
});
function startLoad() {
    var cl = $('.RowCheckbox:checked');
    if (cl.length > 0) {
        loadData(cl.get(0));
    }
}
var cursor;
function loadData(c) {
    cursor = c;
    var tr = $(cursor).parents('tr');
    $('.Status', tr).text('Loading...');

    var data = {
        'Action': 'GetRating',
        'Id': tr.attr('artist_id')
    };

    $.post(self.location, data, onLoadData, 'json');
}
function onLoadData(result) {
    var c, tr = $(cursor).parents('tr');

    if (result['message']) {
        alert(result['message'])
    } else {
        $.each(result['elements'], function(key, value) {
            $(key, tr).empty().html(value);
        });
    }

    tr.nextAll('tr').each(function() {
        c = $('input:checked', this);
        if (c.length > 0) {
            return false;
        }
    });
    if (c) {
        loadData(c);
    }
}
{/literal}</script>