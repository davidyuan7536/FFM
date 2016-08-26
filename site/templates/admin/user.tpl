<script type="text/javascript" src="js/utils.js?{$V}"></script>
<script type="text/javascript" src="js/user.js?{$V}"></script>

<h1>{$Title}</h1>

<input type="hidden" id="Id" value="{$User.user_id}" />

<div class="F-wrap" style="margin-bottom: 20px;">
<table>
<col style="width: 150px;"/>
<tr>
    <td>Ip</td>
    <td>{$User.ip}</td>
</tr>
<tr>
    <td>Place</td>
    <td>{if $User.country_code}
            <a style="text-decoration: none; font-weight: bold;" href="http://maps.google.com/maps?sll={$User.latitude},{$User.longitude}&q={$User.latitude},{$User.longitude}" target="_blank"><img src="/i/flags/{$User.country_code|lower}.png" alt="" width="16" height="11" style="vertical-align: 0;"/> {$User.city}, {$Country}</a>
        {else}Unknown{/if}</td>
</tr>
<tr>
    <td><label for="Hash" class="F-label">Id</label></td>
    <td><input type="text" id="Hash" value="{$User.user_hash|escape}" style="width: 300px;" /></td>
</tr>
<tr>
    <td><label for="Name" class="F-label">Name</label></td>
    <td><input type="text" id="Name" value="{$User.user_name|escape}" style="width: 300px;" /></td>
</tr>
<tr>
    <td><label for="Email" class="F-label">Email</label></td>
    <td><input type="text" id="Email" value="{$User.user_email|escape}" style="width: 300px;" /></td>
</tr>
<tr>
    <td><label for="Password" class="F-label">New Password <small>(optional)</small></label></td>
    <td><input type="text" id="Password" value="" style="width: 300px;" /></td>
</tr>
<tr>
    <td></td>
    <td><input type="checkbox" id="Enabled" style="vertical-align: -1px;" {if $User.enabled==1}checked="checked"{/if}/><label for="Enabled" class="F-label">Enabled</label></td>
</tr>
<tr>
    <td></td>
    <td><input type="checkbox" id="Subscribe" style="vertical-align: -1px;" {if $User.subscribe==1}checked="checked"{/if}/><label for="Subscribe" class="F-label">Subscribe</label></td>
</tr>
<tr>
    <td align="right"></td>
    <td>
        <div style="padding-top: 10px;">
            {*<button id="Delete" class="Button Small" onclick="deleteArtist();" style="float: right;" tabindex="-1">Delete</button>*}
            <button id="Save" class="Button blue F-button" onclick="save();">Save</button>
            <button id="Cancel" class="Button gray F-button" onclick="cancel()">Cancel</button>
        </div>
    </td>
</tr>
</table>
</div>
