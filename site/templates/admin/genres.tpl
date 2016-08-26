<link rel="stylesheet" type="text/css" href="g.css?{$V}"/>
<script src="/js/sources/jquery-ui.js?{$V}"></script>
<script type="text/javascript" src="js/utils.js?{$V}"></script>
<script type="text/javascript" src="js/genres.js?{$V}"></script>

<h1>{$Title}</h1>

<div>
    <table>
        <tr>
            <td><button class="Button Small" id="Add">Add Genre</button></td>
        </tr>
    </table>
</div>

<div style="padding: 10px 0;">
    <div id="List">Loading...</div>
    <div id="Space"></div>
</div>

<div id="dialog" title="Genre" style="display: none;">
    <input type="hidden" id="Id" />
    <table>
    <col style="width: 150px;"/>
    <tr style="aheight: 30px;">
        <td><label for="Filename" class="F-label">Filename</label></td>
        <td><input type="text" id="Filename" readonly="readonly" style="width: 300px;" /></td>
    </tr>
    <tr>
        <td><label for="Name" class="F-label">Name</label></td>
        <td><input type="text" id="Name" style="width: 300px;" /></td>
    </tr>
    <tr>
        <td align="right" height=40></td>
        <td>
            <div>
                <button id="Delete" class="Button Small" style="float: right;" tabindex="-1">Delete</button>
                <button id="Save" class="Button blue F-button">Save</button>
                <button id="Cancel" class="Button gray F-button">Cancel</button>
            </div>
        </td>
    </tr>
    </table>
</div>
