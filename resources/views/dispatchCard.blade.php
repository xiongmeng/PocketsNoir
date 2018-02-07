<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>创建会员卡</title>

    </head>
    <body>
    <form method="post">
        <p>手机号： <input name="mobile" value=""></p>
        <p>来源渠道：<select name="channel">
            <?php foreach (\App\Vip::$ChannelCardMaps as $channel => $card){ ?>
                <option value="<?=$channel?>">
                    <?php
                        if($channel == '特殊渠道'){
                            echo $channel;
                        }else{
                            $name = \App\Vip::$GuanJiaPoCardMaps[$card];
                            echo "{$channel}（{$card}-{$name}）";
                        }
                    ?></option>
            <?php }?>
            </select>
        </p>

        <p>备注：<input name="comment" value=""></p>

        <p>
            特殊渠道：<select name="card">
                <option value=""></option>
                <?php foreach (\App\Vip::$GuanJiaPoCardMaps as $card => $name){ ?>
                <option value="<?=$card?>">
                    <?=$name?></option>
                <?php }?>
            </select>
        </p>

        {!! csrf_field() !!}

        <input type="submit" name="提交">
    </form>
    </body>
</html>
