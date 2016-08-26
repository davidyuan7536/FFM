<div id="ProfileCard">
    <div class="A-card-image"><span class="A-frame"></span><img src="{$Artist|artist_picture}" alt="" width="130" height="130"/></div>
    <div class="A-card-info">
        <div class="A-text">{$Artist.description|escape|nl2br}</div>
        <div class="A-links">
            <div class="A-links-ul">
                {$Artist.links|escape|nl2br|links:'<span>$?</span>'}
            </div>
        </div>
    </div>
</div>
