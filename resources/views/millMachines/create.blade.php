@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="w-full lg:w-1/2 mx-auto bg-base-100 shadow-xl p-6">
        <form action="{{ route('millMachines.store') }}" id="uploadForm" method="POST">
            @csrf

            <div class="form-control">
                <label class="label" for="machine_number">
                    <span class="label-text">番号<span class="text-info">＊必須</span></span>
                </label>
                <input type="text" id="machine_number" name="machine_number" class="input input-bordered" required autofocus>
            </div>
            
            <div class="form-control">
                <label class="label" for="machine_name">
                    <span class="label-text">製粉機名<span class="text-info">＊必須</span></span>
                </label>
                <input type="text" id="machine_name" name="machine_name" class="input input-bordered" required autofocus>
            </div>

            <div class="form-control">
                <label class="label" for="description">
                    <span class="label-text">説明</span>
                </label>
                <textarea id="description" name="description" class="textarea textarea-bordered" rows="4"></textarea>
            </div>

            <div class="form-control mt-6">
                <button type="submit" class="btn btn-primary">製粉機登録</button>
            </div>
        </form>
    </div>
</div>
@endsection
