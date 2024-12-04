<?php

namespace App\Http\Controllers;

use App\Models\Fridge;
use App\Models\Content;
use App\Models\Seasoning;
use App\Models\MySeasoning;
use App\Models\Recipe;
use App\Models\Allergy;
use App\Models\SeasoningRecipe;
use App\Models\ShopList;
use Illuminate\Http\Request;

class FridgeController extends Controller
{
    public function index()
    {
        $fridges = Fridge::all();
        $contents = Content::all();

        return view('fridges.index', compact('fridges','contents'));
    }

    public function indexSeasoning()
    {
        $seasonings = Seasoning::all();
        $mySeasonings = MySeasoning::all();

        return view('fridges.seasoning', compact('seasonings','mySeasonings'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'content_id' => 'required|integer|exists:contents,content_id',
        ]);

        Fridge::create([
            'fridge_id' => $request->content_id,
            'fridge_content' => Content::find($request->content_id)->name,
        ]);

        return redirect()->route('fridge.index');
    }

    public function storeSeasoning(Request $request) // 調味料追加メソッド
    {
        $request->validate([
            'seasoning_id' => 'required|integer|exists:seasonings,seasoning_id',
        ]);

        MySeasoning::create([
            'seasoning_id' => $request->seasoning_id,
        ]);

        return redirect()->route('fridge.indexSeasoning');
    }

    public function delete(Request $request)
    {
        //選択されたアイテムのIDを取得
        $ids = $request->input('ids');

        //IDが選択されている場合は削除
        if($ids){
            Fridge::destroy($ids);
        }

        //インデックスにリダイレクト
        return redirect()->route('fridge.index');
    }

    public function mypage()
    {
        return view('fridges.mypage');
    }

    public function recipe_list()
    {
        // Recipeモデルからすべてのレシピを取得
        $recipes = Recipe::all();

        // 取得したデータをビューに渡す
        return view('fridges.recipe_list' , compact('recipes'));
    }

    public function recipe_create()
    {
        // 空のレシピを作成せず、ビューに材料と調味料のデータを渡すだけ
        $contents = Content::all();
        $seasonings = Seasoning::all(); 
        $allergies = Allergy::all(); 
        
        // レシピIDをビューに渡さないようにする（保存するタイミングで生成する）
        return view('fridges.recipe_create', compact('contents','seasonings','allergies'));
    }

    public function recipe_store(Request $request)
    {
        $request->validate([
            'recipe.recipe_name' => 'required|string|max:255',
            'recipe.recipe_step' => 'required|string',
            'contents' => 'required|array|min:1', // 必須で1つ以上の材料が必要
            'contents.*' => 'exists:contents,content_id',
            'quantities' => 'required|array|size:' . count($request->input('contents', [])), // contents数とquantities数の一致を確認
            'quantities.*' => 'required|string',
            'seasonings' => 'required|array|min:1', // 必須で1つ以上の調味料が必要
            'seasonings.*' => 'exists:seasonings,seasoning_id',
            'seasoning_quantities' => 'required|array|size:' . count($request->input('seasonings', [])),
            'seasoning_quantities.*' => 'required|string',
            'allergies' => 'nullable|array',
            'allergies.*' => 'integer|exists:allergies,allergy_id',
        ]);

        // バリデーション後にレシピをデータベースに保存
        $recipe = Recipe::create($request->input('recipe'));        

        // 食材との関連をcontent_recipeテーブルに保存
        foreach ($request->input('contents') as $index => $contentId) {
            // 中間テーブルのモデルを使って、quantityを保存
            $recipe->contents()->attach($contentId, [
                'quantity' => $request->input('quantities')[$index]
            ]);
        }

        // 調味料との関連をseasoning_recipeテーブルに保存
        foreach ($request->input('seasonings') as $index => $seasoningId) {
            // 中間テーブルのモデルを使って、quantityを保存
            $recipe->seasonings()->attach($seasoningId, [
                'quantity' => $request->input('seasoning_quantities')[$index]
            ]); 
        }
        
        // アレルギーとの関連を保存
        if ($request->has('allergies')) {
            foreach ($request->input('allergies') as $allergyId) {
                $recipe->allergies()->attach($allergyId);
            }
        }

        return redirect()->route('recipe.create')
                         ->with('success', 'レシピが正常に保存されました！');
    }

    public function searchRecipes(Request $request)
    {
        // 冷蔵庫の中身（登録されている食材）を取得
        $fridgeContentIds = Fridge::pluck('fridge_id')->toArray();

        // アレルギーのリストを取得（アレルギーのモデルから）
        $allergies = Allergy::all();

        // アレルギーのIDを取得（選択されたアレルギー）
        $allergyIds = $request->input('allergy_ids', []);

        // 冷蔵庫の中身に関連するレシピを検索
        $recipes = Recipe::whereHas('contents', function ($query) use ($fridgeContentIds) {
            $query->whereIn('content_recipe.content_id', $fridgeContentIds); // 冷蔵庫の食材と一致するcontent_idを検索
        })
        ->get();

        // レシピをビューに渡す
        return view('fridges.recipe_search', compact('recipes'));
    }

    public function show($id)
    {
        //　指定されたレシピの取得（Content、Seasoningも一緒に）
        $recipe = Recipe::with(['contents', 'seasonings'])->findOrFail($id);

        // リレーションで食材と調味料も取得
        $contents = $recipe->contents;
        $seasonings = $recipe->seasonings;

        // ビューにデータを渡す
        return view('fridges.recipe_show', compact('recipe', 'contents', 'seasonings'));
    }

    public function edit($id)
    {
        $recipe = Recipe::findOrFail($id);
        $contents = Content::all();  // 食材リスト
        $seasonings = Seasoning::all();  // 調味料リスト
        $allergies = Allergy::all();  // アレルギーリスト

        // 編集用に必要なデータをビューに渡す
        return view('fridges.recipe_edit', compact('recipe', 'contents', 'seasonings', 'allergies'));
    }

        public function update(Request $request, $id)
    {
        $request->validate([
            'recipe.recipe_name' => 'required',
            'recipe.recipe_step' => 'required',
            'contents_list' => 'required|array',
            'seasonings_list' => 'required|array',
        ]);

        $recipe = Recipe::findOrFail($id);
        $recipe->update([
            'recipe_name' => $request->input('recipe.recipe_name'),
            'recipe_step' => $request->input('recipe.recipe_step'),
        ]);

        // 食材と調味料の更新
        $recipe->contents()->sync($request->input('contents_list'));
        $recipe->seasonings()->sync($request->input('seasonings_list'));

        return redirect()->route('recipe.list')->with('success', 'レシピが更新されました');
    }

        public function addToShopList($recipeId)
    {
        // レシピの詳細を取得
        $recipe = Recipe::with(['contents'])->findOrFail($recipeId);

        // 冷蔵庫にない食材を取得
        $fridgeContentIds = Fridge::pluck('fridge_id')->toArray();
        $missingContents = $recipe->contents->filter(function ($content) use ($fridgeContentIds) {
            return !in_array($content->content_id, $fridgeContentIds);
        });

        // 足りない食材を買い物リストに追加
        foreach ($missingContents as $content) {
            ShopList::create([
                'item_name' => $content->name,
                'quantity' => (string) $content->pivot->quantity,
            ]);
        }

        return redirect()->route('shopList.show')
                         ->with('success', '足りない材料が買い物リストに追加されました！');
    }

        public function showShopList()
    {
        $shopListItems = ShopList::all();
        return view('fridges.shopList', compact('shopListItems'));
    }

        public function deleteFromShopList($id)
    {
        // 指定されたIDの買い物リストアイテムを取得
        $item = ShopList::findOrFail($id);

        // アイテムを削除
        $item->delete();

        // 削除後に買い物リスト画面へリダイレクト
        return redirect()->route('shopList.show')
                         ->with('success', 'アイテムが削除されました！');
    }

}
