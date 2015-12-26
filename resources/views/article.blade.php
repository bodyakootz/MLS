<!DOCTYPE html>
<html>
<head>
    <title>Laravel</title>

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
        <div class="title">This is article</div>
            <a href="{{l('article', [s($article->title), $article->article_id, $article->language])}}">{{$article->title}}</a>
            <p>{{$article->date}}</p>
            <div>{{print($article->body)}}</div>
            <p>{{$article->category}}</p>
            <p>{{$article->preview}}</p>
            <p>{{$article->language}}</p>
        <p>BREAK</p>
        @foreach($same_articles as $same_article)
            <a href="{{l('article', [s($same_article->title), $same_article->article_id, $same_article->language])}}">{{$same_article->title}}</a>
            <p>{{$same_article->date}}</p>
            <div>{{print($same_article->body)}}</div>
            <p>{{$same_article->category}}</p>
            <p>{{$same_article->preview}}</p>
            <p>{{$same_article->language}}</p>
        @endforeach
    </div>

</div>
</body>
</html>
