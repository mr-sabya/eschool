<div>
    <div class="row">
        <!-- ফর্ম সেকশন: সদস্য যোগ বা এডিট করার জন্য -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="card-title mb-0">
                        {{ $isEdit ? 'সদস্য তথ্য আপডেট করুন' : 'নতুন সদস্য যোগ করুন' }}
                    </h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <!-- নাম -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">সদস্যের নাম <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="name" placeholder="পূর্ণ নাম লিখুন">
                            @error('name') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <!-- পদবী -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">পদবী <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" wire:model="designation" placeholder="উদা: সভাপতি, সাধারণ সম্পাদক">
                            @error('designation') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="row">
                            <!-- সিরিয়াল/র‍্যাঙ্ক -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">সিরিয়াল (Rank) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" wire:model="rank" placeholder="1, 2, 3...">
                                @error('rank') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <!-- ধরন -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">ধরন</label>
                                <select class="form-select" wire:model="type">
                                    <option value="current">বর্তমান</option>
                                    <option value="former">প্রাক্তন</option>
                                </select>
                            </div>
                        </div>

                        <!-- মোবাইল -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">মোবাইল নাম্বার (ঐচ্ছিক)</label>
                            <input type="text" class="form-control" wire:model="mobile" placeholder="০১XXXXXXXXX">
                        </div>

                        <!-- ছবি আপলোড -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">ছবি (300x300 রিকমেন্ডেড)</label>
                            <div class="image-preview mb-2">
                                @if ($new_image)
                                <img src="{{ $new_image->temporaryUrl() }}" class="rounded border shadow-sm" style="width: 100px; height: 100px; object-fit: cover;">
                                @elseif ($image)
                                <img src="{{ asset('storage/' . $image) }}" class="rounded border shadow-sm" style="width: 100px; height: 100px; object-fit: cover;">
                                @else
                                <div class="bg-light border rounded d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                    <i class="fas fa-user-circle fa-3x text-muted"></i>
                                </div>
                                @endif
                            </div>
                            <input type="file" class="form-control" wire:model="new_image" accept="image/*">
                            @error('new_image') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <!-- বাটনসমূহ -->
                        <div class="d-flex gap-2 border-top pt-3">
                            <button type="submit" class="btn btn-primary w-100">
                                <span wire:loading.remove wire:target="save">
                                    {{ $isEdit ? 'আপডেট করুন' : 'সংরক্ষণ করুন' }}
                                </span>
                                <span wire:loading wire:target="save">প্রসেসিং...</span>
                            </button>
                            @if($isEdit)
                            <button type="button" wire:click="$refresh" class="btn btn-secondary">বাতিল</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- লিস্ট সেকশন: সদস্যদের তালিকা দেখার জন্য -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">পরিচালনা পর্ষদ তালিকা</h5>
                    <span class="badge bg-info text-dark">মোট সদস্য: {{ $members->count() }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3">র‍্যাঙ্ক</th>
                                    <th>ছবি</th>
                                    <th>সদস্যের তথ্য</th>
                                    <th>ধরন</th>
                                    <th class="text-end pe-3">অ্যাকশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($members as $member)
                                <tr>
                                    <td class="ps-3 fw-bold text-muted">#{{ $member->rank }}</td>
                                    <td>
                                        <img src="{{ asset('storage/' . $member->image) }}" class="rounded-circle border" style="width: 45px; height: 45px; object-fit: cover;">
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $member->name }}</div>
                                        <div class="small text-primary">{{ $member->designation }}</div>
                                        <div class="small text-muted">{{ $member->mobile }}</div>
                                    </td>
                                    <td>
                                        @if($member->type == 'current')
                                        <span class="badge bg-success-subtle text-success">বর্তমান</span>
                                        @else
                                        <span class="badge bg-secondary-subtle text-secondary">প্রাক্তন</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-3">
                                        <button wire:click="edit({{ $member->id }})" class="btn btn-sm btn-outline-info me-1">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="confirm('আপনি কি নিশ্চিতভাবে এই সদস্যকে ডিলিট করতে চান?') || event.stopImmediatePropagation()"
                                            wire:click="delete({{ $member->id }})" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        কোনো সদস্যের তথ্য পাওয়া যায়নি।
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <style>
        .card {
            border-radius: 12px;
            overflow: hidden;
        }

        .table thead th {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-sm {
            border-radius: 6px;
        }

        .image-preview img {
            object-position: top;
        }
    </style>
</div>