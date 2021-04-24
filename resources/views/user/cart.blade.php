@extends('../user.index')

@section('content')
<!-- Alert User -->
@if(Session::has('success'))
<div class="alert alert-success">
    {{Session::get('success')}}
</div>
@elseif (Session::has('delete'))
<div class="alert alert-danger">
    {{Session::get('delete')}}
</div>
@endif

@if (count($cartItems) > 0)
    <table class="table table-striped">
        <thead>
            <tr>
                <th>商品圖片</th>
                <th>商品名稱</th>
                <th>售價</th>
                <th>數量</th>

                <th>動作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cartItems as $item)
            <tr>
                <td scope="row"><img style="height: 100px;width: 100px;" src="{{ $item->attributes->imageUrl }}" alt="{{ $item->name }}"></td>
                <td>{{ $item->name }}</td>
                <td>
                    $ {{ Cart::session(auth()->id())->get($item->id)->getPriceSum() }}
                </td>
                <td>
                    <form action="{{ route('cart.update', $item->id) }}">
                        <input name="quantity" type="number" value="{{ $item->quantity }}" min="1" max="{{ $countOfProduct[$item->id] }}">
                        <p> 尺寸：{{ strtoupper($item->attributes->size) }}</p>
                        <input type="submit" class="btn btn-success" value="save">
                    </form>
                </td>

                <td>
                    <a href="{{ route('cart.delete', $item->id) }}" role="button" class="btn btn-danger">刪除</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <h3>小計 : $ {{ $subTotal }}</h3>
    <h3>總計 : $ {{ $total }}</h3>
    <a type="button" class="btn btn-secondary" href="{{ route('welcome') }}">繼續購物</a>
    <a role="button" class="btn btn-primary" href="{{ route('orders.checkout') }}">結帳去！</a>    
@else
    <h1 class="text-center">購物車內無商品</h1>
    <a type="button" class="btn btn-outline-success  btn-block" href="{{ route('welcome') }}">立刻去逛逛吧！</a>
@endif
@endsection