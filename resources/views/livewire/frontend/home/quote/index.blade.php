<div class="row mb-4 quote">
    <div class="col-lg-12">
        <div class="title mb-3">
            <h4 class="catagory_title underline">বাণী</h4>
            <div id="quote_slider_arrow"></div>
        </div>
        <div class="slider-area">
            <div class="quote-slider">

                @forelse($quotes as $quote)
                <div class="item">
                    <div class="quote-text">
                        <div class="icon"><i class="ri-double-quotes-l"></i></div>
                        <p>
                            {{ $quote->message }}
                            {{-- Link to a detail page if you have one, or use # --}}
                            <a href="#">বিস্তারিত...</a>
                        </p>
                    </div>
                    <div class="person">
                        <div class="image">
                            <img width="400" height="500"
                                src="{{ $quote->image ? asset('storage/' . $quote->image) : url('assets/frontend/images/person.jpg') }}"
                                class="attachment-post-thumbnail size-post-thumbnail"
                                alt="{{ $quote->name }}" />
                        </div>
                        <div class="text">
                            <h4 class="mb-0">{{ $quote->name }}</h4>
                            <p class="mb-0 text-muted fw-bold">{{ $quote->designation }}</p>
                            <p class="mb-0">{{ $quote->institution }}</p>
                            <p class="mb-0">{{ $quote->location }}</p>
                        </div>
                    </div>
                </div>
                @empty
                {{-- Optional: Show nothing or a default message if no quotes are active --}}
                <div class="text-center p-4">তথ্য পাওয়া যায়নি।</div>
                @endforelse

            </div>
        </div>
    </div>
</div>