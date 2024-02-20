@extends('layouts.app')
@section('content')
    <div class="sm:grid sm:grid-cols-3 sm:gap-10">
        <aside class="mt-4">
            {{--ユーザ情報--}}
            <h2 class="mb-4">ユーザー情報の修正</h2>
            <div class="card w-96 bg-base-100 shadow-xl">
                <div class="card-body">
                    <form method="POST" action="{{ route('users.update', $user->id) }}" id="uploadForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-control my-4">
                            <h2>ユーザーID:&nbsp;{{ $user->id }}</h2>
                        </div>
                        <div class="form-control my-4">
                            <label for="name" class="label">
                                <span class="label-text">姓</span>
                            </label>
                            <input type="text" name="name" class="input input-bordered w-full" value="{{ $user->name }}">
                        </div>

                        <div class="form-control my-4">
                            <label for="first_name" class="label">
                                <span class="label-text">名</span>
                            </label>
                            <input type="text" name="first_name" class="input input-bordered w-full" value="{{ $user->first_name }}">
                        </div>

                        <div class="form-control my-4">
                            <label for="phone" class="label">
                                <span class="label-text">電話番号</span>
                            </label>
                            <input type="text" name="phone" class="input input-bordered w-full" value="{{ $user->phone }}">
                        </div>

                        <div class="form-control my-4">
                            <label for="email" class="label">
                                <span class="label-text">Eメールアドレス</span>
                            </label>
                            <input type="text" name="email" class="input input-bordered w-full" value="{{ $user->email }}">
                        </div>
                        
                        <div class="form-control my-4">
                            <label for="approved" class="label">
                                @if($user->is_approved)
                                    <span class="badge badge-secondary">承認済み</span>
                                @else
                                    <span class="badge badge-warning badge-outline">未承認</span><span class="text-info">※管理者に連絡してください。</span>
                                @endif
                            </label>
                        </div>

                        <div class="form-control my-4">
                            <button type="submit" class="btn btn-secondary">更新</button>
                        </div>
                    </form>
                </div>
            </div>
        </aside>
    </div>
@endsection