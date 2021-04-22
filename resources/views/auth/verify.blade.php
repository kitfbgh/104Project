@extends('layouts.login')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('認證您的電子信箱') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('電子郵件已傳送') }}
                        </div>
                    @endif

                    {{ __('前往網頁前，請確認您的電子信箱是否收到認證郵件') }}
                    <br>
                    {{ __('如果您沒收到信件') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('點擊再度傳送認證郵件') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
