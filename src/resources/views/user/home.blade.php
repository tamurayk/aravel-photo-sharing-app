@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Dashboard</div>

          <div class="card-body">
            @if (session('status'))
              <div class="alert alert-success" role="alert">
                {{ session('status') }}
              </div>
            @endif
            You are logged in!
            <div>
              <ul>
                <li>
                  <a href="{{ route('post.create') }}">add new post</a>
                </li>
                <li>
                  {{-- TODO: ログイン中ユーザーの user_profiles.name を取得 --}}
                  <a href="{{ route('post.index', ['userName' => 'tamurayk']) }}">your post list</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
