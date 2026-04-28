<div class="extra-links">
    {{-- Loop through categories in chunks of 2 to maintain the row structure --}}
    @foreach($categories->chunk(2) as $chunk)
    <div class="row">
        @foreach($chunk as $category)
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    {{-- Use $loop->parent->iteration combined with nested loops if needed, 
                                 or just a continuous counter --}}
                    <h4 class="catagory_title_{{ $category->order ?? $loop->iteration }}">
                        {{ $category->title }}
                    </h4>

                    <div class="news_information">
                        <img src="{{ $category->image ? asset('storage/' . $category->image) : url('assets/frontend/images/menu01.jpg') }}">

                        <div class="menu-container">
                            <ul class="menu">
                                @foreach($category->links as $link)
                                <li class="menu-item">
                                    <a href="{{ $link->url }}">{{ $link->title }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endforeach
</div>