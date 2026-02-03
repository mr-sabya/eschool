<!----------__________ scrool start ___________------------>
<section class="section_1">
    <div class="container">

        <!----------__________ add close ___________------------>

        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-9 ">

                <div class="row mb-4">
                    <div class="col-lg-12 col-md-12 col-sm-12">

                        <div class="history row g-3">
                            @if($history)
                            <div class="col-lg-5">
                                <div class="card h-100">
                                    <!-- Use asset() for the image path -->
                                    <img src="{{ asset('storage/' . $history->image) }}"
                                        class="attachment-post-thumbnail"
                                        alt="{{ $history->title }}" />
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="catagory_title underline">{{ $history->title }}</h4>
                                        
                                        <p>{!! $history->description !!}</p>
                                    </div>
                                </div>
                            </div>
                            @else
                            <p>No history records found.</p>
                            @endif
                        </div>
                    </div>
                </div>

                </br>

                <div class="row mb-4 quote">
                    <div class="col-lg-12">
                        <div class="title mb-3">
                            <h4 class="catagory_title underline"> অধ্যক্ষের বাণী</h4>
                            <div id="quote_slider_arrow"></div>
                        </div>
                        <div class="slider-area">
                            <div class="quote-slider">
                                <div class="item">
                                    <div class="quote-text">
                                        <div class="icon"><i class="ri-double-quotes-l"></i></div>
                                        <p> মাধ্যমিক ও উচ্চ মাধ্যমিক শিক্ষা বোর্ড, যশোর জনগণের দোরগোড়ায় শিক্ষা
                                            সেবা
                                            পৌঁছে দেবার
                                            লক্ষ্যে যাবতীয় কার্যাদী সম্পাদনে  ডিজিটাল প্রযুক্তি ব্যবহার  ও যশোর
                                            শিক্ষাবোর্ডের
                                            অধীন সকল প্রতিষ্ঠানের তথ্য অনলাইনে প্রেরণের ব্যবস্থা নেওয়া হয়েছে
                                            জেনে
                                            আমি
                                            আনন্দিত।
                                            বৃটিশ ঔপনিবেশিক আমলে মহৎপ্রাণ ব্যক্তি বাবু মথুরানাথ কুন্ডু মহাশয়ের
                                            প্রচেষ্টায় ১৮৫৬
                                            খ্রিঃ প্রতিষ্ঠিত হয়েছিল ঐতিহ্যবাহী কুমারখালী এম এন পাইলট (মডেল)

                                            <a href="#">বিস্তারিত...</a>

                                        </p>
                                    </div>
                                    <div class="person">
                                        <div class="image">

                                            <img width="400" height="500" src="{{ url('assets/frontend/images/person.jpg') }}"
                                                class="attachment-post-thumbnail size-post-thumbnail" alt="" />
                                        </div>
                                        <div class="text">
                                            <h4 class="mb-0">অধ্যক্ষ</h4>
                                            <p class="mb-0">কুমারখালী এম এন পাইলট</p>
                                            <p class="mb-0">কুমারখালী</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="quote-text">
                                        <div class="icon"><i class="ri-double-quotes-l"></i></div>
                                        <p> মাধ্যমিক ও উচ্চ মাধ্যমিক শিক্ষা বোর্ড, যশোর জনগণের দোরগোড়ায় শিক্ষা
                                            সেবা
                                            পৌঁছে দেবার
                                            লক্ষ্যে যাবতীয় কার্যাদী সম্পাদনে  ডিজিটাল প্রযুক্তি ব্যবহার  ও যশোর
                                            শিক্ষাবোর্ডের
                                            অধীন সকল প্রতিষ্ঠানের তথ্য অনলাইনে প্রেরণের ব্যবস্থা নেওয়া হয়েছে
                                            জেনে
                                            আমি
                                            আনন্দিত।
                                            বৃটিশ ঔপনিবেশিক আমলে মহৎপ্রাণ ব্যক্তি বাবু মথুরানাথ কুন্ডু মহাশয়ের
                                            প্রচেষ্টায় ১৮৫৬
                                            খ্রিঃ প্রতিষ্ঠিত হয়েছিল ঐতিহ্যবাহী কুমারখালী এম এন পাইলট (মডেল)

                                            <a href="#">বিস্তারিত...</a>

                                        </p>
                                    </div>
                                    <div class="person">
                                        <div class="image">

                                            <img width="400" height="500" src="{{ url('assets/frontend/images/person.jpg') }}"
                                                class="attachment-post-thumbnail size-post-thumbnail" alt="" />
                                        </div>
                                        <div class="text">
                                            <h4 class="mb-0">অধ্যক্ষ</h4>
                                            <p class="mb-0">কুমারখালী এম এন পাইলট</p>
                                            <p class="mb-0">কুমারখালী</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="quote-text">
                                        <div class="icon"><i class="ri-double-quotes-l"></i></div>
                                        <p> মাধ্যমিক ও উচ্চ মাধ্যমিক শিক্ষা বোর্ড, যশোর জনগণের দোরগোড়ায় শিক্ষা
                                            সেবা
                                            পৌঁছে দেবার
                                            লক্ষ্যে যাবতীয় কার্যাদী সম্পাদনে  ডিজিটাল প্রযুক্তি ব্যবহার  ও যশোর
                                            শিক্ষাবোর্ডের
                                            অধীন সকল প্রতিষ্ঠানের তথ্য অনলাইনে প্রেরণের ব্যবস্থা নেওয়া হয়েছে
                                            জেনে
                                            আমি
                                            আনন্দিত।
                                            বৃটিশ ঔপনিবেশিক আমলে মহৎপ্রাণ ব্যক্তি বাবু মথুরানাথ কুন্ডু মহাশয়ের
                                            প্রচেষ্টায় ১৮৫৬
                                            খ্রিঃ প্রতিষ্ঠিত হয়েছিল ঐতিহ্যবাহী কুমারখালী এম এন পাইলট (মডেল)

                                            <a href="#">বিস্তারিত...</a>

                                        </p>
                                    </div>
                                    <div class="person">
                                        <div class="image">

                                            <img width="400" height="500" src="{{ url('assets/frontend/images/person.jpg') }}"
                                                class="attachment-post-thumbnail size-post-thumbnail" alt="" />
                                        </div>
                                        <div class="text">
                                            <h4 class="mb-0">অধ্যক্ষ</h4>
                                            <p class="mb-0">কুমারখালী এম এন পাইলট</p>
                                            <p class="mb-0">কুমারখালী</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <!----------__________ homemenu one start ___________------------>


                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <h4 class="catagory_title_1"> ছাত্রছাত্রীদের তথ্য</h4>
                                <div class="news_information">
                                    <img src="{{ url('assets/frontend/images/menu01.jpg') }}">
                                    <div class="menu-student-information-container">
                                        <ul id="menu-student-information" class="menu">
                                            <li id="menu-item-115"
                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-115">
                                                <a href="#">ছাত্রছাত্রীর
                                                    আসন সংখ্যা</a>
                                            </li>
                                            <li id="menu-item-117"
                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-117">
                                                <a href="#">ভর্তি
                                                    তথ্য</a>
                                            </li>
                                            <li id="menu-item-116"
                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-116">
                                                <a href="#">নোটিশ</a>
                                            </li>
                                            <li id="menu-item-118"
                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-118">
                                                <a href="#">রুটিন</a>
                                            </li>
                                            <li id="menu-item-114"
                                                class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-114">
                                                <a href="#">কৃতি
                                                    শিক্ষার্থী</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!----------__________ homemenu two start ___________------------>


                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <h4 class="catagory_title_2"> শিক্ষকদের তথ্য</h4>
                                <div class="news_information">
                                    <img src="{{ url('assets/frontend/images/menu01.jpg') }}">
                                    <div class="menu-teachers-information-container">
                                        <ul id="menu-teachers-information" class="menu">
                                            <li id="menu-item-120"
                                                class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-120">
                                                <a href="#">শিক্ষকবৃন্দ</a>
                                            </li>
                                            <li id="menu-item-121"
                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-121">
                                                <a href="#">শূণ্যপদের
                                                    তালিকা</a>
                                            </li>
                                            <li id="menu-item-119"
                                                class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-119">
                                                <a href="#">প্রাক্তন
                                                    প্রধান শিক্ষক</a>
                                            </li>
                                            <li id="menu-item-122"
                                                class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-122">
                                                <a href="#">কর্মকর্তা
                                                    কর্মচারী</a>
                                            </li>
                                            <li id="menu-item-123"
                                                class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-123">
                                                <a href="#">পরিচালনা
                                                    পরিষদ</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!----------__________ home menu Three start ___________------------>


                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <h4 class="catagory_title_3"> ডাউনলোড</h4>
                                <div class="news_information">
                                    <img src="{{ url('assets/frontend/images/menu03.jpg') }}">
                                    <ul>

                                        <li><a href="#">১ম
                                                সাময়িকি পরীক্ষার রুটিন ডাউনলোড</a></li>

                                        <li><a href="#">এসএসসি
                                                পরীক্ষার রুটিন ডাউনলোড</a></li>

                                        <li><a href="#">ছুটির
                                                নোটিশ ডাউনলোড</a></li>

                                        <li><a href="#">ভর্তি
                                                ফরম ডাউনলোড</a></li>

                                        <li><a href="#">পরীক্ষার
                                                রুটিন ডাউনলোড</a></li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!----------__________ homemenu four start ___________------------>

                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <h4 class="catagory_title_4"> একাডেমীক তথ্য </h4>
                                <div class="news_information">
                                    <img src="{{ url('assets/frontend/images/menu04.jpg')}}">
                                    <div class="menu-academic-information-container">
                                        <ul id="menu-academic-information" class="menu">
                                            <li id="menu-item-124"
                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-124">
                                                <a href="#">কক্ষ
                                                    সংখ্যা</a>
                                            </li>
                                            <li id="menu-item-125"
                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-125">
                                                <a href="#">ছাত্রছাত্রীর
                                                    আসন সংখ্যা</a>
                                            </li>
                                            <li id="menu-item-126"
                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-126">
                                                <a href="#">ছুটির
                                                    তালিকা</a>
                                            </li>
                                            <li id="menu-item-128"
                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-128">
                                                <a href="#">মাল্টিমিডিয়া
                                                    ক্লাসরুম</a>
                                            </li>
                                            <li id="menu-item-129"
                                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-129">
                                                <a href="#">যানবাহন
                                                    সুবিধা</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--------------add option start----------------------->

                <div class="row">
                    <div class="col-md-12">
                        <div class="add">
                        </div>
                    </div>
                </div>

                <!--------------add option close----------------------->
            </div>


            <div class="col-lg-3 col-md-3 col-sm-3">

                <div class="academy_information">
                    <div class="menu-sidebar-container">
                        <ul id="menu-sidebar" class="menu">
                            <li id="menu-item-134"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-134">
                                <a href="#">ভর্তি
                                    তথ্য</a>
                            </li>
                            <li id="menu-item-135"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-135">
                                <a href="#">ভর্তি
                                    ফরম</a>
                            </li>
                            <li id="menu-item-131"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-131">
                                <a href="#">ফটোগ্যালারী</a>
                            </li>
                            <li id="menu-item-136"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-136">
                                <a href="#">ভিডিও
                                    গ্যালারী</a>
                            </li>
                        </ul>
                    </div>
                </div>


                <!----------__________ Notice start ___________------------>


                <h4 class="catagory_title_5"> নোটিশ বোর্ড</h4>
                <div class="notice_box">
                    @if($notices->count() > 0)
                    <marquee direction="up" scrollamount="3px" onmouseover="this.stop()" onmouseout="this.start()">
                        <ul>
                            @foreach($notices as $notice)
                            <li><a href="#">{{ $notice->title}}</a></li>
                            @endforeach
                        </ul>
                    </marquee>
                    @else
                    <p>No notice published</p>
                    @endif
                </div>

                <!----------__________ add start ___________------------>

                <div class="row">
                    <div class="col-md-12">
                        <div class="add">
                        </div>
                    </div>
                </div>

                <!----------__________ add close ___________------------>


                <!----------__________ Sidemenu Two start ___________------------>



                <h4 class="catagory_title_5"> অফিসিয়াল লিংক</h4>

                <div class="notice_box">
                    <div class="menu-official-link-container">
                        <ul id="menu-official-link" class="menu">
                            <li id="menu-item-137"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-137">
                                <a href="http://www.moedu.gov.bd/">শিক্ষা মন্ত্রণালয়</a>
                            </li>
                            <li id="menu-item-138"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-138">
                                <a href="http://www.educationboardresults.gov.bd/">এসএসসি/এইচএসসি ফলাফল</a>
                            </li>
                            <li id="menu-item-139"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-139">
                                <a href="http://banbeis.gov.bd/new/">ব্যানবেইজ</a>
                            </li>
                            <li id="menu-item-140"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-140">
                                <a href="http://www.seqaep.gov.bd/">সেকায়েপ</a>
                            </li>
                        </ul>
                    </div>

                </div>




                <!----------__________ Sidemenu Three start ___________------------>



                <h4 class="catagory_title_5"> গুরুত্বপূর্ণ তথ্য</h4>

                <div class="notice_box">
                    <div class="menu-important-link-container">
                        <ul id="menu-important-link" class="menu">
                            <li id="menu-item-142"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-142">
                                <a href="http://www.ebook.gov.bd/">ই-বুক</a>
                            </li>
                            <li id="menu-item-143"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-143">
                                <a href="https://www.teachers.gov.bd/">শিক্ষক বাতায়ন</a>
                            </li>
                            <li id="menu-item-144"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-144">
                                <a href="http://mmc.e-service.gov.bd/">মাল্টিমিডিয়া ক্লাসরুম
                                    ম্যানেজমেন্ট</a>
                            </li>
                            <li id="menu-item-144"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-144">
                                <a href="http://mmc.e-service.gov.bd/">মাল্টিমিডিয়া ক্লাসরুম
                                    ম্যানেজমেন্ট</a>
                            </li>
                            <li id="menu-item-144"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-144">
                                <a href="http://mmc.e-service.gov.bd/">মাল্টিমিডিয়া ক্লাসরুম
                                    ম্যানেজমেন্ট</a>
                            </li>
                        </ul>
                    </div>

                </div>



            </div>

        </div>

    </div>
</section>