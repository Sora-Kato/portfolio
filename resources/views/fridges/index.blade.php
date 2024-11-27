<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>冷蔵庫活用アプリ</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <h1>冷蔵庫の食材</h1>
        <div class='fridges'>
            <form action="{{ route('fridge.store') }}" method="POST">
                @csrf
                <label for="content_id">食材を選択</label><br/>
                <select id="content_id" name="content_id">
                    <option value="">食材を選んでください</option>
                    @foreach($contents as $content)
                        <option value="{{ $content->content_id }}">{{ $content->name }}</option>
                    @endforeach
                </select>
                <button type="submit">追加</button>
            </form>

            <form action="{{ route('fridge.delete') }}" method="POST">
                @csrf
                @method('DELETE')
                <ul>
                    @foreach($fridges as $fridge)
                        @php
                            $content = $contents->firstwhere('content_id', $fridge->fridge_id);
                        @endphp
                        <li>
                            {{ $content ? $content->name : '不明な食材' }}
                            <input type="checkbox" name="ids[]" value="{{ $fridge->fridge_id }}">
                        </li>
                    @endforeach
                </ul>
                <button type="submit">削除</button>
            </form>

            <!-- レシピ検索フォーム -->
            <form action="{{ route('recipe.search') }}" method="GET">
                <button type="submit">冷蔵庫の食材でレシピを検索</button>
            </form>

        </div>
        <a href="{{ route('fridge.indexSeasoning') }}">調味料管理</a>
        <div class="mypage">
            <a href="{{ route('fridge.mypage') }}">マイページ</a>
        </div>
    </body>
</html>