@extends('../user.index')

@section('content')
<!-- Alert User -->
@if(Session::has('success'))
<div class="alert alert-success">
    {{Session::get('success')}}
</div>
@endif

<div class="my-5 row justify-content-center">
    <!--Section: Contact v.2-->
<section class="mb-4">

    <!--Section heading-->
    <h2 class="h1-responsive font-weight-bold text-center my-4">聯絡我們</h2>
    <!--Section description-->
    <p class="text-center w-responsive mx-auto mb-5">你有任何問題嗎?<br>請不要猶豫，直接與我們聯繫。我們的團隊將在幾小時內與你聯繫。</p>

    <div class="row">

        <!--Grid column-->
        <div class="col-md-9 mb-md-0 mb-5">
            <form id="contact-form" name="contact-form" action="{{ route('contact.store') }}" method="POST">
                @csrf
                <!--Grid row-->
                <div class="row">

                    <!--Grid column-->
                    <div class="col-md-4">
                        <div class="md-form mb-0">
                            <input type="text" id="name" name="name" class="form-control" placeholder="輸入名字" required>
                            <label for="name" class="">你的名字</label>
                        </div>
                    </div>
                    <!--Grid column-->

                    <!--Grid column-->
                    <div class="col-md-4">
                        <div class="md-form mb-0">
                            <input
                              type="text"
                              id="phone"
                              name="phone"
                              class="form-control"
                              placeholder="請輸入10位數電話 (XXXXXXXXXX)"
                              minlength="10"
                              list="defaultTels"
                              pattern="[0-9]{10}"
                              required
                            />
                            <label for="phone" class="">你的電話</label>
                        </div>
                    </div>
                    <!--Grid column-->

                    <!--Grid column-->
                    <div class="col-md-4">
                        <div class="md-form mb-0">
                            <input type="text" id="email" name="email" class="form-control" placeholder="輸入信箱" required>
                            <label for="email" class="">你的信箱</label>
                        </div>
                    </div>
                    <!--Grid column-->

                </div>
                <!--Grid row-->

                <!--Grid row-->
                <div class="row">
                    <div class="col-md-12">
                        <div class="md-form mb-0">
                            <input type="text" id="subject" name="subject" class="form-control" placeholder="輸入問題或建議" required>
                            <label for="subject" class="">問題與建議</label>
                        </div>
                    </div>
                </div>
                <!--Grid row-->

                <!--Grid row-->
                <div class="row">

                    <!--Grid column-->
                    <div class="col-md-12">

                        <div class="md-form">
                            <textarea type="text" id="message" name="message" rows="2" class="form-control md-textarea" placeholder="輸入內容" required></textarea>
                            <label for="message">詳細內容</label>
                        </div>

                    </div>
                </div>
                <!--Grid row-->

                <div class="text-center text-md-left">
                    <button class="btn btn-primary" type="submit">送出</button>
                </div>
                <div class="status"></div>
            </form>
        </div>
        <!--Grid column-->

        <!--Grid column-->
        <div class="col-md-3 text-center">
            <ul class="list-unstyled mb-0">
                <li><i class="fas fa-map-marker-alt fa-2x"></i>
                    <p>Xindian Taipei, Taiwan</p>
                </li>

                <li><i class="fas fa-phone mt-4 fa-2x"></i>
                    <p>+ 01 234 567 89</p>
                </li>

                <li><i class="fas fa-envelope mt-4 fa-2x"></i>
                    <p>natz.liutest@gmail.com</p>
                </li>
            </ul>
        </div>
        <!--Grid column-->

    </div>

</section>
<!--Section: Contact v.2-->
</div>
@endsection