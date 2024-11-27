<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>冷蔵庫活用アプリ</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <h1>マイページ</h1>
        {{-- <a href="{{ route('fridge.mypage') }}">アレルギー登録</a> --}}
        <a href="{{ route('recipe.list') }}">レシピ一覧</a>
        <div class="footer">
            <a href="/">戻る</a>
        </div>
    </body>
</html>