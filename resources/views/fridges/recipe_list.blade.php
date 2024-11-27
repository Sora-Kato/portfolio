<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>冷蔵庫活用アプリ</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <h1>作成したレシピ</h1>

        <div class="myRecipe">
            <ul>
                @foreach($recipes as $recipe)
                    <li>{{ $recipe->recipe_name }} - 作成日： {{ $recipe->created_at->format('Y-m-d') }}</li>
                @endforeach
            </ul>
        </div>

        <div class="create">
            <a href="{{ route('recipe.create') }}">レシピを作成する</a>
        </div>
        <form>

        </form>
        <div class="footer">
            <a href="/mypage">戻る</a>
        </div>
    </body>
</html>