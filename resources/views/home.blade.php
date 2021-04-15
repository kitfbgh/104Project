@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h2 >Dashboard</h2>
    <div class="btn-toolbar mb-2 mb-md-0">
      <div class="btn-group me-2">
        <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
        <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
      </div>
      <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
        <span data-feather="calendar"></span>
        This week
      </button>
    </div>
</div>
<div class="row">
  <div class="col-md-4 col-xl-3">
    <div class="card bg-c-blue order-card">
        <div class="card-block">
            <h6 class="m-b-20">已上架產品</h6>
            <h2 class="text-right"><i class="fa fa-cart-plus f-left"></i><span>{{ $productNum }}</span></h2>
            <p class="m-b-0">-<span class="f-right"></span></p>
        </div>
    </div>
  </div>
  <div class="col-md-4 col-xl-3">
    <div class="card bg-c-pink order-card">
        <div class="card-block">
            <h6 class="m-b-20">已接受的訂單</h6>
            <h2 class="text-right"><i class="fa fa-credit-card f-left"></i><span>{{ $orderTotal }}</span></h2>
            <p class="m-b-0">已完成的訂單<span class="f-right">{{ $orderCompleted }}</span></p>
        </div>
    </div>
  </div>
</div>

@endsection
