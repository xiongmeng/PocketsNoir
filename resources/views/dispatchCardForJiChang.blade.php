<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>机场员工发卡</title>

    </head>
    <body>
    <form method="post" action="/dispatchCard">
        <p>手机号： <input name="mobile" value=""></p>
        <p>来源渠道：<select name="channel">
            <?php foreach (\App\Vip::$jiChangChannelCardMaps as $channel => $card){ ?>
                <option value="<?=$channel?>">
                    <?php
                        $name = \App\Vip::$GuanJiaPoCardMaps[$card];
                        echo "{$channel}（{$card}-{$name}）";
                    ?></option>
            <?php }?>
            </select>
        </p>
        <input type="hidden" name="card">
        <p>备注：<input name="comment" value=""></p>

        {!! csrf_field() !!}

        <input type="submit" name="提交">
    </form>
    </body>
</html>
