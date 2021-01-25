@php
  /** @var \Illuminate\Pagination\LengthAwarePaginator $paginator */
  /** @var bool $isMine */
  /** @var App\Models\Eloquents\Post $post */
@endphp

@extends('layouts.app')

@section('content')

  {{-- TODO: ブレードを自分の投稿一覧と、他人の投稿一覧で分けたほうがいいかも? --}}
  @if ($isMine)
    <a href="{{ route('post.create') }}">add new post</a>
  @endif

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
