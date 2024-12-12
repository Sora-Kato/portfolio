<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>冷蔵庫活用アプリ</title>
        <!-- Fonts -->
        <link rel="stylesheet" href="{{ asset('css/seasoning.css') }}">
        <link href="https://fonts.googleapis.com/css2?family=Kosugi&display=swap" rel="stylesheet">
    </head>
    <body>
        <h1>調味料の管理</h1>
        <div class='seasonings'>
            <form action="{{ route('fridge.storeSeasoning') }}" method="POST">
                @csrf
                <label for="seasoning_id">調味料を選択</label><br/>
                <select id="seasoning_id" name="seasoning_id">
                    <option value="">調味料を選んでください</option>
                    @foreach($seasonings as $seasoning)
                        <option value="{{ $seasoning->seasoning_id }}">{{ $seasoning->seasoning_name }}</option>
                    @endforeach
                </select>
                <button type="submit">追加</button>
            </form>

            <form action="{{ route('fridge.delete') }}" method="POST">
                @csrf
                @method('DELETE')
                <ul>
                    @foreach($mySeasonings as $mySeasoning)
                        @php
                            $seasoning = $seasonings->firstwhere('seasoning_id', $mySeasoning->mySeasoning_id);
                        @endphp
                        <li>
                            {{ $seasoning ? $seasoning->seasoning_name : '不明な食材' }}
                            <input type="checkbox" name="ids[]" value="{{ $mySeasoning->mySeasoning_id }}">
                        </li>
                    @endforeach
                </ul>
                <button type="submit">削除</button>
            </form>
        </div>
        <div class="footer">
            <a href="/">戻る</a>
        </div>
    </body>
</html>