<!----------__________slider start___________------------>
<div class="section_1 hero-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">

                <div class="hero-slider">
                    <div class="slider">

                        @forelse($banners as $banner)
                        {{-- Dynamic Banners from Database --}}
                        <div class="item">
                            <!-- Post Image Code Start-->
                            <img width="1000" height="300"
                                src="{{ asset('storage/' . $banner->image) }}"
                                class="attachment-post-thumbnail size-post-thumbnail wp-post-image"
                                alt="{{ $banner->title }}" />
                            <!-- Post Image Code Close-->

                            @if($banner->title)
                            <h4 class="centered">{{ $banner->title }}</h4>
                            @endif
                        </div>
                        @empty
                        {{-- Fallback: Keep one image if no banner exists in DB --}}
                        <div class="item">
                            <img width="1000" height="300"
                                src="{{ url('assets/frontend/images/web.jpg') }}"
                                class="attachment-post-thumbnail size-post-thumbnail wp-post-image"
                                alt="Default Banner" />
                            <h4 class="centered">Welcome to our Website</h4>
                        </div>
                        @endforelse

                    </div>

                    <!-- Left and right controls -->
                    <div id="custom-dots"></div>
                    <div id="custom-arrows"></div>
                </div>
            </div>
        </div>
    </div>
</div>