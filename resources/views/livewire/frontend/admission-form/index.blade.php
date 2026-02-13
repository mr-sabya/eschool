<div class="container my-5">
    <div class="admission-section text-center">

        <div class="download-card p-5 border rounded bg-light shadow-sm">
            <div class="mb-4">
                <i class="fas fa-file-pdf fa-4x text-danger"></i>
            </div>

            <h2 class="mb-3">ভর্তি ফরম সংগ্রহ করুন</h2>
            <p class="lead mb-4">
                আপনি যদি অফলাইনে ভর্তি হতে চান, তবে নিচের বাটন থেকে PDF ফরমটি ডাউনলোড করুন। <br>
                ফরমটি পূরণ করে প্রয়োজনীয় কাগজপত্রসহ স্কুল অফিসে সরাসরি যোগাযোগ করুন।
            </p>

            @if($hasForm)
            <button wire:click="download" class="btn btn-success btn-lg px-5 py-3">
                <i class="fas fa-download me-2"></i> ভর্তি ফরম ডাউনলোড করুন (PDF)
            </button>

            <div wire:loading wire:target="download" class="mt-3 text-primary">
                <div class="spinner-border spinner-border-sm me-2"></div> ডাউনলোড প্রসেসিং হচ্ছে...
            </div>
            @else
            <div class="alert alert-warning d-inline-block">
                <i class="fas fa-exclamation-triangle me-2"></i> বর্তমানে ডাউনলোডের জন্য কোনো ফরম আপলোড করা নেই। অনুগ্রহ করে স্কুল অফিসে যোগাযোগ করুন।
            </div>
            @endif
        </div>

        <div class="mt-4 text-muted small">
            <p>📍 স্কুল অফিস: সকাল ৯টা – বিকেল ৩টা পর্যন্ত খোলা থাকে।</p>
        </div>
    </div>
</div>