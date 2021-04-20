@extends('user.index')

@section('content')
<br><br><br>
<div class="row">
    <div class="col-sm-6 push-bit">
        <a href="#" class="gallery-link"><img src="{{ $product->imageUrl ?? asset('storage/' . $product->image) }}" style="width: 500px;height: 500px;" alt="" class="img-responsive push-bit" /></a>
    </div>
    <div class="col-sm-6 push-bit">
        <div class="clearfix">
            <div class="pull-right">
                <span class="h2"><strong>$ {{ $product->price }}</strong></span>
            </div>
            <span class="h4">
                <strong class="text-success">{{ $product->name }}</strong><br />
                <small>{{ $product->quantity }} {{ $product->unit }}</small>
            </span>
        </div>
        <hr />
        <pre>
            {{ $product->description }}
        </pre>
        <pre>
            {{ $product->content }}
        </pre>
        <hr />
        <form action="{{ route('cart.add', $product) }}" method="post" class="form-inline push-bit text-right">
            @csrf
            @method('GET')
            <select id="ecom-addcart-size" name="size" class="form-control" size="1">
                <option value="0" disabled="" selected="">尺寸</option>
                <option value="xs">XS</option>
                <option value="s">S</option>
                <option value="m">M</option>
                <option value="l">L</option>
                <option value="xl">XL</option>
                <option value="xxl">XXL</option>
            </select>
            <button type="submit" class="btn btn-outline-primary"><i class="fas fa-cart-plus fa-2x primary"></i>加到購物車</button>
        </form>
    </div>
</div>
@endsection