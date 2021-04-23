@extends('user.index')

@section('content')

@if (count($products))
<div class="row">
    @foreach ($products as $product)
    <div class="col-md-3">
        <div class="ibox">
            <div class="ibox-content product-box">
                    <img src="{{ $product->imageUrl ?? asset('storage/' . $product->image) }}" style="width: 200px;height: 200px;" alt="{{ $product['name'] }}">
                <div class="product-desc">
                    <span class="product-price">
                        ${{ $product->price }}
                    </span>
                    <small class="text-muted">{{ $product->category }}</small>
                    <a href="{{ route('products.detail', $product->id) }}" class="product-name"> {{ $product->name }}</a>

                    <div class="small m-t-xs">
                        {{ $product->description }}
                    </div>
                    <div class="m-t text-righ">

                        <a href="{{ route('products.detail', $product->id) }}" class="btn btn-xs btn-outline-primary">info <i class="fa fa-long-arrow-right"></i> </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    {{ $products->links("pagination::simple-bootstrap-4") }}
</div>
@endif
@endsection
