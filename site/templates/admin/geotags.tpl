<link rel="stylesheet" type="text/css" href="g.css?{$V}"/>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="/js/sources/jquery-ui.js?{$V}"></script>
<script type="text/javascript" src="js/utils.js?{$V}"></script>
<script type="text/javascript" src="js/geotags.js?{$V}"></script>

<h1>{$Title}</h1>

<div>
    <table>
        <tr>
            <td><button class="Button Small" id="Add">Add Geotag</button></td>
        </tr>
    </table>
</div>

<div style="padding: 10px 0;">
    <div id="List">Loading...</div>
    <div id="Space"></div>
</div>

<style type="text/css">{literal}
#Map {
    width: 450px;
    height: 300px;
    border: 1px solid #cbd0d6;
}
{/literal}</style>

<div id="Dialog" title="Geotag" style="display: none;">
    <input type="hidden" id="Id" />
    <table>
    <col style="width: 150px;"/>
    <tr>
        <td><label for="Filename" class="F-label">Filename</label></td>
        <td><input type="text" id="Filename" readonly="readonly" style="width: 300px;" /></td>
    </tr>
    <tr>
        <td><label for="Name" class="F-label">Name</label></td>
        <td><input type="text" id="Name" style="width: 300px;" /></td>
    </tr>
    <tr>
        <td><label for="Fullname" class="F-label">Full name</label></td>
        <td><input type="text" id="Fullname" style="width: 300px;" /></td>
    </tr>
    <tr>
        <td><label for="Wikilink" class="F-label">Wiki link</label></td>
        <td><input type="text" id="Wikilink" style="width: 300px;" /></td>
    </tr>
    <tr>
        <td>
            <div style="position: relative;">
                <div style="position: absolute; right: 0; top: -5px; cursor: pointer;"><img id="Marker" src="/site/i/icons/marker.png" alt="" width="24" height="24"></div>
                <label for="Lat" class="F-label">Location</label>
            </div>
        </td>
        <td>
            <input type="text" id="Lat" style="width: 90px;" />,
            <input type="text" id="Lng" style="width: 90px;" />
            zoom:
            <input type="text" id="Zoom" style="width: 50px;" maxlength="2" />
        </td>
    </tr>
    <tr>
        <td style="padding-top: 10px;"><label for="Map" class="F-label">Map:</label></td>
    </tr>
    <tr>
        <td colspan="2">
            <div id="Map"></div>
        </td>
    </tr>
    <tr>
        <td><label for="Quick" class="F-label">Quick search:</label></td>
        <td><input type="text" id="Quick" style="width: 300px;" /></td>
    </tr>
    <tr>
        <td align="right"></td>
        <td>
            <div style="padding-top: 20px;">
                <button id="Delete" class="Button Small" style="float: right;" tabindex="-1">Delete</button>
                <button id="Save" class="Button blue F-button">Save</button>
                <button id="Cancel" class="Button gray F-button">Cancel</button>
            </div>
        </td>
    </tr>
    </table>
</div>
