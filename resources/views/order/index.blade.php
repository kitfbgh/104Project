@extends('../layouts.app')

@section('content')
<!-- Alert User -->
@if (Session::has('delete'))
<div class="alert alert-danger">
    {{Session::get('delete')}}
</div>
@endif

<div>
<table class="table table-striped table-sort">
    <thead class="table-dark">
        <tr>
            <th class="order-by-desc">購買時間</th>
            <th>收貨人</th>
            <th>Email</th>
            <th>狀態</th>
            <th>付款方式</th>
            <th>留言</th>
            @cannot('user')
            <th>動作</th>
            @endcan
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
        <tr>
            <td>{{ $order->created_at }}</td>
            <td>{{ $order->billing_name }}</td>
            <td>{{ $order->billing_email }}</td>
            <td>{{ $order->status }}</td>
            <td>{{ $order->payment }}</td>
            <td>{{ $order->comment }}</td>
            @cannot('user')
            <td>
                <a type="button" class="btn btn-info" href="{{ route('orders.detail', $order->id) }}">詳情</a>
            </td>
            @endcan
        </tr>
        @endforeach
    </tbody>
</table>
{{ $orders->links("pagination::simple-bootstrap-4") }}
</div>
@endsection