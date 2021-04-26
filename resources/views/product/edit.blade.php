<div
      class="modal fade"
      id="modal_edit{{ $product['id'] }}"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0">
          <div class="modal-header bg-dark text-white">
            <h5 class="modal-title" id="exampleModalLabel{{ $product['id'] }}">
              <span>編輯產品</span>
            </h5>
            <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="/products/{{ $product['id'] }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group">
                  <label for="image{{ $product['id'] }}">輸入圖片網址</label>
                  <input
                    type="text"
                    class="form-control"
                    id="image{{ $product['id'] }}"
                    name="imageUrl"
                    placeholder="請輸入圖片連結"
                    value="{{ $product['imageUrl'] }}"
                  />
                </div>
                <div class="form-group">
                  <label for="customFile{{ $product['id'] }}">
                    或 上傳圖片
                    {{-- <i class="fas fa-spinner fa-spin"></i> --}}
                  </label>
                  <input
                    type="file"
                    name="image"
                    id="customFile{{ $product['id'] }}"
                    class="form-control"
                   />
                </div>
                <img  class="img-fluid" alt />
              </div>
              <div class="col-sm-8">
                <div class="form-group">
                  <label for="title{{ $product['id'] }}">標題</label>
                  <input
                    name="name"
                    type="text"
                    class="form-control"
                    id="title{{ $product['id'] }}"
                    placeholder="請輸入標題"
                    required
                    value="{{ $product['name'] }}"
                  />
                </div>

                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="category{{ $product['id'] }}">分類</label>
                    <input
                      name="category"
                      type="text"
                      class="form-control"
                      id="category{{ $product['id'] }}"
                      placeholder="請輸入分類"
                      required
                      value="{{ $product['category'] }}"
                    />
                  </div>
                  <div class="form-group col-md-6">
                    <label for="unit{{ $product['id'] }}">單位</label>
                    <input
                      name="unit"
                      type="unit"
                      class="form-control"
                      id="unit{{ $product['id'] }}"
                      placeholder="請輸入單位"
                      required
                      value="{{ $product['unit'] }}"
                    />
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="origin_price{{ $product['id'] }}">原價</label>
                    <input
                      name="origin_price"
                      type="number"
                      class="form-control"
                      id="origin_price{{ $product['id'] }}"
                      placeholder="請輸入原價"
                      required
                      value="{{ $product['origin_price'] }}"
                    />
                  </div>
                  <div class="form-group col-md-6">
                    <label for="price{{ $product['id'] }}">售價</label>
                    <input
                      name="price"
                      type="number"
                      class="form-control"
                      id="price{{ $product['id'] }}"
                      placeholder="請輸入售價"
                      required
                      value="{{ $product['price'] }}"
                    />
                  </div>
                </div>
                <div class="form-group">
                    <label for="quantity{{ $product['id'] }}">數量</label>
                    <input
                      name="quantity"
                      type="number"
                      min="1"
                      class="form-control"
                      id="quantity{{ $product['id'] }}"
                      placeholder="請輸入數量"
                      required
                      value="{{ $product['quantity'] }}"
                    />
                </div>
                <hr />

                <div class="form-group">
                  <label for="description{{ $product['id'] }}">產品描述</label>
                  <textarea
                    name="description"
                    type="text"
                    class="form-control"
                    id="description{{ $product['id'] }}"
                    placeholder="請輸入產品描述"
                  >{{ $product['description'] }}</textarea>
                </div>
                <div class="form-group">
                  <label for="content{{ $product['id'] }}">說明內容</label>
                  <textarea
                    name="content"
                    type="text"
                    class="form-control"
                    id="content{{ $product['id'] }}"
                    placeholder="請輸入產品說明內容"
                  >{{ $product['content'] }}</textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">取消</button>
            <button type="submit" class="btn btn-primary">確認</button>
          </div>
          </form>
        </div>
    </div>
</div>

<a href="#modal_edit{{ $product['id'] }}" role="button" class="btn btn-primary" data-bs-toggle="modal">編輯</a>