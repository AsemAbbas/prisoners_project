<footer id="footer">
    <div class="container-fluid" dir="rtl">
        <!-- About and Newsletter START -->
        <div style="background-color:#022d4f!important;"
             class="row bg-dark py-5 mx-0 card card-header flex-row align-items-center text-center text-md-start">
            <!-- Copyright -->
            <div class="col-md-4 mb-3 mb-md-0" style="text-align: right;">
                <div style="display: flex; flex-direction: column; align-items: center;">
                    <img src="{{asset('main/images/green.webp')}}" width="80" alt="green" style="margin-bottom: 10px;">
                    <div class="text-primary-hover text-body-secondary">
                        جميع الحقوق محفوظة . فجر الحرية ©2024
                    </div>
                </div>
            </div>
            <!-- Logo -->
            <div class="col-md-4 mb-3 mb-md-0 text-center">
                <img src="{{asset('assets/images/light-logo.webp')}}" width="180" alt="footer logo">
            </div>
            <!-- Social links -->
            @php
                $SocialMedia = \App\Models\SocialMedia::query()->orderBy('order_by')->get();
            @endphp
            <div class="col-md-4 text-center">
                <ul class="nav text-primary-hover justify-content-center justify-content-center text-center">
                    @foreach($SocialMedia as $social)
                        <li class="nav-item">
                            <a class="nav-link px-2 fs-5" target="_blank"
                               href="{{$social->social_link}}">
                                <img width="20"
                                     src="{{asset('storage/social_photo/'.$social->social_photo)}}"
                                     alt="{{$social->social_name}}"
                                     title="{{$social->social_name}}">
                            </a>
                        </li>
                    @endforeach
                </ul>
                <span class="text-white text-center d-block my-2" style="font-size: 18px">من نحن ؟</span>
                <span class="text-white text-center">منصة إعلامية فلسطينية ، تتبع مكتب الشهداء والأسرى والجرحى في حركة المقاومة الإسلامية حماس. تهتم بقضايا الأسرى في سجون الاحتلال وتحديث بياناتهم.</span>
            </div>
        </div>
        <!-- About and Newsletter END -->
    </div>
</footer>
