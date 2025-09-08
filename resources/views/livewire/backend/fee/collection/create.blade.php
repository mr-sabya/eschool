<div>
    <div class="row">
        <!-- Left: Search Students -->
        <div class="col-md-4 mb-3">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="card-title m-0 text-white">Search Students</div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label>Class</label>
                        <select class="form-control" wire:change="classChanged($event.target.value)">
                            <option value="">-- Select Class --</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Section</label>
                        <select class="form-control" wire:change="sectionChanged($event.target.value)" @if(empty($sections)) disabled @endif>
                            <option value="">-- Select Section --</option>
                            @foreach($sections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="mb-3">
                        <label>Roll Number</label>
                        <input type="text" class="form-control" wire:model="roll_number">
                    </div>

                    <button class="btn btn-primary w-100" wire:click="loadStudents">Search</button>
                </div>
            </div>
        </div>

        <!-- Right: Student List & Payment Form -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary">
                    <div class="card-title m-0 text-white">Students</div>
                </div>
                <div class="card-body">
                    @if($students)
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Roll Number</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($students as $student)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $student->roll_number }}</td>
                                    <td>{{ $student->user->name ?? 'N/A' }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary" wire:click="selectStudent({{ $student->id }})">
                                            Take Payment
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No students found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @endif

                    @if($selectedStudent)
                    <h5>Payment for: {{ $selectedStudent->user->name }} (Roll: {{ $selectedStudent->roll_number }})</h5>
                    <form wire:submit.prevent="savePayment">
                        <div class="mb-3">
                            <label>Fee List</label>
                            <select wire:model="fee_list_id" class="form-control">
                                <option value="">-- Select Fee List --</option>
                                @foreach($feeLists as $feeList)
                                <option value="{{ $feeList->id }}">{{ $feeList->name }} - {{ number_format($feeList->amount,2) }}</option>
                                @endforeach
                            </select>
                            @error('fee_list_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Amount Paid</label>
                            <input type="number" class="form-control" wire:model="amount_paid">
                            @error('amount_paid') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Discount</label>
                            <input type="number" class="form-control" wire:model="discount">
                        </div>

                        <div class="mb-3">
                            <label>Fine</label>
                            <input type="number" class="form-control" wire:model="fine">
                        </div>

                        <div class="mb-3">
                            <label>Payment Date</label>
                            <input type="date" class="form-control" wire:model="payment_date">
                            @error('payment_date') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Payment Method</label>
                            <select wire:model="payment_method_id" class="form-control">
                                <option value="">-- Select Payment Method --</option>
                                @foreach($paymentMethods as $method)
                                <option value="{{ $method->id }}">{{ ucfirst($method->name) }}</option>
                                @endforeach
                            </select>
                            @error('payment_method_id') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label>Transaction No</label>
                            <input type="text" class="form-control" wire:model="transaction_no">
                        </div>

                        <div class="mb-3">
                            <label>Note</label>
                            <textarea class="form-control" wire:model="note"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit Payment</button>
                    </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>