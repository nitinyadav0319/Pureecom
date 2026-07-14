@extends('backend.layouts.master')

@section('title')
    Vendors List
@endsection

@section('contents')
<div class="card">
    <div class="card-header">
        <h4 class="mb-0">Vendors List</h4>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vendors as $vendor)
                    <tr>
                        <td>{{ $vendor->id }}</td>
                        <td>{{ $vendor->name }}</td>
                        <td>{{ $vendor->email }}</td>
                        <td>
                            <span class="badge badge-{{ $vendor->status == 'approved' ? 'success' : 'warning' }}">
                                {{ ucfirst($vendor->status) }}
                            </span>
                        </td>
                        <td>
<a href="{{ route('admin.vendors.show', $vendor->id) }}" class="btn btn-sm btn-primary">View</a>

                                
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No vendors found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        {{ $vendors->links() }}
    </div>
</div>
@endsection
