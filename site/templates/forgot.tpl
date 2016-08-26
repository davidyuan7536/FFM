<!DOCTYPE html>
<html lang="{$LANG.id}">
<head>
{include file='includes/global_head.tpl'}
</head>

<body>
{include file='includes/global_top.tpl'}

<div class="global-content">

    <div class="C-left">

        {if $Form}
            <div id="Content">
                <h1>{$LANG.user.forgotTitle}</h1>
                <p>{$LANG.user.forgotText}</p>
                <form id="Forgot">
                    <div><input type="text" style="width: 200px;" placeholder="{$LANG.user.forgotEmail}" id="Email" name="Email" /></div>
                    <div style="padding-top: 10px;"><input type="submit" value="{$LANG.user.forgotSubmit}" class="F-button"></div>
                </form>
            </div>
            <script type="text/javascript">{literal}
                $('#Forgot').submit(function() {
                    var data = {
                        'Action': 'Forgot',
                        'Email': $('#Email').val()
                    };
                    $.post('/accounts/', data, function(result) {
                        if (result['message']) {
                            alert (result['message']);
                            $('#Email').focus();
                        } else {
                            if (result['elements']) {
                                $.each(result['elements'], function(i, v) {
                                    $(i).html(v);
                                });
                            }
                        }
                    }, 'json');
                    return false;
                });
            {/literal}</script>
        {else}
            <div id="Content">
                <form id="ChangePassword">
                    <input type="hidden" id="Code" value="{$Code}"/>
                    <label class="F-label" style="text-align: left;">{$LANG.user.forgotPasswordTitle}: <br/><input type="password" style="width: 200px;" placeholder="{$LANG.user.forgotPassword}" id="Password" /></label>
                    <div style="padding-top: 10px;"><input type="submit" value="{$LANG.user.forgotSubmit}" class="F-button"></div>
                </form>
            </div>
            <script type="text/javascript">{literal}
                $('#ChangePassword').submit(function() {
                    var data = {
                        'Action': 'ChangePassword',
                        'Password': $('#Password').val(),
                        'Code': $('#Code').val()
                    };
                    $.post('/accounts/', data, function(result) {
                        if (result['message']) {
                            alert (result['message']);
                            $('#Password').focus();
                        } else {
                            if (result['elements']) {
                                $.each(result['elements'], function(i, v) {
                                    $(i).html(v);
                                });
                            }
                        }
                    }, 'json');
                    return false;
                });
            {/literal}</script>
        {/if}


    </div>

    <div class="C-right">

    </div>

    <div class="clear"></div>
</div>

{include file='includes/global_bottom.tpl'}
</body>
</html>
