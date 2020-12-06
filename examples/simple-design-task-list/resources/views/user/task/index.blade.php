@php
  /** @var \Illuminate\Pagination\LengthAwarePaginator $paginator */
  /** @var App\Models\Eloquents\Task $task */
@endphp

@extends('layouts.app')

@section('header')
  <div>
    <h1>
      <i class="fas fa-align-justify"></i> Task
      {{--<a class="btn btn-success float-right" onclick="alert('not yet implemented.')"><i class="fas fa-plus"></i> Create</a>--}}
    </h1>
  </div>
@endsection


@section('content')
  <div class="container">
    <div class="col-sm-offset-2 col-sm-14">
      <div class="panel panel-default">
        <div class="panel-body">
          <!-- Display Validation Errors -->
        @include('common.errors')

        <!-- Search Form -->
          <div class="panel panel-default">
            <div class="panel-heading">
              <div class="panel-title">Search Form</div>
            </div>
            <div class="panel-body">
              <form method="GET" action="{{ route('task.index') }}" class="form-horizontal">
                <div class="form-group">
                  <label for="name" class="control-label col-sm-2">Name</label>
                  <div class="col-sm-4">
                    <input type="text" name="name" id="name" class="form-control input-sm" value="{{request()->get('name')}}"/>
                  </div>
                </div>
                <div class="text-center">
                  <button type="submit" value="検索" class="btn btn-primary">Search</button>
                  <button type="reset" value="クリア" class="btn ">Clear</button>
                </div>
              </form>
            </div>
          </div>

        <!-- New Task Form -->
          <form action="{{ url('task') }}" method="POST" class="form-horizontal">
          {{ csrf_field() }}

          <!-- Task Name -->
            <div class="form-group">
              <div class="panel-heading">
                <div class="panel-title">New Task</div>
              </div>
              <label for="task-name" class="col-sm-3 control-label">Task</label>

              <div class="col-sm-6">
                <input type="text" name="name" id="task-name" class="form-control" value="{{ old('task') }}">
              </div>
            </div>

            <!-- Add Task Button -->
            <div class="form-group">
              <div class="col-sm-offset-3 col-sm-6">
                <button type="submit" class="btn btn-success">
                  <i class="fa fa-btn fa-plus"></i>Add
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      @if (count($paginator) > 0)
        <div class="panel panel-default">
          <div class="panel-body">
            <table class="table table-striped task-table">
              <tbody>
              <thead>
              <tr>
                <th class="text-center"><a href="{{ sort_uri(request(), 'id')}}">#</a></th>
                <th><a href="{{ sort_uri(request(), 'name')}}">name</a></th>
                <th><a href="{{ sort_uri(request(), 'created_at')}}">created_at</a></th>
                <th class="text-right">OPTIONS</th>
              </tr>
              </thead>
              @foreach ($paginator as $task)
                <tr>
                  <td class="table-text text-center"><div>{{ $task->id }}</div></td>
                  <td class="table-text"><div>{{ $task->name }}</div></td>
                  <td class="table-text"><div>{{ $task->created_at }}</div></td>
                  <td class="text-right">
                    <a class="btn btn-sm btn-primary" onclick="alert('not yet implemented.')">
                      <i class="fas fa-eye"></i> View
                    </a>
                    <a class="btn btn-sm btn-warning" onclick="alert('not yet implemented.')">
                      <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="{{ route('task.destroy', ['task' => $task->id]) }}" method="POST" style="display: inline;"
                          onsubmit="return confirm('Delete? Are you sure?');">
                      {{ csrf_field() }}
                      {{ method_field('DELETE') }}

                      <button type="submit" id="delete-task-{{ $task->id }}" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i> Delete
                      </button>
                    </form>
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
@endsection