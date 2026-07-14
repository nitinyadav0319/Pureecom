@extends('backend.layouts.master')

@section('title')
    {{ localize('Manage Vendor Pincodes') }} {{ getSetting('title_separator') }} {{ getSetting('system_title') }}
@endsection

@section('contents')
    <div class="container py-5">
        <h2 class="mb-4">Manage Pincodes</h2>

        {{-- ✅ Success message --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- ✅ CSV Upload Form --}}
        <div class="card mb-4">
            <div class="card-header">Upload India Pincode CSV</div>
            <div class="card-body">
                <form action="{{ route('admin.pincodes.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" class="form-control mb-2" required>
                    <button type="submit" class="btn btn-primary">Import CSV</button>
                </form>
            </div>
        </div>

        {{-- ✅ Manual Add Form --}}
        <div class="card mb-4">
            <div class="card-header">Add Pincode Manually</div>
            <div class="card-body">
                <form action="{{ route('admin.pincodes.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label>Pincode</label>
                            <input type="number" name="pincode" class="form-control" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>District</label>
                            <input type="text" name="district" class="form-control" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>State</label>
                            <input type="text" name="state" class="form-control" required>
                        </div>
                        <div class="col-md-3 d-flex align-items-end mb-3">
                            <button type="submit" class="btn btn-success w-100">Add Pincode</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- ✅ Pincode List --}}
        <div class="card">
            <div class="card-header">All Pincodes</div>
            <div class="card-body">
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Pincode</th>
                            <th>District</th>
                            <th>State</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pincodes as $index => $pin)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $pin->pincode }}</td>
                                <td>{{ $pin->district }}</td>
                                <td>{{ $pin->state }}</td>
                                <td>
                                    <form action="{{ route('admin.pincodes.toggle', $pin->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-sm {{ $pin->is_active ? 'btn-success' : 'btn-danger' }}">
                                            {{ $pin->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('admin.pincodes.destroy', $pin->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this pincode?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No pincodes found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $pincodes->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
