@php
  /** @var \Illuminate\Pagination\LengthAwarePaginator $paginator */
  /** @var App\Models\Eloquents\Post $post */
@endphp

@extends('layouts.app')

@section('content')
  {{-- TODO: URL中の userName = ログイン中ユーザーの user_profiles.name の場合のみ新規投稿リンクを表示 --}}
  {{-- TODO: ↑ ブレードを分けたほうがいいかも? --}}
  {{--<a href="{{ route('post.create') }}">add new post</a>--}}

  @if (count($paginator) > 0)
    <div>
      @foreach ($paginator as $post)
        <div>
          {{ $post->created_at }}

          {{-- note: nginx + php-fpm の場合は、nginxコンテナに ./src/storage/app/public:/srv/public/storage とマウントする必要がある --}}
          <img src="{{ asset(sprintf('storage/posts/%s/%s', $post->user_id, $post->image)) }}">

          {{ $post->caption }}
        </div>
      @endforeach

    </div>
    <div>
      {{ $paginator->links() }}
    </div>
    @endif

@endsection
