@extends('user.index')

@section('content')

@if (count($products))
<div class="row">
    @foreach ($products as $product)
        <div class="card border-info mb-4" style="max-width: 270px;" >
            <div class="row g-0">
                <div>
                    <img src={{ $product['imageUrl'] ?? $product['image'] }} class="card-img-top" style="height: 200px;" alt="{{ $product['name'] }}">
                </div>
                <div>
                    <div class="card-body ">
                        <h5 class="card-title">名稱：{{ $product['name'] }}</h5>
                        <p class="card-text">商品概述：{{ $product['description'] }}</p>
                        <p class="card-text">商品數量：{{ $product['quantity'] }} {{ $product['unit'] }}</p>
                        <p class="card-text">原價：${{ $product['origin_price'] }}   元 / {{ $product['unit'] }}</p>
                        <p class="card_text">售價：${{ $product['price'] }}   元 / {{ $product['unit'] }}</p>
                        <p class="card-text"><small class="text-muted">Last updated at {{ $product['updated_at'] }}</small></p>
                    </div>
                </div>
                <div class="card-footer text-center" >
                    <a href="{{ route('cart.add', $product) }}" class="btn btn-outline-primary"><i class="fas fa-cart-plus fa-2x primary"></i>加到購物車</a>
                </div>
            </div>
        </div>
    @endforeach
    {{ $products->links("pagination::simple-bootstrap-4") }}
</div>
@endif
@endsection
