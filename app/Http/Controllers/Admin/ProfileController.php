<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Profile;

use App\Profile_history;

use Carbon\Carbon;

class ProfileController extends Controller
{
  public function add(){
    return view('admin.profile.create');
  }
  public function create(Request $request){
    // Varidationを行う
    $this->validate($request, Profile::$rules);

    $profile = new Profile;
    $form = $request->all();


    // フォームから送信されてきた_tokenを削除する
    unset($form['_token']);
    
    // データベースに保存する
    $profile->fill($form);
    $profile->save();
    // admin/profile/createにリダイレクトする
    return redirect('admin/profile/create');
  }
  public function edit(Request $request){
    $profile = Profile::find($request->id);
    return view('admin.profile.edit', ['profile' => $profile]);
  }
  public function update(Request $request){
    // Varidationを行う
    $this->validate($request, Profile::$rules);
    
    // 更新対象のレコードをデータベースから取得
    $profile = Profile::find($request->id);
    
    // リクエストに入っているパラメータを連想配列として$formに取り出す
    $form = $request->all();
    
    // 不要なパラメータを削除する
    unset($form['_token']);
    
    // プロフィールモデルに更新された値を詰めて保存
    $profile->fill($form)->save();
    
    // 更新した結果を確認できるようにプロフィール編集画面に遷移
    $profile_history = new Profile_History;
    $profile_history->profile_id = $profile->id;
    $profile_history->edited_at = Carbon::now();
    $profile_history->save();

    return redirect('admin/profile/create');
  }
}

