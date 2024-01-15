@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="w-full lg:w-1/2 mx-auto bg-base-100 shadow-xl p-6">
        <form action="{{ route('millMachines.update', $millMachine->id) }}" class="mb-4" method="POST">
            @csrf
            @method('PUT')

            <div class="form-control">
                <label class="label" for="machine_number">
                    <span class="label-text">番号</span>
                </label>
                <input type="text" id="machine_number" name="machine_number" value="{{ $millMachine->machine_number }}" class="input input-bordered bg-base-200" required readonly>
            </div>

            <div class="form-control">
                <label class="label" for="machine_name">
                    <span class="label-text">製粉機名<span class="text-accent">＊必須</span></span>
                </label>
                <input type="text" id="machine_name" name="machine_name" value="{{ $millMachine->machine_name }}" class="input input-bordered" required>
            </div>

            <div class="form-control">
                <label class="label" for="description">
                    <span class="label-text">説明</span>
                </label>
                <textarea id="description" name="description" class="textarea textarea-bordered" rows="4">{{ $millMachine->description }}</textarea>
            </div>

            <div class="form-control mt-6">
                <button type="submit" class="btn btn-primary">更新</button>
            </div>
        </form>

        <form action="{{ route('millMachines.destroy', $millMachine->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-error w-full">削除</button>
        </form>
    </div>
</div>
@endsection
