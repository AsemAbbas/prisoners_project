<footer>
    <div class="container" dir="rtl">
        <!-- About and Newsletter START -->
        <div class="row bg-dark py-5 mx-0 card card-header flex-row align-items-center text-center text-md-start">
            <!-- Copyright -->
            <div class="col-md-5 mb-3 mb-md-0">
                <div class="text-primary-hover text-body-secondary"> جميع الحقوق محفوظة.بوابة الحرية ©2023
                </div>
            </div>
            <!-- Logo -->
            <div class="col-md-3 mb-3 mb-md-0">
                <img src="{{asset('assets/images/light-logo.png')}}" width="150" alt="footer logo">
            </div>
            <!-- Social links -->
            @php
                $SocialMedia = \App\Models\SocialMedia::all();
            @endphp
            <div class="col-md-4">
                <ul class="nav text-primary-hover justify-content-center justify-content-md-end">
                    @foreach($SocialMedia as $social)
                        <li class="nav-item"><a class="nav-link px-2 fs-5" target="_blank" href="{{$social->social_link}}"> <img width="20" src="{{asset('storage/social_photo/'.$social->social_photo)}}" alt="{{$social->social_name}}" title="{{$social->social_name}}"> </a></li>
                    @endforeach


                </ul>
            </div>
        </div>
        <!-- About and Newsletter END -->
    </div>
</footer>
