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
        <form action="{{ route('recipe.store') }}" method="POST" id="create">
            @csrf
            <div class="form-group">
                <label for="recipe_name">レシピ名:</label>
                <input type="text" class="form-control" id="recipe_name" name="recipe[recipe_name]" required>
            </div>

            <!-- 食材の選択 -->
            <div class="form-group">
                <label for="contents_list">材料:</label>
                <select id="contents_list" name="contents_list[]" class="form-control" required>
                    <option value="">選択してください</option>
                    @foreach ($contents as $content)
                        <option value="{{ $content->content_id }}">{{ $content->name }}</option>
                    @endforeach
                </select>
                <input type="text" id="quantity" class="form-control" placeholder="分量を入力" required>
                <button type="button" id="add-content" class="btn btn-secondary">材料に追加</button>
            </div>

            <div class="form-group"> 
                <label for="seasonings_list">調味料:</label>
                <select id="seasonings_list" name="seasonings_list[]" class="form-control" required>
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
                <ul id="listContent"></ul>
            </div>
            
            <!-- 追加された調味料リスト -->
            <div id="added-seasonings">
                <h3>追加した調味料</h3>
                <ul id="listSeasoning"></ul>
            </div>

            <div class="form-group">
                <label for="recipe_step">レシピ手順</label>
                <textarea class="form-control" id="recipe_step" name="recipe[recipe_step]" rows="5" required></textarea>
            </div>

            <div class="form-group">
                <label for="allergies">アレルギー:</label>
                <select id="allergies" name="allergies[]" class="form-control" multiple>
                    <option value="">選択してください</option>
                    @foreach ($allergies as $allergy)
                        <option value="{{ $allergy->allergy_id }}">{{ $allergy->allergy_name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary">レシピを登録</button>

            <!--<input type="hidden" id="contents" name="contents[]" value="">-->
            <!--<input type="hidden" id="seasonings" name="seasonings[]" value="">-->
            <!--<input type="hidden" id="quantities" name="quantities[]" value="">-->
            <!--<input type="hidden" id="seasoning_quantities" name="seasoning_quantities[]" value="">-->

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
                let addedContents = [];
                // 追加された調味料を格納する配列
                let addedSeasonings = [];

                // 食材を追加するボタンのクリックイベント
                $('#add-content').click(function() { // #add-contentはボタンに紐づく
                    let contentId = $('#contents_list').val();
                    let quantity = $('#quantity').val();
                    let contentName = $('#contents_list option:selected').text();
                    let last_contentId = $('input[name="contents[]"]').val();
                    let last_quantity = $('input[name="quantities[]"]').val();
                    let new_contentId = [];
                    let new_quantity = [];
                    new_contentId.push(contentId);
                    new_contentId.push(last_contentId);
                    new_quantity.push(quantity);
                    new_quantity.push(last_quantity);
                    const form = document.getElementById('create');
                    const input = document.createElement('input');
                    input.setAttribute('type', 'hidden');
                    input.setAttribute('name', 'contents[]');
                    input.setAttribute('value', contentId);
                    form.appendChild(input);
                    const inputContent = document.createElement('input');
                    inputContent.setAttribute('type', 'hidden');
                    inputContent.setAttribute('name', 'quantities[]');
                    inputContent.setAttribute('value', quantity);
                    form.appendChild(inputContent);
                    

                    if (contentId && quantity) {
                        addedContents.push({ contentId: contentId, contentName: contentName, quantity: quantity });
                        updateContentList();
                        $('#contents_list').val('');
                        $('#quantity').val('');
                        // $('input[name="contents[]"]').val(new_contentId);
                        // $('input[name="quantities[]"]').val(new_quantity);
                    } else {
                        alert('食材と分量を選択してください');
                    }

                });

                // 調味料を追加するボタンのクリックイベント
                $('#add-seasoning').click(function() {
                    let seasoningId = $('#seasonings_list').val();
                    let seasoningQuantity = $('#seasoning-quantity').val();
                    let seasoningName = $('#seasonings_list option:selected').text();
                    let last_seasoningId = $('input[name="seasonings[]"]').val();
                    let last_seasoningQuantity = $('input[name="seasoning_quantities[]"]').val();
                    let new_seasoningId = [];
                    let new_seasoningQuantity = [];
                    new_seasoningId.push(seasoningId);
                    new_seasoningId.push(last_seasoningId);
                    new_seasoningQuantity.push(seasoningQuantity);
                    new_seasoningQuantity.push(last_seasoningQuantity);
                    const form = document.getElementById('create');
                    const input = document.createElement('input');
                    input.setAttribute('type', 'hidden');
                    input.setAttribute('name', 'seasonings[]');
                    input.setAttribute('value', seasoningId);
                    form.appendChild(input);
                    const inputSeasoning = document.createElement('input');
                    inputSeasoning.setAttribute('type', 'hidden');
                    inputSeasoning.setAttribute('name', 'seasoning_quantities[]');
                    inputSeasoning.setAttribute('value', seasoningQuantity);
                    form.appendChild(inputSeasoning);

                    if (seasoningId && seasoningQuantity) {
                        addedSeasonings.push({ seasoningId: seasoningId, seasoningName: seasoningName, seasoningQuantity: seasoningQuantity });
                        updateSeasoningList();
                        updateRequiredAttributes(); // 調味料追加後にrequiredを更新
                        $('#seasonings_list').val('');
                        $('#seasoning-quantity').val('');
                        // $('input[name="seasonings[]"]').val(new_seasoningId);
                        // $('input[name="seasoning_quantities[]"]').val(new_seasoningQuantity);
                    } else {
                        alert('調味料と分量を選択してください');
                    }
                });

                // 食材リストの更新
                function updateContentList() {
                    $('#listContent').empty();
                    addedContents.forEach(function(content, index) {
                        $('#listContent').append(
                            `<li>${content.contentName} - ${content.quantity} <button type="button" class="remove-btn" data-index="${index}" data-type="content">削除</button></li>`
                        );
                    });
                }

                // 調味料リストの更新
                function updateSeasoningList() {
                    $('#listSeasoning').empty();
                    addedSeasonings.forEach(function(seasoning, index) {
                        $('#listSeasoning').append(
                            `<li>${seasoning.seasoningName} - ${seasoning.seasoningQuantity} <button type="button" class="remove-btn" data-index="${index}" data-type="seasoning">削除</button></li>`
                        );
                    });
                }

                // 食材または調味料リストから削除
                $('#listContent, #listSeasoning').on('click', '.remove-btn', function() {
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
                        $('#contents_list').removeAttr('required'); // 食材があるときはrequiredを外す
                        $('#quantity').removeAttr('required'); // 食材分量があるときはrequiredを外す
                    } else {
                        $('#contents_list').attr('required', true); // 食材がないときはrequiredを設定
                        $('#quantity').attr('required', true); // 食材分量がないときはrequiredを設定
                    }

                    // 調味料が追加されていない場合はrequiredを設定
                    if (addedSeasonings.length > 0) {
                        $('#seasonings_list').removeAttr('required'); // 調味料があるときはrequiredを外す
                        $('#seasoning-quantity').removeAttr('required'); // 調味料分量があるときはrequiredを外す
                    } else {
                        $('#seasonings_list').attr('required', true); // 調味料がないときはrequiredを設定
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

                });
            });
        </script>

    </body>
</html>