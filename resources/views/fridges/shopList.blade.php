<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>買い物リスト</title>
</head>
<body>
    <h1>買い物リスト</h1>
    @if($shopListItems->isEmpty())
        <p>買い物リストにアイテムがありません。</p>
    @else
        <ul>
            @foreach($shopListItems as $item)
                <li>
                    {{ $item->item_name }} - {{ $item->quantity ?? '数量不明' }}
                    <!-- 削除ボタン -->
                    <form action="{{ route('shopList.delete', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('本当に削除しますか？')">削除</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
    <a href="{{ route('recipe.search') }}">検索結果に戻る</a>
    <div class="index">
        <a href="{{ route('fridge.index') }}">冷蔵庫の食材登録に戻る</a>
    </div>
</body>
</html>