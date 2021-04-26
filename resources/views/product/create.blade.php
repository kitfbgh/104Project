<div
      class="modal fade"
      id="modal"
      tabindex="-1"
      role="dialog"
      aria-labelledby="exampleModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0">
          <div class="modal-header bg-dark text-white">
            <h5 class="modal-title" id="exampleModalLabel">
              <span>新增產品</span>
            </h5>
            <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="/products" method="POST" enctype="multipart/form-data">
            @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group">
                  <label for="image">輸入圖片網址</label>
                  <input
                    type="text"
                    class="form-control"
                    id="image"
                    name="imageUrl"
                    placeholder="請輸入圖片連結"
                  />
                </div>
                <div class="form-group">
                  <label for="customFile">
                    或 上傳圖片
                    {{-- <i class="fas fa-spinner fa-spin"></i> --}}
                  </label>
                  <input
                    type="file"
                    name="image"
                    id="customFile"
                    class="form-control"
                   />
                </div>
                <img  class="img-fluid" alt />
              </div>
              <div class="col-sm-8">
                <div class="form-group">
                  <label for="title">標題</label>
                  <input
                    name="name"
                    type="text"
                    class="form-control"
                    id="title"
                    placeholder="請輸入標題"
                    required
                  />
                </div>

                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="category">分類</label>
                    <input
                      name="category"
                      type="text"
                      class="form-control"
                      id="category"
                      placeholder="請輸入分類"
                      required
                    />
                  </div>
                  <div class="form-group col-md-6">
                    <label for="unit">單位</label>
                    <input
                      name="unit"
                      type="unit"
                      class="form-control"
                      id="unit"
                      placeholder="請輸入單位"
                      required
                    />
                  </div>
                </div>

                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label for="origin_price">原價</label>
                    <input
                      name="origin_price"
                      type="number"
                      class="form-control"
                      id="origin_price"
                      placeholder="請輸入原價"
                      required
                    />
                  </div>
                  <div class="form-group col-md-6">
                    <label for="price">售價</label>
                    <input
                      name="price"
                      type="number"
                      class="form-control"
                      id="price"
                      placeholder="請輸入售價"
                      required
                    />
                  </div>
                </div>
                <div class="form-group">
                    <label for="quantity">數量</label>
                    <input
                      name="quantity"
                      type="number"
                      min="1"
                      class="form-control"
                      id="quantity"
                      placeholder="請輸入數量"
                      required
                    />
                </div>
                <div class="form-group">
                    <label for="size">尺寸</label>
                    <select id="size" name="size" class="form-control" size="1">
                        <option value="0" disabled="" selected="">尺寸</option>
                        <option value="xs">XS</option>
                        <option value="s">S</option>
                        <option value="m">M</option>
                        <option value="l">L</option>
                        <option value="xl">XL</option>
                        <option value="xxl">XXL</option>
                    </select>
                  </div>
                <hr />

                <div class="form-group">
                  <label for="description">產品描述</label>
                  <textarea
                    name="description"
                    type="text"
                    class="form-control"
                    id="description"
                    placeholder="請輸入產品描述"
                  ></textarea>
                </div>
                <div class="form-group">
                  <label for="content">說明內容</label>
                  <textarea
                    name="content"
                    type="text"
                    class="form-control"
                    id="content"
                    placeholder="請輸入產品說明內容"
                  ></textarea>
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

<a href="#modal" role="button" class="btn btn-outline-success" data-bs-toggle="modal">創建商品</a>