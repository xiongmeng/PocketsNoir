<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>刷新会员卡等级</title>

    </head>
    <body>
    <form method="post">
        <p>手机号： <input name="mobile" value=""></p>

        {!! csrf_field() !!}

        <input type="submit" name="提交">
    </form>
    </body>
</html>
