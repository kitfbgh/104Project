@extends('../user.index')

@section('content')
<div class="main-content">
    <div class="container mt-7">
      <!-- Table -->
      <h2 class="mb-5">個人資料</h2>
      <div class="row">
        <div class="col-xl-8 m-auto order-xl-1">
          <div class="card bg-secondary shadow">
            <div class="card-header bg-white border-0">
              <div class="row align-items-center">
                <div class="col-8">
                  <h3 class="mb-0">我的帳號</h3>
                </div>
            </div>
            <div class="card-body">
              <form action="{{ route('user.profile.update', $user->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <h6 class="heading-small text-muted mb-4">使用者資訊</h6>
                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group focused">
                        <label class="form-control-label" for="input-username">使用者名稱</label>
                        <input type="text" id="input-username" class="form-control form-control-alternative" placeholder="{{ $user->name }}" name="name">
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label" for="input-email">Email</label>
                        <input type="email" id="input-email" class="form-control form-control-alternative" placeholder="{{ $user->email }}" name="email">
                      </div>
                    </div>
                  </div>
                </div>
                <input type="submit" class="btn btn-primary float-right" value="更新" />
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection