<div>
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">{{ $isEdit ? 'তথ্য এডিট' : 'নতুন প্রধান শিক্ষক যোগ' }}</h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label class="form-label">নাম</label>
                            <input type="text" class="form-control" wire:model="name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">শিক্ষাগত যোগ্যতা</label>
                            <input type="text" class="form-control" wire:model="qualification">
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">যোগদান</label>
                                <input type="date" class="form-control" wire:model="joining_date">
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label">অব্যাহতি</label>
                                <input type="date" class="form-control" wire:model="leaving_date">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">সিরিয়াল (Rank)</label>
                            <input type="number" class="form-control" wire:model="rank">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ছবি</label>
                            @if($new_image)
                                <img src="{{ $new_image->temporaryUrl() }}" class="d-block mb-2 rounded shadow-sm" width="100">
                            @elseif($image)
                                <img src="{{ asset('storage/'.$image) }}" class="d-block mb-2 rounded shadow-sm" width="100">
                            @endif
                            <input type="file" class="form-control" wire:model="new_image">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">সংরক্ষণ করুন</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ছবি</th>
                                <th>নাম ও যোগ্যতা</th>
                                <th>সময়কাল</th>
                                <th class="text-end">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($headmasters as $hm)
                            <tr>
                                <td><img src="{{ asset('storage/'.$hm->image) }}" class="rounded" width="50" height="60" style="object-fit: cover;"></td>
                                <td>
                                    <div class="fw-bold">{{ $hm->name }}</div>
                                    <small class="text-muted">{{ $hm->qualification }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info-subtle text-info">
                                        {{ $hm->joining_date?->format('d M, Y') }} - {{ $hm->leaving_date?->format('d M, Y') ?? 'বর্তমান' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <button wire:click="edit({{ $hm->id }})" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></button>
                                    <button onclick="confirm('মুছে ফেলতে চান?') || event.stopImmediatePropagation()" wire:click="delete({{ $hm->id }})" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>