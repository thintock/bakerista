@if (Auth::id() ==$user->id)
  <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">
    <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" width="18" height="18" color="#000000"><defs><style>.cls-6374f8d9b67f094e4896c676-1{fill:none;stroke:currentColor;stroke-miterlimit:10;}</style></defs><path class="cls-6374f8d9b67f094e4896c676-1" d="M7.23,20.59l-4.78,1,1-4.78L17.89,2.29A2.69,2.69,0,0,1,19.8,1.5h0a2.7,2.7,0,0,1,2.7,2.7h0a2.69,2.69,0,0,1-.79,1.91Z"></path><line class="cls-6374f8d9b67f094e4896c676-1" x1="0.55" y1="22.5" x2="23.45" y2="22.5"></line><line class="cls-6374f8d9b67f094e4896c676-1" x1="19.64" y1="8.18" x2="15.82" y2="4.36"></line></svg>
    </a>
  <form action="{{ route('users.destroy', $user->id) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-error" onclick="return confirm('本当に削除しますか？');">
      <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" width="18" height="18" color="#000000"><defs><style>.cls-6374f8d9b67f094e4896c66b-1{fill:none;stroke:currentColor;stroke-miterlimit:10;}</style></defs><path class="cls-6374f8d9b67f094e4896c66b-1" d="M16.88,22.5H7.12a1.9,1.9,0,0,1-1.9-1.8L4.36,5.32H19.64L18.78,20.7A1.9,1.9,0,0,1,16.88,22.5Z"></path><line class="cls-6374f8d9b67f094e4896c66b-1" x1="2.45" y1="5.32" x2="21.55" y2="5.32"></line><path class="cls-6374f8d9b67f094e4896c66b-1" d="M10.09,1.5h3.82a1.91,1.91,0,0,1,1.91,1.91V5.32a0,0,0,0,1,0,0H8.18a0,0,0,0,1,0,0V3.41A1.91,1.91,0,0,1,10.09,1.5Z"></path><line class="cls-6374f8d9b67f094e4896c66b-1" x1="12" y1="8.18" x2="12" y2="19.64"></line><line class="cls-6374f8d9b67f094e4896c66b-1" x1="15.82" y1="8.18" x2="15.82" y2="19.64"></line><line class="cls-6374f8d9b67f094e4896c66b-1" x1="8.18" y1="8.18" x2="8.18" y2="19.64"></line></svg>
    </button>
  </form>
@endif