@extends('layouts.app')

@section('content')
  {{-- React Component: src/resources/js/components/Test --}}
  <div id="test"></div>

  <form {{ route('post.store') }} method="post" enctype='multipart/form-data'>
    {{ csrf_field() }}
    <p>
      <label for="caption">Caption:</label>
      <input type="text" id="caption" name="caption">
    </p>

    <p>
      <input type="file" name="image">
    </p>

    <p>
      <input type="submit" value="シェア">
    </p>
  </form>
@endsection
