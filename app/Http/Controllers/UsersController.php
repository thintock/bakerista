<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
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
        
        $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'first_name' => 'required|string|max:255',
        'phone' => 'nullable|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        ]);
        
        $user = User::findOrFail($id);
        $user->update($validatedData);
        
        return redirect()->route('users.show', $user->id)->with('success', 'ユーザー情報が更新されました。');
    }
    
    public function destroy($id)
    {
        // 管理者（ユーザーIDが1）または自分自身のIDの場合のみ削除を許可
        if (Auth::id() === 1 || Auth::id() == $id) {
            try {
                $user = User::findOrFail($id);
                $user->delete();
                return redirect()->route('users.index')->with('success', 'ユーザーが削除されました。');
            } catch (QueryException $e) {
                // 外部キー制約違反などのデータベース関連のエラーをキャッチ
                return back()->with('error', 'このユーザーは他のデータに関連付けられており、削除できません。削除するためには、関連するデータを削除してください。');
            }
        }
    
        // それ以外の場合は、削除を拒否し、適切なメッセージと共にリダイレクト
        return back()->with('error', '他のユーザーの削除は許可されていません。');
    }
    
    // 管理者によるユーザーの承認機能
    public function manage()
    {
        if (Auth::id() !== 1) {
            return back()->with('error','この操作を実行する権限がありません。');
        }
        $users = User::orderBy('name','asc')->paginate(15);
        return view('users.manage', compact('users'));
    }
    
    // 管理者によるユーザーの承認機能のupdate
    public function updateStatus(Request $request, $id)
    {
        if (Auth::id() !== 1) {
            return redirect()->back()->with('error', 'この操作を実行する権限がありません。');
        }
    
        $user = User::findOrFail($id);
        $user->is_approved = $request->has('is_approved');
        $user->save();
    
        return redirect()->route('users.manage')->with('success', 'ユーザーの承認ステータスが更新されました。');
    }

}
