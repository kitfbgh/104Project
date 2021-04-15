@extends('../user.index')

@section('content')
<table class="table table-striped">
    <thead class="table-dark">
        <tr>
            <th>購買時間</th>
            <th>Email</th>
            <th>購買款項</th>
            <th>狀態</th>
            <th>應付金額</th>
            <th>留言</th>
            <th>動作</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
        <tr>
            <td>{{ $order->created_at }}</td>
            <td>{{ $order->billing_email }}</td>
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
            <td>
                <ul class="list-unstyled">
                    <li>
                        <form method="post" action="{{ route('orders.update', $order->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <button type="submit" class="btn btn-danger" name="status" value="買家提出取消">取消</button>
                            </div>
                        </form>
                    </li>
                </ul>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection