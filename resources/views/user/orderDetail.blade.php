@extends('../user.index')

@section('content')
<!-- Alert User -->
@if(Session::has('success'))
<div class="alert alert-success">
    {{Session::get('success')}}
</div>
@endif

<div class="purchase overflow-auto">
    <!--<div style="min-width: 600px">-->
        <header>
            <div class="row">
                <div class="col-sm-3 col-xs-3">
                    <img style="width: 400px;height: 200px;" src="https://www.qbrobotics.com/wp-content/uploads/2018/03/sample-logo-490x200.png" class="img-responsive">
                </div>
            </div>
        </header>
        <main>
            <div class="row">
                <div class="col-sm-6 col-xs-6 to-details">
                    <div>訂單明細:</div>
                    <div class="to-name">買家：{{ $order->billing_name }}</div>
                    <div class="to-email">電子郵件：{{ $order->billing_email }}</div>
                    <div class="to-address">地址：{{ $order->billing_address }}</div>
                </div>
                <div class="text-right col-sm-6 col-xs-6 purchase-info">
                    <div class="info-date">下單時間 : {{ $order->created_at}}</div>
                    <div class="info-status">狀態：{{ $order->status }}</div>
                    <div class="info-payment">付款方式：{{ $order->payment }}</div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-xs-12 table-responsive">
                    <table class="table table-condensed" border="0" cellspacing="0" cellpadding="0" width="100%">
                    <thead>
                        <tr>
                            <th class="text-center col-xs-1 col-sm-1">產品ID</th>
                            <th class="text-center col-xs-7 col-sm-7">產品名稱</th>
                            <th class="text-center col-xs-1 col-sm-1">數量</th>
                            <th class="text-center col-xs-3 col-sm-3">價格</th>
                        </tr>
                    </thead>
                    @if (count($products))
                    <tbody>
                        @foreach ($products as $product)
                        <tr>
                            <td class="col-xs-1 col-sm-1 text-center">{{ $product->id }}</td>
                            <td class="text-center">{{ $product->name }}</td>
                            <td class="text-center">{{ $product->pivot->quantity }} {{ $product->unit }}</td>
                            <td class="text-right">{{ $product->price * $product->pivot->quantity }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    @endif
                    <tfoot>
                        <tr>
                            <th colspan="2">
                                留言：<br>
                                {{ $order->comment }}
                            </th>
                            <th class="text-center">
                                含稅<br>
                                總計
                            </th>
                            <th class="text-right">
                                {{ $order->billing_tax }}<br>
                                {{ $order->billing_total }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
                </div>
            </div>
        </main>
    <!--</div>-->
    <div class="row">
        @if ($order->status === '運送中')
        <div class="col-1">
            <form method="post" action="/orders/{{ $order->id }}">
                @csrf
                @method('PATCH')
                <div class="form-group">
                    <button type="submit" class="btn btn-success " name="status" value="貨物已領取">領取</button>
                </div>
            </form>
        </div>
        @endif
        <div class="col-1">
            <a type="button" class="btn btn-secondary" href="{{ route('user.orders', Auth::user()->id) }}">返回</a>
        </div>
    </div>
</div>
@endsection