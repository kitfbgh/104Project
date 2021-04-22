@extends('../user.index')

@section('content')
<table class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th>購買時間</th>
            <th>收貨人</th>
            <th>Email</th>
            <th>狀態</th>
            <th>付款方式</th>
            <th>留言</th>
            <th>動作</th>
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
            <td>
                <a type="button" class="btn btn-info" href="{{ route('user.order.detail', $order->id) }}">詳情</a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection