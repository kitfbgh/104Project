<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow navbar-expand-md">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="{{ url('/') }}"><i class="fab fa-maxcdn"></i> {{ config('app.name', 'Laravel') }}</a>
    
    {{-- <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search"> --}}
    <ul class="navbar-nav px-5 ml-auto">
      <li class="nav-item mr-auto">
        <a class="nav-link" href="{{ route('welcome') }}">
          <i class="fas fa-list"></i>
          商品列表
        </a>
      </li>

      <li class="nav-item mr-auto">
        <a class="nav-link" href="{{ route('contact') }}">
          <i class="fas fa-paper-plane"></i>
          聯絡我們
        </a>
      </li>

      @cannot('manager')
      <li class="nav-item mr-auto">
        <a class="nav-link " href="{{ route('cart') }}">
          <i class="fas fa-cart-plus fa-x text-success"></i>
          購物車
          <div class="badge badge-danger">
            @can('admin')
              {{ Cart::session(auth()->id())->getContent()->count() }}
            @elsecan('manager')
              {{ Cart::session(auth()->id())->getContent()->count() }}
            @elsecan('user')
              {{ Cart::session(auth()->id())->getContent()->count() }}
            @else
            @endcan
          </div>
        </a>
      </li>
      @endcan

      <!-- Authentication Links -->
      @guest
        @if (Route::has('login'))
          <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">{{ __('登入') }}</a>
          </li>
        @endif
                            
        @if (Route::has('register'))
        <li class="nav-item">
          <a class="nav-link" href="{{ route('register') }}">{{ __('註冊') }}</a>
        </li>
        @endif
      @else
        <li class="nav-item dropdown px-2">
          <a id="navbarDropdown " class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            {{ Auth::user()->name }}
          </a>
          
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            @cannot('manager')
            <a class="dropdown-item" href="{{ route('user.profile') }}">
              <i class="fas fa-id-badge"></i>
              個人資料
            </a>
            <a class="dropdown-item" href="{{ route('user.orders', Auth::user()->id) }}">
              <i class="fas fa-box"></i>
              訂單
            </a>
            <hr>
            @endcan
            <a class="dropdown-item" href="{{ route('logout') }}"
               onclick="event.preventDefault();
               document.getElementById('logout-form').submit();">
                {{ __('登出') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
              @csrf
            </form>
          </div>
        </li>
      @endif
    </ul>
</nav>