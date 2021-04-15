@extends('../layouts.app')

@section('content')
<table class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th>購買時間</th>
            <th>Email</th>
            <th>地址</th>
            <th>購買款項</th>
            <th>狀態</th>
            <th>應付金額</th>
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
            <td>{{ $order->billing_email }}</td>
            <td>{{ $order->billing_address }}</td>
            @if (count($order->products))
            <td>
                <ul class="list-unstyled">
                @foreach ($order->products as $product)
                <li>
                    {{ $product->name }} <span class="text-end">數量：{{ $product->pivot->quantity }} {{ $product->unit }}</span>
                </li>
                @endforeach
                </ul>
            </td>
            @else
                <td></td>
            @endif
            <td>{{ $order->status }}</td>
            <td>$ {{ $order->billing_total }}</td>
            <td>{{ $order->comment }}</td>
            @cannot('user')
            <td>
                <ul class="list-unstyled">
                    <li>
                        <form method="post" action="/orders/{{ $order->id }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary " name="status" value="運送中">出貨</button>
                            </div>
                        </form>
                    </li>
                    <li>
                        <form method="post" action="/orders/{{ $order->id }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <button type="submit" class="btn btn-success " name="status" value="訂單完成">完成</button>
                            </div>
                        </form>
                    </li>
                    <li>
                        <form method="post" action="/orders/{{ $order->id }}">
                            @csrf
                            @method('DELETE')
                            <div class="form-group">
                                <input type="submit" class="btn btn-danger " value="刪除">
                            </div>
                        </form>
                    </li>
                </ul>
            </td>
            @endcan
        </tr>
        @endforeach
    </tbody>
</table>
@endsection