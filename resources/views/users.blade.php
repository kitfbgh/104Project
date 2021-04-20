@extends('layouts.app')

@section('content')
<table class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th>使用者ID</th>
            <th>使用者名稱</th>
            <th>Email</th>
            @cannot('user')
            <th>動作</th>
            @endcan
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            @cannot('user')
            <td>
                <form method="post" action="{{ route('users.delete', $user->id) }}">
                    @csrf
                    @method('DELETE')
                    <div class="form-group">
                        <input type="submit" class="btn btn-danger " value="刪除">
                    </div>
                </form>
            </td>
            @endcan
        </tr>
        @endforeach
    </tbody>
</table>
@endsection