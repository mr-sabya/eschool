<div class="container my-5">
    @if(session()->has('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($info)
    <div class="admission-section">
        <!-- Renders the HTML content -->
        {!! $info->content !!}

        @if($info->form_path)
        <div class="text-center mt-4">
            <!-- wire:click calls the download() function in the component -->
            <button wire:click="download" class="btn btn-primary btn-lg">
                <i class="fas fa-download me-2"></i> ভর্তি ফরম সংগ্রহ করুন
            </button>

            <!-- Loading Indicator -->
            <div wire:loading wire:target="download" class="text-muted mt-2">
                <span class="spinner-border spinner-border-sm"></span> প্রসেসিং হচ্ছে...
            </div>
        </div>
        @endif
    </div>
    @else
    <div class="text-center py-5">
        <h3 class="text-muted">ভর্তি সংক্রান্ত কোনো তথ্য পাওয়া যায়নি।</h3>
    </div>
    @endif
</div>