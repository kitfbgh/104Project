@extends('../user.index')

@section('content')
<div>
    <div class="container mt-5">
      <div>
        <div class="row justify-content-center">
          <div class="col-md-9">
            <div class="container row justify-content-around">
              <div class="col-md-4 p-2 rounded-pill alert-success text-center">填寫訂購資料</div>
             </div>
          </div>
          <div class="col-md-6 mt-5">
            <table class="table table-hover">
              <thead class="table-dark">
                <th></th>
                <th>品名</th>
                <th>數量</th>
                <th>單價</th>
                <th>總價格</th>
              </thead>
              <tbody>
                @foreach ($cartItems as $item)
                <tr>
                  <td class="align-middle">
                    <a
                      type="button"
                      class="btn btn-outline-danger btn-sm"
                      href="{{ route('cart.delete', $item->id) }}"
                    >
                      <i class="far fa-trash-alt"></i>
                  </a>
                  </td>
                  <td class="align-middle">
                    {{ $item->name }}
                  </td>
                  <td class="align-middle">{{ $item->quantity }}/{{ $item->attributes->unit }}</td>
                  <td class="align-middle">NT$ {{ $item->price }}</td>
                  <td class="align-middle">NT$ {{ Cart::session(auth()->id())->get($item->id)->getPriceSum() }}</td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="4" class="text-right">小計</td>
                  <td class="text-right">NT$ {{ $subTotal }}</td>
                </tr>
                <tr>
                  <td colspan="4" class="text-right text-success">總計</td>
                  <td class="text-right text-success">NT$ {{ $total }}</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
        <div class="my-5 row justify-content-center">
          <form class="col-md-6" action="/orders" method="POST">
              @csrf
              <input type="hidden" name="total" value="{{ $total }}" />
              <input type="hidden" name="subTotal" value="{{ $subTotal }}"/>
              <input type="hidden" name="tax" value="{{ $total - $subTotal }}"/>
              <input type="hidden" name="status" value="訂單已送出"/>
              <input type="hidden" name="userId" value="{{ $userId }}"/>
            <div class="form-group">
              <label for="payment">
                付款方式
                <span class="text-danger">*必填</span>
              </label>
              <select id="payment" name="payment" class="form-control" size="1" required>
                <option disabled selected value="" hidden>付款方式</option>
                <option value="貨到付款">貨到付款</option>
              </select>
            </div>
            <div class="form-group">
              <label for="userEmail">
                Email
                <span class="text-danger">*必填</span>
              </label>
              <input
                type="email"
                class="form-control"
                name="email"
                id="userEmail"
                placeholder="請輸入 Email"
                required
              />
            </div>
            <div class="form-group">
              <label for="username">
                收件人姓名
                <span class="text-danger">*必填</span>
              </label>
              <input
                type="text"
                class="form-control"
                name="name"
                id="username"
                placeholder="輸入姓名"
                required
              />
            </div>
            <div class="form-group">
              <label for="usertel">
                收件人電話
                <span class="text-danger">*必填</span>
              </label>
              <input
                type="tel"
                class="form-control"
                id="usertel"
                name="tel"
                placeholder="請輸入10位數電話 (XXXXXXXXXX)"
                minlength="10"
                list="defaultTels"
                pattern="[0-9]{10}"
                required
              />
              <datalist id="defaultTels">
                <option value="1111111111">
                <option value="1222222222">
              </datalist>
            </div>
            <div class="form-group">
              <label for="useraddress">
                收件人地址
                <span class="text-danger">*必填</span>
              </label>
              <input
                type="text"
                class="form-control"
                name="address"
                id="useraddress"
                placeholder="請輸入地址"
                required
              />
            </div>
            <div class="form-group">
              <label for="comment">留言</label>
              <textarea
                name="comment"
                id="comment"
                class="form-control"
                cols="30"
                rows="10"
              ></textarea>
            </div>
            <div class="text-right">
              <a href="{{ route('cart') }}" type="button" class="btn btn-secondary">返回</a>
              <button class="btn btn-danger" type="submit">送出訂單</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection