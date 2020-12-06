@php
  /** @var \Illuminate\Pagination\LengthAwarePaginator $paginator */
  /** @var App\Models\Eloquents\User $user */
@endphp

@extends('layouts.admin')

@section('header')
  <div>
    <h1>
      <i class="fas fa-align-justify"></i> User
      <a class="btn btn-success float-right" onclick="alert('not yet implemented.')"><i class="fas fa-plus"></i> Create</a>
    </h1>
  </div>
@endsection

@section('content')
  <div class="col-sm-offset-2 col-sm-14">
    <div class="panel panel-default">
      <div class="panel-body">
        @include('common.errors')

        @if (count($paginator) > 0)
        <div class="panel panel-default">
          <div class="panel-body">
            <table class="table table-striped task-table">
              <tbody>
              <thead>
              <tr>
                <th class="text-center"><a href="{{ sort_uri(request(), 'id')}}">#</a></th>
                <th><a href="{{ sort_uri(request(), 'name')}}">name</a></th>
                <th><a href="{{ sort_uri(request(), 'email')}}">email</a></th>
                <th><a href="{{ sort_uri(request(), 'created_at')}}">created_at</a></th>
                <th><a href="{{ sort_uri(request(), 'updated_at')}}">updated_at</a></th>
                <th class="text-right">OPTIONS</th>
              </tr>
              </thead>
              @foreach ($paginator as $user)
                <tr>
                  <td class="table-text text-center">{{ $user->id }}</td>
                  <td class="table-text">{{ $user->name }}</td>
                  <td class="table-text">{{ $user->email }}</td>
                  <td class="table-text">{{ $user->created_at }}</td>
                  <td class="table-text">{{ $user->updated_at }}</td>
                  <td class="text-right">
                    <a class="btn btn-sm btn-primary" onclick="alert('not yet implemented.')">
                      <i class="fas fa-eye"></i> View
                    </a>
                    <a class="btn btn-sm btn-warning" onclick="alert('not yet implemented.')">
                      <i class="fas fa-edit"></i> Edit
                    </a>
                    <a class="btn btn-sm btn-danger" onclick="alert('not yet implemented.')">
                      <i class="fas fa-edit"></i> Delete
                    </a>
                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div>
          {{ $paginator->links() }}
        </div>
      @endif
      </div>
    </div>
  </div>
@endsection