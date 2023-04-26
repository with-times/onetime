<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME') }}</title>
    @vite('resources/js/app.js')
</head>
<body>
<!--主布局-->
<div class="uk-container">
    <div class="uk-card uk-card-default uk-width-1-3 uk-position-center">
        <div class="uk-card-header">
            <div class="uk-grid-small" uk-grid>

                <div>
                    <h3 class="uk-card-title uk-margin-remove-bottom">邮箱验证</h3>
                </div>
            </div>
        </div>
        <div class="uk-card-body">

            <p>欢迎加入与时同行，请输入电子邮箱中的密钥如已验证请忽略：</p>
            <div class="uk-margin">
                @csrf
                <input class="uk-input" id="keys" type="text" placeholder="密钥" aria-label="Input">
            </div>
        </div>
        <div class="uk-card-footer">
            <button id="submit" class="uk-button uk-button-text">提交</button>
        </div>
    </div>
</div>


</body>
</html>
