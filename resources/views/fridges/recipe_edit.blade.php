<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>レシピ編集</title>
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <body>
        <h1>レシピを編集する</h1>
        <form action="{{ route('recipe.update', ['id' => $recipe->recipe_id]) }}" method="POST" id="edit">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="recipe_name">レシピ名:</label>
                <input type="text" class="form-control" id="recipe_name" name="recipe[recipe_name]" value="{{ old('recipe.recipe_name', $recipe->recipe_name) }}" required>
            </div>

            <!-- 食材の選択 -->
            <div class="form-group">
                <label for="contents_list">材料:</label>
                <select id="contents_list" name="contents_list[]" class="form-control" multiple required>
                    <option value="">選択してください</option>
                    @foreach ($contents as $content)
                        <option value="{{ $content->content_id }}" 
                            @if(in_array($content->content_id, $recipe->contents->pluck('content_id')->toArray())) selected @endif>
                            {{ $content->name }}
                        </option>
                    @endforeach
                </select>
                <input type="text" id="quantity" class="form-control" placeholder="分量を入力" required>
                <button type="button" id="add-content" class="btn btn-secondary">材料に追加</button>
            </div>

            <!-- 調味料の選択 -->
            <div class="form-group">
                <label for="seasonings_list">調味料:</label>
                <select id="seasonings_list" name="seasonings_list[]" class="form-control" multiple required>
                    <option value="">選択してください</option>
                    @foreach ($seasonings as $seasoning)
                        <option value="{{ $seasoning->seasoning_id }}"
                            @if(in_array($seasoning->seasoning_id, $recipe->seasonings->pluck('seasoning_id')->toArray())) selected @endif>
                            {{ $seasoning->seasoning_name }}
                        </option>
                    @endforeach
                </select>
                <input type="text" id="seasoning-quantity" class="form-control" placeholder="分量を入力" required>
                <button type="button" id="add-seasoning" class="btn btn-secondary">調味料に追加</button>
            </div>

            <!-- 追加された食材リスト -->
            <div id="added-contents">
                <h3>追加した食材</h3>
                <ul id="listContent">
                    @foreach ($recipe->contents as $content)
                        <li>{{ $content->name }} - {{ $content->pivot->quantity }} <button type="button" class="remove-btn" data-id="{{ $content->content_id }}">削除</button></li>
                    @endforeach
                </ul>
            </div>

            <!-- 追加された調味料リスト -->
            <div id="added-seasonings">
                <h3>追加した調味料</h3>
                <ul id="listSeasoning">
                    @foreach ($recipe->seasonings as $seasoning)
                        <li>{{ $seasoning->seasoning_name }} - {{ $seasoning->pivot->quantity }} <button type="button" class="remove-btn" data-id="{{ $seasoning->seasoning_id }}">削除</button></li>
                    @endforeach
                </ul>
            </div>

            <div class="form-group">
                <label for="recipe_step">レシピ手順</label>
                <textarea class="form-control" id="recipe_step" name="recipe[recipe_step]" rows="5" required>{{ old('recipe.recipe_step', $recipe->recipe_step) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">レシピを更新</button>

        </form>

        <div class="footer">
            <a href="/mypage/recipe_list">戻る</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <script>
            $(document).ready(function() {
    // 追加された食材を格納する配列
    let addedContents = @json($recipe->contents->pluck('content_id').toArray());
    let addedContentNames = @json($recipe->contents->pluck('name').toArray());
    let addedQuantities = @json($recipe->contents->pluck('pivot.quantity').toArray());

    // 追加された調味料を格納する配列
    let addedSeasonings = @json($recipe->seasonings->pluck('seasoning_id').toArray());
    let addedSeasoningNames = @json($recipe->seasonings->pluck('seasoning_name').toArray());
    let addedSeasoningQuantities = @json($recipe->seasonings->pluck('pivot.quantity').toArray());

    // 既存の食材と調味料をリストに表示
    updateContentList();
    updateSeasoningList();

    // 食材を追加するボタンのクリックイベント
    $('#add-content').click(function() {
        let contentId = $('#contents_list').val();
        let quantity = $('#quantity').val();
        let contentName = $('#contents_list option:selected').text();

        if (contentId && quantity) {
            addedContents.push(contentId);
            addedContentNames.push(contentName);
            addedQuantities.push(quantity);
            updateContentList();
            $('#contents_list').val('');
            $('#quantity').val('');
        } else {
            alert('食材と分量を選択してください');
        }
    });

    // 調味料を追加するボタンのクリックイベント
    $('#add-seasoning').click(function() {
        let seasoningId = $('#seasonings_list').val();
        let seasoningQuantity = $('#seasoning-quantity').val();
        let seasoningName = $('#seasonings_list option:selected').text();

        if (seasoningId && seasoningQuantity) {
            addedSeasonings.push(seasoningId);
            addedSeasoningNames.push(seasoningName);
            addedSeasoningQuantities.push(seasoningQuantity);
            updateSeasoningList();
            $('#seasonings_list').val('');
            $('#seasoning-quantity').val('');
        } else {
            alert('調味料と分量を選択してください');
        }
    });

    // 食材リストの更新
    function updateContentList() {
        $('#listContent').empty();
        addedContents.forEach(function(contentId, index) {
            $('#listContent').append(
                `<li>${addedContentNames[index]} - ${addedQuantities[index]} <button type="button" class="remove-btn" data-index="${index}" data-type="content">削除</button></li>`
            );
        });
    }

    // 調味料リストの更新
    function updateSeasoningList() {
        $('#listSeasoning').empty();
        addedSeasonings.forEach(function(seasoningId, index) {
            $('#listSeasoning').append(
                `<li>${addedSeasoningNames[index]} - ${addedSeasoningQuantities[index]} <button type="button" class="remove-btn" data-index="${index}" data-type="seasoning">削除</button></li>`
            );
        });
    }

    // 食材または調味料リストから削除
    $('#listContent, #listSeasoning').on('click', '.remove-btn', function() {
        let index = $(this).data('index');
        let type = $(this).data('type');

        if (type === 'content') {
            addedContents.splice(index, 1);
            addedContentNames.splice(index, 1);
            addedQuantities.splice(index, 1);
            updateContentList();
        } else if (type === 'seasoning') {
            addedSeasonings.splice(index, 1);
            addedSeasoningNames.splice(index, 1);
            addedSeasoningQuantities.splice(index, 1);
            updateSeasoningList();
        }
    });

    // フォーム送信時に追加した食材と調味料をhiddenフィールドにセット
    $('form').submit(function() {
        // 食材と調味料のhiddenフィールドに値をセット
        let contentIds = [];
        let seasoningIds = [];
        let quantities = [];
        let seasoningQuantities = [];

        addedContents.forEach(function(item){
            contentIds.push(item);
            quantities.push(addedQuantities[addedContents.indexOf(item)]);
        });

        addedSeasonings.forEach(function(item){
            seasoningIds.push(item);
            seasoningQuantities.push(addedSeasoningQuantities[addedSeasonings.indexOf(item)]);
        });

        // hiddenフィールドを追加して値を送信するロジックを追加
        // 例: $('input[name="contents[]"]').val(contentIds);
        // 例: $('input[name="quantities[]"]').val(quantities);
    });
});
        </script>
    </body>
</html>