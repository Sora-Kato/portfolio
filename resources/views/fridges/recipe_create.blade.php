<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>冷蔵庫活用アプリ</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- jQuery -->
    </head>
    <body>
        <h1>レシピを作成する</h1>
        <form action="{{ route('recipe.store') }}" method="POST">
            @csrf
            <input type="hidden" name="recipe_id" value="{{ $recipe_id }}"> <!-- 空のレシピIDを作成 -->

            <input type="hidden" name="contents[]">
            <input type="hidden" name="seasonings[]">
            <input type="hidden" name="quantities[]">
            <input type="hidden" name="seasoning_quantities[]">

            <div class="form-group">
                <label for="recipe_name">レシピ名:</label>
                <input type="text" class="form-control" id="recipe_name" name="recipe[recipe_name]" required>
            </div>

            <!-- 食材の選択 -->
            <div class="form-group">
                <label for="contents">材料:</label>
                <select id="contents" name="contents[]" class="form-control" required>
                    <option value="">選択してください</option>
                    @foreach ($contents as $content)
                        <option value="{{ $content->content_id }}">{{ $content->name }}</option>
                    @endforeach
                </select>
                <input type="text" id="quantity" class="form-control" placeholder="分量を入力" required>
                <button type="button" id="add-content" class="btn btn-secondary">材料に追加</button>
            </div>

            <div class="form-group"> 
                <label for="seasonings">調味料:</label>
                <select id="seasonings" name="seasonings[]" class="form-control" required>
                    <option value="">選択してください</option>
                    @foreach ($seasonings as $seasoning)
                        <option value="{{ $seasoning->seasoning_id }}">{{ $seasoning->seasoning_name }}</option>
                    @endforeach
                </select>
                <input type="text" id="seasoning-quantity" class="form-control" placeholder="分量を入力" required>
                <button type="button" id="add-seasoning" class="btn btn-secondary">調味料に追加</button>
            </div>

            <!-- 追加された食材リスト -->
            <div id="added-contents">
                <h3>追加した食材</h3>
                <ul id="content-list"></ul>
            </div>
            
            <!-- 追加された調味料リスト -->
            <div id="added-seasonings">
                <h3>追加した調味料</h3>
                <ul id="seasoning-list"></ul>
            </div>

            <div class="form-group">
                <label for="recipe_step">レシピ手順</label>
                <textarea class="form-control" id="recipe_step" name="recipe[recipe_step]" rows="5" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">レシピを登録</button>
            </form>
        <div class="footer">
            <a href="/mypage/recipe_list">戻る</a>
        </div>

        <script>
            $(document).ready(function() {
                // 追加された食材を格納する配列
                let addedContents = [];
                // 追加された調味料を格納する配列
                let addedSeasonings = [];

                // 食材を追加するボタンのクリックイベント
                $('#add-content').click(function() {
                    let contentId = $('#contents').val();
                    let quantity = $('#quantity').val();
                    let contentName = $('#contents option:selected').text();

                    if (contentId && quantity) {
                        addedContents.push({ contentId: contentId, contentName: contentName, quantity: quantity });
                        updateContentList();
                        $('#contents').val('');
                        $('#quantity').val('');
                    } else {
                        alert('食材と分量を選択してください');
                    }
                });

                // 調味料を追加するボタンのクリックイベント
                $('#add-seasoning').click(function() {
                    let seasoningId = $('#seasonings').val();
                    let seasoningQuantity = $('#seasoning-quantity').val();
                    let seasoningName = $('#seasonings option:selected').text();

                    if (seasoningId && seasoningQuantity) {
                        addedSeasonings.push({ seasoningId: seasoningId, seasoningName: seasoningName, seasoningQuantity: seasoningQuantity });
                        updateSeasoningList();
                        updateRequiredAttributes(); // 調味料追加後にrequiredを更新
                        $('#seasonings').val('');
                        $('#seasoning-quantity').val('');
                    } else {
                        alert('調味料と分量を選択してください');
                    }
                });

                // 食材リストの更新
                function updateContentList() {
                    $('#content-list').empty();
                    addedContents.forEach(function(content, index) {
                        $('#content-list').append(
                            `<li>${content.contentName} - ${content.quantity} <button type="button" class="remove-btn" data-index="${index}" data-type="content">削除</button></li>`
                        );
                    });
                }

                // 調味料リストの更新
                function updateSeasoningList() {
                    $('#seasoning-list').empty();
                    addedSeasonings.forEach(function(seasoning, index) {
                        $('#seasoning-list').append(
                            `<li>${seasoning.seasoningName} - ${seasoning.seasoningQuantity} <button type="button" class="remove-btn" data-index="${index}" data-type="seasoning">削除</button></li>`
                        );
                    });
                }

                // 食材または調味料リストから削除
                $('#content-list, #seasoning-list').on('click', '.remove-btn', function() {
                    let index = $(this).data('index');
                    let type = $(this).data('type');

                    if (type === 'content') {
                        addedContents.splice(index, 1);
                        updateContentList();
                    } else if (type === 'seasoning') {
                        addedSeasonings.splice(index, 1);
                        updateSeasoningList();
                    }

                    updateRequiredAttributes(); // 削除時にrequired属性の更新
                });

                // 必要な場合のみrequired属性を設定
                function updateRequiredAttributes() {
                    // 食材が追加されていない場合はrequiredを設定
                    if (addedContents.length > 0) {
                        $('#contents').removeAttr('required'); // 食材があるときはrequiredを外す
                        $('#quantity').removeAttr('required'); // 食材分量があるときはrequiredを外す
                    } else {
                        $('#contents').attr('required', true); // 食材がないときはrequiredを設定
                        $('#quantity').attr('required', true); // 食材分量がないときはrequiredを設定
                    }

                    // 調味料が追加されていない場合はrequiredを設定
                    if (addedSeasonings.length > 0) {
                        $('#seasonings').removeAttr('required'); // 調味料があるときはrequiredを外す
                        $('#seasoning-quantity').removeAttr('required'); // 調味料分量があるときはrequiredを外す
                    } else {
                        $('#seasonings').attr('required', true); // 調味料がないときはrequiredを設定
                        $('#seasoning-quantity').attr('required', true); // 調味料分量がないときはrequiredを設定
                    }
                }

                // フォーム送信時に追加した食材と調味料をhiddenフィールドにセット
                $('form').submit(function() {
                    // 最初にhiddenフィールドを用意しておく
                    let contentIds = [];
                    let seasoningIds = [];
                    let quantities = [];
                    let seasoningQuantities = [];

                    // 配列に追加された食材・調味料を格納
                    addedContents.forEach(function(item){
                        contentIds.push(item.contentId);
                        quantities.push(item.quantity);
                    });
                    
                    addedSeasonings.forEach(function(item){
                        seasoningIds.push(item.seasoningId);
                        seasoningQuantities.push(item.seasoningQuantity);
                    });

                    // hiddenフィールドに値を設定
                    $('input[name="contents[]"]').val(contentIds.join(','));
                    $('input[name="seasonings[]"]').val(seasoningIds.join(','));
                    $('input[name="quantities[]"]').val(quantities.join(','));
                    $('input[name="seasoning_quantities[]"]').val(seasoningQuantities.join(','));
                });
            });
        </script>

    </body>
</html>