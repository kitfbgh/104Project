<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3 menu-list">
      <br>
      <span>管理員</span>
      <ul class="nav flex-column menu-content">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="{{ route('dashboard') }}">
            <span data-feather="home"></span>
            Dashboard
          </a>
        </li>
        <li class="nav-item" data-target="#products">
          <a class="nav-link" href="{{ route('products') }}">
            <span data-feather="shopping-cart"></span>
            產品列表
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{ route('orders') }}">
            <span data-feather="file"></span>
            訂單列表
          </a>
        </li>
      </ul>
    </div>
  </nav>
  