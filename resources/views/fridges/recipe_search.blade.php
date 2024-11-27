<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>冷蔵庫活用アプリ - レシピ検索結果</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <h1>冷蔵庫の食材を使ったレシピ検索結果</h1>

        @if($recipes->isEmpty())
            <p>該当するレシピはありません。</p>
        @else
            <ul>
                @foreach($recipes as $recipe)
                    <li>
                        <a href="{{ route('recipe.show', $recipe->id) }}">
                            {{ $recipe->recipe_name }}
                        </a>
                        - 作成日：{{ $recipe->created_at->format('Y-m-d') }}
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="footer">
            <a href="{{ route('fridge.index') }}">食材の登録に戻る</a>
        </div>
    </body>
</html>