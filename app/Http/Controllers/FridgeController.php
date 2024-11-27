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
        return view('fridges.recipe_list');
    }

    public function recipe_create()
    {
        // 空のレシピを作成（ただしDBには保存しない）
        $recipe = Recipe::create([
            'recipe_name' => '', //初期値は空
            'recupe_step' => '', //初期値は空
        ]);

        // 材料と調味料のデータを渡す
        $contents = Content::all();
        $seasonings = Seasoning::all(); 
        
        // 新規作成したrecipe_idをビューに渡す
        return view('fridges.recipe_create', compact('contents','seasonings'))->with('recipe_id', $recipe->recipe_id);
    }

    public function recipe_store(Request $request)
    {
        $request->validate([
            'recipe.recipe_name' => 'required|string|max:255', //レシピ名は必須で文字列
            'recipe.recipe_step' => 'required|string', //レシピ手順は必須で文字列
            'contents' => 'required|array', //材料は必須で配列
            'contents.*' => 'integer|exists:contents,content_id', // 材料の各要素は整数で、contentsテーブルに存在することを確認
            'quantities' => 'required|array',
            'quantities.*' => 'string', // 数量は文字列型としてバリデーション
            'seasonings' => 'required|array', 
            'seasonings.*' => 'integer|exists:seasonings,seasoning_id',
            'seasoning_quantities' => 'required|array',
            'seasoning_quantities.*' => 'string', // 数量は文字列型としてバリデーション
        ]);

        // 空のレシピ（recipe_id）を更新
        $recipe = Recipe::find($request->recipe_id);
        $recipe->update($request->input('recipe'));

        // 食材との関連をcontent_recipeテーブルに保存
        foreach ($request->input('contents') as $index => $contentId) {
            $quantity = $request->input('quantities')[$index] ?? null; // quantityを取得
            // 中間テーブルのモデルを使って、quantityを保存
            $recipe->contents()->attach($contentId, ['quantity' => $quantity]); // 中間テーブルに保存
        }

        // 調味料との関連をseasoning_recipeテーブルに保存
        foreach ($request->input('seasonings') as $index => $seasoningId) {
            $quantity = $request->input('seasoning_quantities')[$index] ?? null; // quantityを取得
            // 中間テーブルのモデルを使って、quantityを保存
            $recipe->seasonings()->attach($seasoningId, ['quantity' => $quantity]); // 中間テーブルに保存
        }

        return redirect()->route('recipe.create');
    }
}
