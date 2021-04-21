@extends('../layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom container">
    @include('../product.create')
</div>
@if (count($products))
<div class="row justify-content-md-center">
    @foreach ($products as $product)
        <div class="card border-info mb-3" style="max-width: 540px;" >
            <div class="row g-0">
                <div class="col-4">
                    <img src="{{ $product->imageUrl ?? asset('storage/' . $product->image) }}" class="card-img-top" style="height: 100%" alt="{{ $product['name'] }}">
                </div>
                <div class="col-6">
                    <div class="card-body text-info">
                        <h5 class="card-title">名稱：{{ $product['name'] }}</h5>
                        <p class="card-text">商品概述：{{ $product['description'] }}</p>
                        <p class="card-text">商品數量：{{ $product['quantity'] }} {{ $product['unit'] }}</p>
                        <p class="card-text">原價：${{ $product['origin_price'] }}   元 / {{ $product['unit'] }}</p>
                        <p class="card_text">售價：${{ $product['price'] }}   元 / {{ $product['unit'] }}</p>
                        <p class="card-text"><small class="text-muted">最後更新時間 : {{ $product['updated_at'] }}</small></p>
                    </div>
                </div>
                <div class="col-2">
                        @include('../product.edit')
                        <form method="post" action="/products/{{ $product['id'] }}">
                            @csrf
                            @method('DELETE')
                            <div class="form-group">
                                <input type="submit" class="btn btn-danger " value="刪除">
                            </div>
                        </form>
                </div>
            </div>
        </div>
    @endforeach
    {{ $products->links("pagination::simple-bootstrap-4") }}
</div>
@endif
@endsection
