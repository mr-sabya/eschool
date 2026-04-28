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

                <livewire:frontend.home.quote.index />

                <livewire:frontend.home.extra-links.index />

                <!--------------add option close----------------------->
            </div>

            <!-- sidebar -->
            <div class="col-lg-3 col-md-3 col-sm-3">

                <div class="academy_information">
                    <div class="menu-sidebar-container">
                        <ul id="menu-sidebar" class="menu">
                            <li id="menu-item-134"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-134">
                                <a href="{{ route('admission.info') }}">ভর্তি
                                    তথ্য</a>
                            </li>
                            <li id="menu-item-135"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-135">
                                <a href="{{ route('admission.form') }}">ভর্তি
                                    ফরম</a>
                            </li>
                            <li id="menu-item-131"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-131">
                                <a href="{{ route('media.photos') }}">ফটোগ্যালারী</a>
                            </li>
                            <li id="menu-item-136"
                                class="menu-item menu-item-type-post_type menu-item-object-page menu-item-136">
                                <a href="{{ route('media.videos') }}" wire:navigate>ভিডিও
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
                            <li><a href="{{ route('notice.details', $notice->id) }}" wire:navigate>{{ $notice->title}}</a></li>
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
                                <a href="http://www.moedu.gov.bd/" wire:navigate>শিক্ষা মন্ত্রণালয়</a>
                            </li>
                            <li id="menu-item-138"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-138">
                                <a href="http://www.educationboardresults.gov.bd/" wire:navigate>এসএসসি/এইচএসসি ফলাফল</a>
                            </li>
                            <li id="menu-item-139"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-139">
                                <a href="http://banbeis.gov.bd/new/" wire:navigate>ব্যানবেইজ</a>
                            </li>
                            <li id="menu-item-140"
                                class="menu-item menu-item-type-custom menu-item-object-custom menu-item-140">
                                <a href="http://www.seqaep.gov.bd/" wire:navigate>সেকায়েপ</a>
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