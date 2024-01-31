<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UsersController extends Controller
{
    public function index()
    {
        // ユーザ一覧をidの降順で取得
        $users = User::orderBy('id', 'desc')->paginate(10);
        
        // ユーザ一覧ビューでそれを表示
        return view('users.index', [
            'users' => $users,
            ]);
    }
    
    public function show($id)
    {
        // idの値でユーザを検索して取得
        $user = User::findOrFail($id);
        
        // ユーザ詳細ビューでそれを表示
        return view('users.show', [
            'user' => $user,
            ]);
    }
    
    public function edit($id)
    {
        $user = User::findOrFail($id);
        if (Auth::id() !==$user->id) {
            return view('users.show',[
                'user' => $user,
                ]);
        }
        return view('users.edit', ['user' => $user]);
    }
    
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        if (Auth::id() !==$user->id) {
            return redirect()->route('users.show', $user->id)->with('success', '自分のユーザー情報以外は修正できません。');
        }
        // dd($request);
        $request->validate([
        'name' => 'required|string|max:255',
        'first_name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        ''
        ]);
        
        $user = User::findOrFail($id);
        $user->update($request->all());
        
        return redirect()->route('users.show', $user->id)->with('success', 'ユーザー情報が更新されました。');
    }
    
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Auth::id() がユーザーの ID と等しい場合のみ削除を許可
    if (Auth::id() === $user->id) {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'ユーザーが削除されました。');
    }
    
    // それ以外の場合は、削除を拒否し、適切なメッセージと共にリダイレクト
    return back()->with('error', '他のユーザーの削除は許可されていません。');
    }
}
