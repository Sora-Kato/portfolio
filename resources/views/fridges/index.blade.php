<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>冷蔵庫活用アプリ</title>
        <!-- Fonts -->
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <link href="https://fonts.googleapis.com/css2?family=Kosugi&display=swap" rel="stylesheet">
    </head>
    <body>
        <h1>冷蔵庫の食材</h1>
        <div class='fridges'>
            <form action="{{ route('fridge.store') }}" method="POST">
                @csrf
                <label for="content_id"></label>
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
                <button class='delete' type="submit">削除</button>
            </form>

            <div class='allergy'>
            <!-- レシピ検索フォーム -->
            <form action="{{ route('recipe.search') }}" method="GET">
                <label for="allergies">アレルギーを選択:</label>                
                @isset($allergies)
                <select id="allergies" name="allergy_ids[]" multiple>
                    <option value="">アレルギーを選んでください</option>
                    @foreach($allergies as $allergy)
                        <option value="{{ $allergy->allergy_id }}">{{ $allergy->allergy_name }}</option>
                    @endforeach
                </select>
                @else
                    <p>アレルギー情報がありません</p>
                @endisset

                <button  class='search' type="submit">レシピを検索</button>
            </form>
            </div>

        </div>
        <div class="footer">
        <a href="{{ route('fridge.indexSeasoning') }}">調味料管理</a>
        <div class="shopList">
            <a href="{{ route('shopList.show') }}">買物リストを確認する</a>
        </div>
        <div class="mypage">
            <a href="{{ route('fridge.mypage') }}">マイページ</a>
        </div>
        <p>{{ Auth::user()->name }}</p>
        </div>
    </body>
</html>