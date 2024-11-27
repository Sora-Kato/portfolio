<?php

namespace App\Http\Controllers;

use App\Models\Fridge;
use App\Models\Content;
use App\Models\Seasoning;
use App\Models\MySeasoning;
use App\Models\Recipe;
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
        
        // レシピIDをビューに渡さないようにする（保存するタイミングで生成する）
        return view('fridges.recipe_create', compact('contents','seasonings'));
    }

    public function recipe_store(Request $request)
    {
        $request->validate([
            'recipe.recipe_name' => 'required|string|max:255',
            'recipe.recipe_step' => 'required|string',
            'contents' => 'required|array|min:1', // 必須で1つ以上の材料が必要
            'contents.*' => 'integer|exists:contents,content_id',
            'quantities' => 'required|array|size:' . count($request->input('contents', [])), // contents数とquantities数の一致を確認
            'quantities.*' => 'required|string',
            'seasonings' => 'required|array|min:1', // 必須で1つ以上の調味料が必要
            'seasonings.*' => 'integer|exists:seasonings,seasoning_id',
            'seasoning_quantities' => 'required|array|size:' . count($request->input('seasonings', [])),
            'seasoning_quantities.*' => 'required|string',
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

        return redirect()->route('recipe.create')
                         ->with('success', 'レシピが正常に保存されました！');
    }

}
