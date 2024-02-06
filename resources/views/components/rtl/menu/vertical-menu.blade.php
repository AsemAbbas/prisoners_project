<div class="sidebar-wrapper sidebar-theme">
    <nav id="sidebar">
        <div class="navbar-nav theme-brand flex-row  text-center">
            <div class="nav-logo">
                <div class="nav-item theme-logo">
                    <a href="{{route('main.index')}}">
                        <img src="{{asset('assets/images/logo.webp')}}"
                             class="navbar-logo logo-light" alt="logo">
                    </a>
                </div>
                <div class="nav-item theme-text">
                    <a style="font-size: 21px!important;" href="{{route('main.index')}}" class="nav-link">فجر
                        الحرية</a>
                </div>
            </div>
            <div class="nav-item sidebar-toggle">
                <div class="btn-toggle sidebarCollapse">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-chevrons-left">
                        <polyline points="11 17 6 12 11 7"></polyline>
                        <polyline points="18 17 13 12 18 7"></polyline>
                    </svg>
                </div>
            </div>
        </div>
        <div class="profile-info">
            <div class="user-info">
                <div class="profile-img">
                    <img src="{{asset('assets/images/admin.webp')}}" alt="avatar">
                </div>
                <div class="profile-content">
                    @auth
                        <h6>{{\Illuminate\Support\Facades\Auth::user()->name}}</h6>
                        <p>
                            {{\Illuminate\Support\Facades\Auth::user()->user_status}}
                        </p>
                    @endauth
                </div>
            </div>
        </div>
        <div class="shadow-bottom border border-success"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            @auth
                @php
                    $main = [
                        'dashboard.prisoners',
                        'dashboard.prisoners.create',
                        'dashboard.prisoners.update',
                        'dashboard.suggestions',
                        'dashboard.suggestions.create',
                        'dashboard.suggestions.update',
                        'dashboard.users',
                        'dashboard.news',
                        'dashboard.news.create',
                        'dashboard.news.update',
                        'dashboard.confirms',
                        'dashboard.arrests',
                        'dashboard.relatives_prisoners'
                        ]
                @endphp
                <li class="menu {{ in_array(Route::getCurrentRoute()->getName(),$main) ? "active" : "" }}">
                    <a href="#dashboard" data-bs-toggle="collapse"
                       aria-expanded="{{ in_array(Route::getCurrentRoute()->getName(),$main) ? "true" : "false" }}"
                       class="dropdown-toggle">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-list">
                                <line x1="8" y1="6" x2="21" y2="6"></line>
                                <line x1="8" y1="12" x2="21" y2="12"></line>
                                <line x1="8" y1="18" x2="21" y2="18"></line>
                                <line x1="3" y1="6" x2="3.01" y2="6"></line>
                                <line x1="3" y1="12" x2="3.01" y2="12"></line>
                                <line x1="3" y1="18" x2="3.01" y2="18"></line>
                            </svg>
                            <span>القوائم الرئيسية</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-chevron-right">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled {{ in_array(Route::getCurrentRoute()->getName(),$main) ? "show" : "" }}"
                        id="dashboard" data-bs-parent="#accordionExample">
                        @if(in_array(\Illuminate\Support\Facades\Auth::user()->user_status,['مراجع منطقة','مسؤول']))
                            <li class="{{ Route::getCurrentRoute()->getName()== 'dashboard.prisoners' ? 'active' : '' }}">
                                <a href="{{route('dashboard.prisoners')}}">قائمة الأسرى</a>
                            </li>
                            <li class="{{ Route::getCurrentRoute()->getName() == 'dashboard.suggestions' ? 'active' : '' }}">
                                @auth
                                    @php
                                        $CurrentUserCities = \App\Models\User::query()->where('id', \Illuminate\Support\Facades\Auth::user()->id)->with('City')->first()->toArray()['city'] ?? [];

                                        $cityIdArray = [];
                                        foreach ($CurrentUserCities as $subArray) {
                                            if (isset($subArray['pivot']['city_id'])) {
                                                $cityIdArray[] = $subArray['pivot']['city_id'];
                                            }
                                        }

                                        $count = \App\Models\PrisonerSuggestion::query()
                                            ->with(['City', 'Relationship'])
                                            ->where(function ($query) use ($cityIdArray) {
                                                $query->whereIn('city_id', $cityIdArray)
                                                    ->orWhereNull('city_id');
                                            })->where('suggestion_status', 'يحتاج مراجعة')->count()
                                    @endphp
                                @endauth
                                <a href="{{route('dashboard.suggestions')}}">
                                    قائمة الاقتراحات
                                    @if(isset($count) && $count > 0)
                                        <span class="bg-danger" style="padding: 3px;font-size: 11px;border-radius: 50%;text-align: center;@if($count < 10) width: 25px; @endif ">{{$count}}</span>
                                    @endif
                                </a>
                            </li>
                        @endif
                        @if(\Illuminate\Support\Facades\Auth::user()->user_status === "مسؤول")
                            <li class="{{ Route::getCurrentRoute()->getName()== 'dashboard.confirms' ? 'active' : '' }}">
                                <a href="{{route('dashboard.confirms')}}">قائمة الاقتراحات المؤكدة</a>
                            </li>
                            <li class="{{ Route::getCurrentRoute()->getName()== 'dashboard.users' ? 'active' : '' }}">
                                <a href="{{route('dashboard.users')}}">قائمة المستخدمين</a>
                            </li>
                        @endif
                        @if(in_array(\Illuminate\Support\Facades\Auth::user()->user_status,['مسؤول','محرر أخبار']))
                            <li class="{{ Route::getCurrentRoute()->getName()== 'dashboard.news' ? 'active' : '' }}">
                                <a href="{{route('dashboard.news')}}">قائمة الأخبار</a>
                            </li>
                        @endif
                    </ul>
                </li>
                @php
                    $sub = [
                        'dashboard.cities',
                        'dashboard.belongs',
                        'dashboard.news_types',
                        'dashboard.prisoner_types',
                        'dashboard.news_types',
                        'dashboard.statistics',
                        'dashboard.social_media',
                        'dashboard.relationships',
                        'dashboard.healths',
                        ]
                @endphp
                @if(in_array(\Illuminate\Support\Facades\Auth::user()->user_status,['محرر أخبار','مسؤول']))
                    <li class="menu {{ in_array(Route::getCurrentRoute()->getName(),$sub) ? "active" : "" }}">
                        <a href="#dashboardSub" data-bs-toggle="collapse"
                           aria-expanded="{{ in_array(Route::getCurrentRoute()->getName(),$sub) ? "true" : "false" }}"
                           class="dropdown-toggle">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-list">
                                    <line x1="8" y1="6" x2="21" y2="6"></line>
                                    <line x1="8" y1="12" x2="21" y2="12"></line>
                                    <line x1="8" y1="18" x2="21" y2="18"></line>
                                    <line x1="3" y1="6" x2="3.01" y2="6"></line>
                                    <line x1="3" y1="12" x2="3.01" y2="12"></line>
                                    <line x1="3" y1="18" x2="3.01" y2="18"></line>
                                </svg>
                                <span>القوائم الفرعية</span>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                     stroke-linejoin="round"
                                     class="feather feather-chevron-right">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </div>
                        </a>
                        <ul class="collapse submenu list-unstyled {{ in_array(Route::getCurrentRoute()->getName(),$sub) ? "show" : "" }}"
                            id="dashboardSub" data-bs-parent="#accordionSub">
                            @if(\Illuminate\Support\Facades\Auth::user()->user_status === "محرر أخبار")
                                <li class="{{ Route::getCurrentRoute()->getName()== 'dashboard.news_types' ? 'active' : '' }}">
                                    <a href="{{route('dashboard.news_types')}}">قائمة تصنيفات الأخبار</a>
                                </li>
                            @endif
                            @if(\Illuminate\Support\Facades\Auth::user()->user_status === "مسؤول")
                                <li class="{{ Route::getCurrentRoute()->getName()== 'dashboard.cities' ? 'active' : '' }}">
                                    <a href="{{route('dashboard.cities')}}">قائمة المحافظات</a>
                                </li>
                                <li class="{{ Route::getCurrentRoute()->getName()== 'dashboard.prisoner_types' ? 'active' : '' }}">
                                    <a href="{{route('dashboard.prisoner_types')}}">قائمة تصنيفات الأسرى</a>
                                </li>
                                <li class="{{ Route::getCurrentRoute()->getName()== 'dashboard.relationships' ? 'active' : '' }}">
                                    <a href="{{route('dashboard.relationships')}}">قائمة صلة القرابة</a>
                                </li>
                                <li class="{{ Route::getCurrentRoute()->getName()== 'dashboard.belongs' ? 'active' : '' }}">
                                    <a href="{{route('dashboard.belongs')}}">قائمة الإنتماء</a>
                                </li>
                                <li class="{{ Route::getCurrentRoute()->getName()== 'dashboard.statistics' ? 'active' : '' }}">
                                    <a href="{{route('dashboard.statistics')}}">قائمة الاحصائيات</a>
                                </li>
                                <li class="{{ Route::getCurrentRoute()->getName()== 'dashboard.social_media' ? 'active' : '' }}">
                                    <a href="{{route('dashboard.social_media')}}">قائمة التواصل الإجتماعي</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                <div class="text-center mt-5" id="logoutButton">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                 class="feather feather-log-out">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                        </button>
                    </form>
                </div>
            @endauth
        </ul>
    </nav>

</div>
