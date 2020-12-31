@extends('layouts.app')

@section('content')
  <p>user.post.index</p>
  {{-- TODO: URL中の userName = ログイン中ユーザーの user_profiles.name の場合のみ新規投稿リンクを表示 --}}
  {{--<a href="{{ route('post.create') }}">add new post</a>--}}
@endsection
