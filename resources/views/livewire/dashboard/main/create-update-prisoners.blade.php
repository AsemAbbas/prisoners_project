@section('title')
    فجر الحرية | {{$showEdit ? 'تعديل أسير' : 'إضافة أسير'}}
@endsection
@section('style')
    <link rel="stylesheet" href="{{asset('plugins-rtl/apex/apexcharts.css')}}">
    @vite(['resources/rtl/scss/light/assets/components/list-group.scss'])
    @vite(['resources/rtl/scss/light/assets/widgets/modules-widgets.scss'])
    @vite(['resources/rtl/scss/dark/assets/components/list-group.scss'])
    @vite(['resources/rtl/scss/dark/assets/widgets/modules-widgets.scss'])
    @vite(['resources/rtl/scss/light/assets/elements/alert.scss'])
@endsection
<div class="d-flex justify-content-center flex-column" id="target-element">
    <div class="mx-auto">
        <a href="{{route('main.index')}}">
            <img src="{{asset('assets/images/logo.png')}}" width="350px" alt="logo">
        </a>
    </div>
    <div class="p-5">
        <div class="accordion" id="arrests">
            <form wire:submit.prevent="ReviewMassage">
                <div id="panelsStayOpen-collapse_prisoners" class="accordion-collapse collapse show"
                     wire:ignore.self
                     aria-labelledby="panelsStayOpen-heading_prisoners">
                    <div class="row">
                        <div class="col-md-12 mb-3 text-center">
                            <hr>
                            <h1>بيانات الأسير/ة</h1>
                            <hr>
                        </div>
                        <div class="form-group col-md-6 mb-4">
                            <label for="first_name">الاسم الأول</label>
                            <input wire:model="state.first_name" type="text"
                                   class="form-control @error('first_name') is-invalid @enderror"
                                   id="first_name"
                                   placeholder="الاسم الأول">
                            @error('first_name')
                            <div class="error-message invalid-feedback"
                                 style="font-size: 15px">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6 mb-4">
                            <label for="second_name">اسم الأب</label>
                            <input wire:model="state.second_name" type="text"
                                   class="form-control @error('second_name') is-invalid @enderror"
                                   id="second_name"
                                   placeholder="الاسم الأب">
                            @error('second_name')
                            <div class="error-message invalid-feedback"
                                 style="font-size: 15px">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6 mb-4">
                            <label for="third_name">اسم الجد</label>
                            <input wire:model="state.third_name" type="text"
                                   class="form-control @error('third_name') is-invalid @enderror"
                                   id="third_name"
                                   placeholder="الاسم الجد">
                            @error('third_name')
                            <div class="error-message invalid-feedback"
                                 style="font-size: 15px">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6 mb-4">
                            <label for="last_name">اسم العائلة</label>
                            <input wire:model="state.last_name" type="text"
                                   class="form-control @error('last_name') is-invalid @enderror"
                                   id="last_name"
                                   placeholder="اسم العائلة">
                            @error('last_name')
                            <div class="error-message invalid-feedback"
                                 style="font-size: 15px">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6 mb-4">
                            <label for="mother_name">اسم الأم</label>
                            <input wire:model="state.mother_name" type="text"
                                   class="form-control @error('mother_name') is-invalid @enderror"
                                   id="mother_name"
                                   placeholder="اسم الأم">
                            @error('mother_name')
                            <div class="error-message invalid-feedback"
                                 style="font-size: 15px">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6 mb-4">
                            <label for="identification_number">رقم الهوية</label>
                            <input wire:model="state.identification_number" type="text"
                                   class="form-control @error('identification_number') is-invalid @enderror"
                                   id="identification_number"
                                   placeholder="رقم الهوية">
                            @error('identification_number')
                            <div class="error-message invalid-feedback"
                                 style="font-size: 15px">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6 mb-4">
                            <label for="date_of_birth">تاريخ الميلاد</label>
                            <input wire:model="state.date_of_birth" type="date"
                                   class="form-control @error('date_of_birth') is-invalid @enderror"
                                   id="date_of_birth"
                                   placeholder="تاريخ الميلاد">
                            @error('date_of_birth')
                            <div class="error-message invalid-feedback"
                                 style="font-size: 15px">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6 mb-4">
                            <label for="gender">الجنس</label>
                            <select wire:model.live="state.gender"
                                    class="form-select @error('gender') is-invalid @enderror"
                                    id="gender">
                                <option>إختر...</option>
                                @foreach(\App\Enums\Gender::cases() as $row)
                                    <option value="{{$row->value}}">{{$row->value}}</option>
                                @endforeach
                            </select>
                            @error('gender')
                            <div class="error-message invalid-feedback"
                                 style="font-size: 15px">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6 mb-4">
                            <label for="city_id">المحافظة</label>
                            <select wire:model.live="state.city_id"
                                    class="form-select @error('city_id') is-invalid @enderror"
                                    id="city_id">
                                <option>إختر...</option>
                                @foreach($Cities as $city)
                                    <option value="{{$city->id}}">{{$city->city_name}}</option>
                                @endforeach
                            </select>
                            @error('city_id')
                            <div class="error-message invalid-feedback"
                                 style="font-size: 15px">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6 mb-4">
                            <label for="town_id">البلدة</label>
                            <select wire:model.live="state.town_id" @if(empty($state['city_id'])) disabled
                                    @endif
                                    class="form-select @error('town_id') is-invalid @enderror"
                                    id="town_id">
                                <option>إختر...</option>
                                @foreach($Towns as $town)
                                    <option value="{{$town->id}}">{{$town->town_name}}</option>
                                @endforeach
                            </select>
                            @error('town_id')
                            <div class="error-message invalid-feedback"
                                 style="font-size: 15px">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6 mb-4">
                            <label for="belong_id">الإنتماء</label>
                            <select wire:model.live="state.belong_id"
                                    class="form-select @error('belong_id') is-invalid @enderror"
                                    id="belong_id">
                                <option>إختر...</option>
                                @foreach($Belongs as $row)
                                    <option value="{{$row->id}}">{{$row->belong_name}}</option>
                                @endforeach
                            </select>
                            @error('belong_id')
                            <div class="error-message invalid-feedback"
                                 style="font-size: 15px">{{ $message }}</div>
                            @enderror
                        </div>
                        @if(isset($state['gender']))
                            <div class="form-group col-md-12 mb-4">
                                <label
                                    for="PrisonerType">{{$state['gender'] == "انثى" ? 'تصنيف الأسيرة' : 'تصنيف الأسير'}}</label>
                                <div id="togglePrisonerType" class="PrisonerType">
                                    <div class="card">
                                        <div class="card-header" id="headingPrisonerType" wire:ignore.self>
                                            <section class="mb-0 mt-0">
                                                <div role="menu"
                                                     class="collapsed d-flex justify-content-between"
                                                     data-bs-toggle="collapse"
                                                     data-bs-target="#defaultPrisonerType"
                                                     aria-expanded="false"
                                                     aria-controls="defaultPrisonerType">
                                                    <p class="p-0 m-0">إختر...</p>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                         height="24"
                                                         viewBox="0 0 24 24" fill="none"
                                                         stroke="currentColor"
                                                         stroke-width="2" stroke-linecap="round"
                                                         stroke-linejoin="round"
                                                         class="feather feather-chevron-down">
                                                        <polyline points="6 9 12 15 18 9"></polyline>
                                                    </svg>
                                                </div>
                                            </section>
                                        </div>
                                        <div id="defaultPrisonerType" class="collapse"
                                             aria-labelledby="headingPrisonerType"
                                             wire:ignore.self
                                             data-bs-parent="#togglePrisonerType">
                                            <div class="card-body">
                                                <div class="row">
                                                    @foreach($PrisonerTypes as $row)
                                                        <div class="col-md-6 mb-4">
                                                            <div
                                                                class="form-check form-check-dark form-check-inline">
                                                                <input class="form-check-input"
                                                                       wire:model.live="state.prisoner_type.{{$row->id}}"
                                                                       type="checkbox"
                                                                       id="form-check-dark">
                                                                <label class="form-check-label"
                                                                       for="form-check-dark">
                                                                    {{$row->prisoner_type_name}}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('prisoner_type')
                            <div class="error-message invalid-feedback"
                                 style="font-size: 15px">{{ $message }}</div>
                            @enderror
                        @endif
                        <div class="form-group col-md-12 mb-4">
                            <label for="arrest_start_date">تاريخ الإعتقال</label>
                            <input wire:model="state.arrest_start_date" type="date"
                                   class="form-control @error('arrest_start_date') is-invalid @enderror"
                                   id="arrest_start_date">
                            @error('arrest_start_date')
                            <div class="error-message invalid-feedback"
                                 style="font-size: 15px">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 mb-4">
                            <label for="arrest_type">نوع الإعتقال</label>
                            <select wire:model.live="state.arrest_type"
                                    class="form-select @error('arrest_type') is-invalid @enderror"
                                    id="arrest_type">
                                <option>إختر...</option>
                                @foreach(\App\Enums\ArrestType::cases() as $row)
                                    <option value="{{$row->value}}">{{$row->value}}</option>
                                @endforeach
                            </select>
                            @error('arrest_type')
                            <div class="error-message invalid-feedback"
                                 style="font-size: 15px">{{ $message }}</div>
                            @enderror
                        </div>
                        @if(isset($state['arrest_type']) &&  in_array($state['arrest_type'],['محكوم','موقوف']))
                            <div class="form-group col-md-4 mb-4">
                                <label for="judgment_in_lifetime">
                                    الحكم
                                    {{$state['arrest_type'] == 'موقوف' ? 'المتوقع' : null}}
                                    بالمؤبدات
                                </label>
                                <input wire:model="state.judgment_in_lifetime"
                                       type="number"
                                       class="form-control @error('judgment_in_lifetime') is-invalid @enderror"
                                       placeholder="أرقام (اختياري)"
                                       id="judgment_in_lifetime">
                                @error('judgment_in_lifetime')
                                <div class="error-message invalid-feedback"
                                     style="font-size: 15px">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4 mb-4">
                                <label for="judgment_in_years">
                                    الحكم
                                    {{$state['arrest_type'] == 'موقوف' ? 'المتوقع' : null}}
                                    بالسنوات
                                </label>
                                <input wire:model="state.judgment_in_years" type="number"
                                       class="form-control @error('judgment_in_years') is-invalid @enderror"
                                       placeholder="أرقام (اختياري)"
                                       id="judgment_in_years">
                                @error('judgment_in_years')
                                <div class="error-message invalid-feedback"
                                     style="font-size: 15px">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4 mb-4">
                                <label for="judgment_in_months">
                                    الحكم
                                    {{$state['arrest_type'] == 'موقوف' ? 'المتوقع' : null}}
                                    بالأشهر
                                </label>
                                <input wire:model="state.judgment_in_months" type="number"
                                       class="form-control @error('judgment_in_months') is-invalid @enderror"
                                       placeholder="أرقام (اختياري)"
                                       id="judgment_in_months">
                                @error('judgment_in_months')
                                <div class="error-message invalid-feedback"
                                     style="font-size: 15px">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        @if(isset($state['gender']))
                            <div class="form-group col-md-6 mb-4">
                                <label for="social_type">الحالة الإجتماعية</label>
                                <select wire:model.live="state.social_type"
                                        class="form-select @error('social_type') is-invalid @enderror"
                                        id="social_type">
                                    <option>إختر...</option>
                                    @foreach(\App\Enums\SocialType::cases() as $row)
                                        <option value="{{$row->value}}">{{$row->value}}</option>
                                    @endforeach
                                </select>
                                @error('social_type')
                                <div class="error-message invalid-feedback"
                                     style="font-size: 15px">{{ $message }}</div>
                                @enderror
                            </div>
                            @if(isset($state['social_type']) && !in_array($state['social_type'],['أعزب','إختر...']))
                                <div
                                    class="form-group @if($state['social_type'] == "مطلق") col-md-12 @else col-md-6 @endif mb-4">
                                    <label for="number_of_children">عدد الأبناء</label>
                                    <input wire:model="state.number_of_children" type="text"
                                           class="form-control @error('number_of_children') is-invalid @enderror"
                                           id="number_of_children"
                                           placeholder="عدد الأبناء">
                                    @error('number_of_children')
                                    <div class="error-message invalid-feedback"
                                         style="font-size: 15px">{{ $message }}</div>
                                    @enderror
                                </div>
                                @if($state['social_type'] !== "مطلق")
                                    @if($state['gender'] === 'ذكر')
                                        <div class="form-group col-md-6 mb-4">
                                            <label for="wife_type" class="d-block">عدد الزوجات</label>
                                            <div class="bg-white p-2">
                                                @foreach(\App\Enums\WifeType::cases() as $row)
                                                    <div
                                                        class="form-check form-check-primary form-check-inline">
                                                        <input class="form-check-input" type="radio"
                                                               name="wife_type"
                                                               wire:model="state.wife_type"
                                                               value="{{$row->value}}" id="wife_type">
                                                        <label class="form-check-label" for="wife_type">
                                                            {{$row->value}}
                                                        </label>
                                                    </div>
                                                @endforeach
                                                @error('wife_type')
                                                <div class="error-message invalid-feedback"
                                                     style="font-size: 15px">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endif
                        @endif
                        <div class="form-group col-md-12 mb-4">
                            <label for="SpecialCase">حالة خاصة</label>
                            <div id="toggleSpecialCase" class="SpecialCase">
                                <div class="card">
                                    <div class="card-header" id="headingSpecialCase" wire:ignore.self>
                                        <section class="mb-0 mt-0">
                                            <div role="menu"
                                                 class="collapsed d-flex justify-content-between"
                                                 data-bs-toggle="collapse"
                                                 data-bs-target="#defaultSpecialCase"
                                                 aria-expanded="false"
                                                 aria-controls="defaultSpecialCase">
                                                <p class="p-0 m-0">إختر...</p>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                     height="24"
                                                     viewBox="0 0 24 24" fill="none"
                                                     stroke="currentColor"
                                                     stroke-width="2" stroke-linecap="round"
                                                     stroke-linejoin="round"
                                                     class="feather feather-chevron-down">
                                                    <polyline points="6 9 12 15 18 9"></polyline>
                                                </svg>
                                            </div>
                                        </section>
                                    </div>
                                    <div id="defaultSpecialCase" class="collapse"
                                         aria-labelledby="headingSpecialCase"
                                         wire:ignore.self
                                         data-bs-parent="#toggleSpecialCase">
                                        <div class="card-body">
                                            <div class="row">
                                                @foreach(\App\Enums\SpecialCase::cases() as $index => $row)
                                                    @if($row->value == 'حامل')
                                                        @if(isset($state['gender']) && $state['gender'] == "انثى")
                                                            <div class="col-md-4 mb-4">
                                                                <div
                                                                    class="form-check form-check-dark form-check-inline">
                                                                    <input class="form-check-input"
                                                                           wire:model.live="state.special_case.{{$row->value}}"
                                                                           type="checkbox"
                                                                           id="form-check-dark">
                                                                    <label class="form-check-label"
                                                                           for="form-check-dark">
                                                                        {{$row->value}}
                                                                    </label>
                                                                </div>
                                                                @error('state.special_case.' . $index)
                                                                <div class="error-message invalid-feedback"
                                                                     style="font-size: 15px">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="col-md-4 mb-4">
                                                            <div
                                                                class="form-check form-check-dark form-check-inline">
                                                                <input class="form-check-input"
                                                                       wire:model.live="state.special_case.{{$row->value}}"
                                                                       type="checkbox"
                                                                       id="form-check-dark">
                                                                <label class="form-check-label"
                                                                       for="form-check-dark">
                                                                    {{$row->value}}
                                                                </label>
                                                            </div>
                                                            @error('state.special_case.' . $index)
                                                            <div class="error-message invalid-feedback"
                                                                 style="font-size: 15px">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(isset($state['special_case']) && !empty(array_filter($state['special_case'])))
                            @php
                                $data = array_filter($state['special_case']);
                            @endphp
                            @if(in_array("مريض",array_filter(array_keys($data))))
                                <div class="form-group col-md-12 mb-4">
                                    <label for="health_note">وصف المرض</label>
                                    <textarea id="health_note" rows="3"
                                              class="form-control @error('health_note') is-invalid @enderror"
                                              placeholder="اكتب وصف المرض..."
                                              wire:model="state.health_note"></textarea>
                                    @error('health_note')
                                    <div class="error-message invalid-feedback"
                                         style="font-size: 15px">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                            @if(in_array("أقارب معتقلين",array_filter(array_keys($data))))
                                <div class="form-group col-md-12 mb-4">
                                    <label for="FamilyArrested">أقارب معتقلين</label>
                                    <div id="toggleFamilyArrested" class="FamilyArrested">
                                        <div class="card">
                                            <div class="card-header" id="headingFamilyArrested"
                                                 wire:ignore.self>
                                                <section class="mb-0 mt-0">
                                                    <div role="menu"
                                                         class="collapsed d-flex justify-content-between"
                                                         data-bs-toggle="collapse"
                                                         data-bs-target="#defaultFamilyArrested"
                                                         aria-expanded="false"
                                                         aria-controls="defaultFamilyArrested">
                                                        <p class="p-0 m-0">إختر...</p>
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                             height="24"
                                                             viewBox="0 0 24 24" fill="none"
                                                             stroke="currentColor"
                                                             stroke-width="2" stroke-linecap="round"
                                                             stroke-linejoin="round"
                                                             class="feather feather-chevron-down">
                                                            <polyline points="6 9 12 15 18 9"></polyline>
                                                        </svg>
                                                    </div>
                                                </section>
                                            </div>
                                            <div id="defaultFamilyArrested" class="collapse"
                                                 aria-labelledby="headingFamilyArrested"
                                                 wire:ignore.self
                                                 data-bs-parent="#toggleFamilyArrested">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-4">
                                                            <div
                                                                class="form-check form-check-dark form-check-inline">
                                                                <input class="form-check-input"
                                                                       wire:model.live="state.father_arrested"
                                                                       type="checkbox"
                                                                       id="form-check-dark">
                                                                <label class="form-check-label"
                                                                       for="form-check-dark">
                                                                    الأب
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-4">
                                                            <div
                                                                class="form-check form-check-dark form-check-inline">
                                                                <input class="form-check-input"
                                                                       wire:model.live="state.mother_arrested"
                                                                       type="checkbox"
                                                                       id="form-check-dark">
                                                                <label class="form-check-label"
                                                                       for="form-check-dark">
                                                                    الأم
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-4">
                                                            <div
                                                                class="form-check form-check-dark form-check-inline">
                                                                <input class="form-check-input"
                                                                       wire:model.live="state.husband_arrested"
                                                                       type="checkbox"
                                                                       id="form-check-dark">
                                                                <label class="form-check-label"
                                                                       for="form-check-dark">
                                                                    الزوج
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-4">
                                                            <div
                                                                class="form-check form-check-dark form-check-inline">
                                                                <input class="form-check-input"
                                                                       wire:model.live="state.wife_arrested"
                                                                       type="checkbox"
                                                                       id="form-check-dark">
                                                                <label class="form-check-label"
                                                                       for="form-check-dark">
                                                                    الزوجة
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-4">
                                                            <div class="form-group">
                                                                <label for="brother_arrested"
                                                                       style="display: inline-block;">
                                                                    أخ
                                                                </label>
                                                                <input class="form-control form-control-sm"
                                                                       wire:model.live="state.brother_arrested"
                                                                       type="number"
                                                                       id="brother_arrested"
                                                                       style="width: 80px; display: inline-block;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-4">
                                                            <div class="form-group">
                                                                <label for="brother_arrested"
                                                                       style="display: inline-block;">
                                                                    أخت
                                                                </label>
                                                                <input class="form-control form-control-sm"
                                                                       wire:model.live="state.sister_arrested"
                                                                       type="number"
                                                                       id="brother_arrested"
                                                                       style="width: 80px; display: inline-block;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-4">
                                                            <div class="form-group">
                                                                <label for="brother_arrested"
                                                                       style="display: inline-block;">
                                                                    ابن
                                                                </label>
                                                                <input class="form-control form-control-sm"
                                                                       wire:model.live="state.son_arrested"
                                                                       type="number"
                                                                       id="brother_arrested"
                                                                       style="width: 80px; display: inline-block;">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-4">
                                                            <div class="form-group">
                                                                <label for="brother_arrested"
                                                                       style="display: inline-block;">
                                                                    ابنه
                                                                </label>
                                                                <input class="form-control form-control-sm"
                                                                       wire:model.live="state.daughter_arrested"
                                                                       type="number"
                                                                       id="brother_arrested"
                                                                       style="width: 80px; display: inline-block;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                        <div class="form-group col-md-12 mb-4">
                            <label for="education_level">المستوى التعليمي</label>
                            <select wire:model.live="state.education_level"
                                    class="form-select @error('education_level') is-invalid @enderror"
                                    id="education_level">
                                <option>إختر...</option>
                                @foreach(\App\Enums\EducationLevel::cases() as $row)
                                    <option value="{{$row->value}}">{{$row->value}}</option>
                                @endforeach
                            </select>
                            @error('education_level')
                            <div class="error-message invalid-feedback"
                                 style="font-size: 15px">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6 mb-4">
                            <label for="first_phone_number">رقم التواصل (واتس/تلجرام)</label>
                            <input wire:model="state.first_phone_number" type="text"
                                   class="form-control @error('first_phone_number') is-invalid @enderror"
                                   id="first_phone_number"
                                   placeholder="رقم التواصل مع الأهل">
                            @error('first_phone_number')
                            <div class="error-message invalid-feedback"
                                 style="font-size: 15px">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6 mb-4">
                            <label for="first_phone_owner">اسم صاحب الرقم (واتس/تلجرام)</label>
                            <input wire:model="state.first_phone_owner" type="text"
                                   class="form-control @error('first_phone_owner') is-invalid @enderror"
                                   id="first_phone_owner"
                                   placeholder="اسم صاحب الرقم">
                            @error('first_phone_owner')
                            <div class="error-message invalid-feedback"
                                 style="font-size: 15px">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6 mb-4">
                            <label for="second_phone_number">رقم التواصل الإضافي</label>
                            <input wire:model="state.second_phone_number" type="text"
                                   class="form-control @error('second_phone_number') is-invalid @enderror"
                                   id="second_phone_number"
                                   placeholder="رقم التواصل مع الأهل">
                            @error('second_phone_number')
                            <div class="error-message invalid-feedback"
                                 style="font-size: 15px">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6 mb-4">
                            <label for="second_phone_owner">اسم صاحب الرقم</label>
                            <input wire:model="state.second_phone_owner" type="text"
                                   class="form-control @error('second_phone_owner') is-invalid @enderror"
                                   id="second_phone_owner"
                                   placeholder="اسم صاحب الرقم">
                            @error('second_phone_owner')
                            <div class="error-message invalid-feedback"
                                 style="font-size: 15px">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6 mb-4">
                            <label for="email">البريد الإلكتروني</label>
                            <input wire:model="state.email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   placeholder="البريد الإلكتروني">
                            @error('email')
                            <div class="error-message invalid-feedback"
                                 style="font-size: 15px">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-12 mb-4">
                            <label for="notes">ملاحظات</label>
                            <textarea id="notes" rows="3"
                                      class="form-control @error('notes') is-invalid @enderror"
                                      placeholder="اكتب ملاحظاتك..."
                                      wire:model="state.notes"></textarea>
                            @error('notes')
                            <div class="error-message invalid-feedback"
                                 style="font-size: 15px">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-3 text-center">
                            <hr>
                            <h1>إعتقالات سابقة (إن وجد)</h1>
                            <hr>
                        </div>

                        @foreach($old_arrests as $key => $old_arrest)
                            <div class="accordion-item col-md-12 mx-auto mb-3 p-0" wire:key="{{$key}}_"
                                 wire:ignore.self>
                                <h2 class="accordion-header" id="panelsStayOpen-heading_{{$key}}">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#panelsStayOpen-collapse_{{$key}}"
                                            aria-expanded="true"
                                            aria-controls="panelsStayOpen-collapse_{{$key}}">
                                        إعتقال سابق {{$key + 1}}
                                        <a class="btn btn-danger mx-4"
                                           wire:click="removeOldArrest({{ $key }})">إزالة</a>
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapse_{{$key}}"
                                     class="accordion-collapse collapse show"
                                     wire:ignore.self
                                     aria-labelledby="panelsStayOpen-heading_{{$key}}">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <div class="form-group col-md-6 mb-4">
                                                <label style="font-weight: bold" for="old_arrest_start_date">بداية
                                                    الإعتقال</label>
                                                <input wire:model="old_arrests.{{$key}}.old_arrest_start_date"
                                                       type="date"
                                                       class="form-control"
                                                       id="old_arrest_start_date">
                                            </div>
                                            <div class="form-group col-md-6 mb-4">
                                                <label style="font-weight: bold" for="old_arrest_end_date">نهاية
                                                    الإعتقال</label>
                                                <input wire:model="old_arrests.{{$key}}.old_arrest_end_date"
                                                       type="date"
                                                       class="form-control"
                                                       id="old_arrest_end_date">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="d-flex justify-content-center align-content-center mb-5">
                            <a class="btn btn-primary mx-auto" wire:click="addOldArrest">إضافة إعتقال سابق
                                آخر</a>
                        </div>
                    </div>
                </div>
                <div class="m-3">
                    <button type="submit" class="btn {{$showEdit ? 'btn-warning' : 'btn-primary'}}">
                        حفظ
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" class="feather feather-save">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @if($state)
        <div class="modal modal fade" id="review" data-bs-backdrop="static" data-bs-keyboard="true" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog">
                <div class="modal-content bg-white">
                    <div class="modal-header {{$showEdit ? 'bg-warning' : 'bg-success'}}" style="margin: 5px;">
                        <h1 class="modal-title fs-5 text-white"
                            id="staticBackdropLabel">مراجعة البيانات</h1>
                        <button type="button" class="btn-close bg-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h5 class="text-dark m-3">هل أنت متأكد من المعلومات؟</h5>
                        <span class="text-danger m-3">
* تنبية:
<span class="text-dark">
    سيتم
    {{$showEdit ? 'تعديل' : 'إضافة'}}
    بيانات الأسير
    {{!empty($state['first_name']) ? $state['first_name'] : null}} {{!empty($state['last_name']) ? $state['last_name'] : null}}
</span>
</span>
                    </div>
                    <div class="modal-footer d-flex justify-content-start align-items-start">
                        <button type="submit" wire:click="ConfirmMassage"
                                class="btn {{$showEdit ? 'bg-warning' : 'bg-success'}}">
                            تأكيد
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-check-circle">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <polyline points="22 4 12 14.01 9 11.01"></polyline>
                            </svg>
                        </button>
                        <button type="button" class="btn btn-light-dark" data-bs-dismiss="modal">إلغاء</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@section('script')
    <script src="{{asset('plugins-rtl/apex/apexcharts.min.js')}}"></script>
    @vite(['resources/rtl/assets/js/widgets/modules-widgets.js'])
    <script src="{{asset('plugins-rtl/global/vendors.min.js')}}"></script>
    @vite(['resources/rtl/assets/js/custom.js'])
    <script>
        window.addEventListener('ReviewMassage', event => {
            $('#review').modal('show');
        })
        window.addEventListener('hideReviewMassage', event => {
            $('#review').modal('hide');
        })

        document.body.addEventListener('scroll-to-top', function () {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            document.body.addEventListener('create_massage', function () {
                Swal.fire(
                    {
                        title: 'نجاح',
                        text: 'تم إضافة الأسير بنجاح',
                        icon: 'success',
                        confirmButtonText: 'تم',
                    }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/dashboard/prisoners';
                    }
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            document.body.addEventListener('update_massage', function () {
                Swal.fire(
                    {
                        title: 'نجاح',
                        text: 'تم تعديل الأسير بنجاح',
                        icon: 'success',
                        confirmButtonText: 'تم',
                    }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '/dashboard/prisoners';
                    }
                });
            });
        });

        Livewire.hook('commit', ({succeed}) => {
            succeed(() => {
                setTimeout(() => {
                    const firstErrorMessage = document.querySelector('.error-message')

                    if (firstErrorMessage !== null) {
                        firstErrorMessage.scrollIntoView({block: 'center', inline: 'center'})
                    }
                }, 0)
            })
        })
    </script>
@endsection
