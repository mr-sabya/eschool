 <div class="main-menu">
     <div class="container">
         <div class="menu-container">
             <div class="logo">
                 <a href="#">
                     <div class="brand">
                         <div class="image">
                             <img src="{{ url('assets/frontend/images/kcgs-logo.png') }}" alt="">
                         </div>
                         <div class="text">
                             <h4>{{ $settings->school_name ?? 'School Name' }}</h4>
                             <p>{{ $settings->school_address ?? 'School Address' }}</p>
                         </div>
                     </div>
                 </a>
             </div>

             <nav class="menu navbar" id="main_menu">
                 <ul class="navbar-nav">
                     <li class="nav-item menu-item">
                         <a class="nav-link {{ Route::is('home') ? 'active' : '' }}" href="{{ route('home') }}" wire:navigate>প্রচ্ছদ</a>
                     </li>

                     <li class="nav-item menu-item dropdown">
                         <a class="nav-link dropdown-toggle {{ Route::is('teacher.index') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown"
                             aria-expanded="false">
                             প্রশাসন
                         </a>
                         <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                             <li>
                                 <a class="dropdown-item" title="শিক্ষকবৃন্দ" href="{{ route('teacher.index') }}" wire:navigate>শিক্ষকবৃন্দ</a>
                             </li>
                             <li>
                                 <a class="dropdown-item" title="শিক্ষকবৃন্দ" href="{{ route('governing-body.index') }}" wire:navigate>পরিচালনা পরিষদ</a>
                             </li>
                             <li>
                                 <a class="dropdown-item" title="শিক্ষকবৃন্দ" href="{{ route('staff.index') }}" wire:navigate>কর্মকর্তা কর্মচারী</a>
                             </li>
                             <li>
                                 <a class="dropdown-item" title="শিক্ষকবৃন্দ" href="#">প্রাক্তন প্রধান
                                     শিক্ষক</a>
                             </li>
                             <li>
                                 <a class="dropdown-item" title="শিক্ষকবৃন্দ" href="#">কৃতি শিক্ষার্থী</a>
                             </li>
                         </ul>
                     </li>

                     <li class="nav-item menu-item dropdown">
                         <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                             aria-expanded="false">
                             শিক্ষার্থীদের তথ্য
                         </a>
                         <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                             @foreach($classes as $class)
                             <li>
                                 <a class="dropdown-item" title="শিক্ষকবৃন্দ" href="{{ route('student.index', $class->numeric_name) }}" wire:navigate>Class {{ $class->name }}</a>
                             </li>
                             @endforeach
                         </ul>
                     </li>
                     <li class="nav-item menu-item dropdown">
                         <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                             aria-expanded="false">
                             ভর্তি
                         </a>
                         <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                             <li>
                                 <a class="dropdown-item" title="শিক্ষকবৃন্দ" href="{{ route('admission.info') }}" wire:navigate>ভর্তি তথ্য</a>
                             </li>
                             <li>
                                 <a class="dropdown-item" title="শিক্ষকবৃন্দ" href="{{ route('admission.form') }}" wire:navigate>ভর্তি ফরম</a>
                             </li>
                         </ul>
                     </li>
                     <li class="nav-item menu-item">
                         <a class="nav-link" href="#">রুটিন</a>
                     </li>
                     <li class="nav-item menu-item">
                         <a class="nav-link" href="#">ফলাফল</a>
                     </li>
                     <li class="nav-item menu-item dropdown">
                         <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                             aria-expanded="false">
                             বিভিন্ন তথ্য
                         </a>
                         <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                             <li>
                                 <a class="dropdown-item" title="শিক্ষকবৃন্দ" href="{{ route('media.photos') }}" wire:navigate>ফটোগ্যালারী</a>
                             </li>
                             <li>
                                 <a class="dropdown-item" title="শিক্ষকবৃন্দ" href="{{ route('media.videos') }}" wire:navigate>ভিডিও গ্যালারী</a>
                             </li>
                         </ul>
                     </li>

                     <li class="nav-item menu-item">
                         <a class="nav-link" href="#">যোগাযোগ</a>
                     </li>
                 </ul>
             </nav>
             <!-- login -->
             <div class="login">
                 @if (Route::has('login'))
                 @auth
                 <a href="{{ route('student.profile.index') }}" wire:navigate class="login-btn">Profile</a>
                 @else
                 <a href="{{ route('login') }}" wire:navigate class="login-btn">Login</a>
                 @endauth
                 @endif
             </div>

             <button class="menu-btn ms-2" id="menu_button"><i class="ri-menu-line"></i></button>

         </div>
     </div>
 </div>