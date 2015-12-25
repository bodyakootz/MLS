<!DOCTYPE html>
<html>
<head>
    <title>Index</title>

    <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

    <style>
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            display: table;
            font-weight: 100;
            font-family: 'Lato';
        }

        .container {
            text-align: center;
            display: table-cell;
            vertical-align: middle;
        }

        .content {
            text-align: center;
            display: inline-block;
        }

        .title {
            font-size: 96px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="content">
        <div class="title">This is login</div>
        <div class='login_section'>
            {!! Form::open(['url' => l('logging')]) !!}
            {!! HTML::image('img/layout/warning.png', 'Объект под охраной', array('class'=>'admin_warning')) !!}
            <h2 class='admin_area'>Объект под охраной</h2>
            {!! Form::text('name', null, ['class'=>'form-control admin_input admin_login', 'placeholder'=>'Логин']) !!}
            {!! Form::password('password', ['class'=>'form-control admin_input admin_login', 'placeholder'=>'Пароль']) !!}
            {!! Form::submit('Войти', ['class'=>'form-control login_button_1']) !!}
            {!! Form::close() !!}
        </div>
    </div>
</div>
</body>
</html>
