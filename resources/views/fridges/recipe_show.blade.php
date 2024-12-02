<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>冷蔵庫活用アプリ</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <h1>レシピ詳細</h1>
        <h2>{{ $recipe->recipe_name }}</h2>
        <p>作成日：{{ $recipe->created_at->format('Y-m-d') }}</p>
        <h3>作り方</h3>
        <p>{!! nl2br(e($recipe->recipe_step)) !!}</p>

        <h3>材料</h3>
        @if($contents->isEmpty())
            <p>材料の情報がありません。</p>
        @else
            <ul>
                @foreach($contents as $content)
                    <li>{{ $content->name }} - {{ $content->pivot->quantity }}</li>
                @endforeach
            </ul>
        @endif

        <h3>必要な調味料</h3>
        @if($seasonings->isEmpty())
            <p>調味料の情報がありません。</p>
        @else
            <ul>
                @foreach($seasonings as $seasoning)
                    <li>{{ $seasoning->seasoning_name }} - {{ $seasoning->pivot->quantity }}</li>
                @endforeach
            </ul>
        @endif

        <div class="footer">
            <a href="{{ route('recipe.search') }}">検索結果に戻る</a>
        </div>
    </body>
</html>