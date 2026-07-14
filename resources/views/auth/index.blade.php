@extends('layouts.auth')  {{-- ya jo bhi tumhara admin layout ho --}}

@section('content')
    <div class="container py-5">
        <h1>Pending Vendor Approvals</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($vendors->count() > 0)
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Vendor Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vendors as $vendor)
                        <tr>
                            <td>{{ $vendor->id }}</td>
                            <td>{{ $vendor->business_name }}</td>
                            <td>{{ $vendor->user->email }}</td>
                            <td>{{ ucfirst($vendor->user->status) }}</td>
                            <td>
                                <form action="{{ route('admin.vendors.approve', $vendor->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                </form>

                                <form action="{{ route('admin.vendors.reject', $vendor->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>No pending vendors found.</p>
        @endif
    </div>
@endsection
