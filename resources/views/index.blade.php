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
        <div class="title">This is index</div>
        @foreach($articles as $article)
            <p>{{$article->title}}</p>
            <p>{{$article->date}}</p>
            <p>{{$article->body}}</p>
            <p>{{$article->category}}</p>
            <p>{{$article->preview}}</p>
            <p>{{$article->language}}</p>
        @endforeach
    </div>
</div>
</body>
</html>
