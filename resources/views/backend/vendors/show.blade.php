@extends('backend.layouts.master')

@section('title')
    Vendor Details
@endsection

@section('contents')
    <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Vendor Details - {{ $vendor->name }}</h4>
        </div>
        <div class="card-body">
            {{-- Tabs --}}
            <ul class="nav nav-tabs" id="vendorTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="basic-tab" data-bs-toggle="tab" href="#basic" role="tab">Basic Info</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#profile" role="tab">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="orders-tab" data-bs-toggle="tab" href="#orders" role="tab">Orders</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="products-tab" data-bs-toggle="tab" href="#products" role="tab">Products</a>
                </li>
            </ul>

            <div class="tab-content mt-3">

                {{-- Basic Info --}}
                <div class="tab-pane fade show active" id="basic" role="tabpanel">
                    <p><strong>ID:</strong> {{ $vendor->id }}</p>
                    <p><strong>Name:</strong> {{ $vendor->name }}</p>
                    <p><strong>Email:</strong> {{ $vendor->email }}</p>
                    <p><strong>Phone:</strong> {{ $vendor->phone ?? '-' }}</p>
                    <p><strong>Status:</strong>
                        <span class="badge badge-{{ $vendor->status == 'approved' ? 'success' : 'warning' }}">
                            {{ ucfirst($vendor->status) }}
                        </span>
                    </p>
                </div>

                {{-- Profile Info --}}
                <div class="tab-pane fade" id="profile" role="tabpanel">
                    @if($vendor->vendorProfile)
                        <p><strong>Business Name:</strong> {{ $vendor->vendorProfile->business_name ?? '-' }}</p>
                        <p><strong>Business Type:</strong> {{ $vendor->vendorProfile->business_type ?? '-' }}</p>
                        <p><strong>Business Registration No:</strong> {{ $vendor->vendorProfile->business_reg_no ?? '-' }}</p>
                        <p><strong>Establishment Date:</strong> {{ $vendor->vendorProfile->establishment_date ?? '-' }}</p>
                        <p><strong>Business Address:</strong> {{ $vendor->vendorProfile->business_address ?? '-' }}</p>
                        <p><strong>City:</strong> {{ $vendor->vendorProfile->city ?? '-' }}</p>
                        <p><strong>State:</strong> {{ $vendor->vendorProfile->state ?? '-' }}</p>
                        <p><strong>ZIP:</strong> {{ $vendor->vendorProfile->zip ?? '-' }}</p>
                        <p><strong>Contact Person:</strong> {{ $vendor->vendorProfile->contact_person ?? '-' }}</p>
                        <p><strong>Designation:</strong> {{ $vendor->vendorProfile->designation ?? '-' }}</p>
                        <p><strong>Phone (Alternate):</strong> {{ $vendor->vendorProfile->alt_phone ?? '-' }}</p>
                        <p><strong>Bank Name:</strong> {{ $vendor->vendorProfile->bank_name ?? '-' }}</p>
                        <p><strong>Branch:</strong> {{ $vendor->vendorProfile->branch_name ?? '-' }}</p>
                        <p><strong>Account Holder Name:</strong> {{ $vendor->vendorProfile->account_holder_name ?? '-' }}</p>
                        <p><strong>Account Number:</strong> {{ $vendor->vendorProfile->account_number ?? '-' }}</p>
                        <p><strong>IFSC Code:</strong> {{ $vendor->vendorProfile->ifsc_code ?? '-' }}</p>
                        <p><strong>Cheque Copy:</strong>
                            @if($vendor->vendorProfile->cheque_copy)
                                <a href="{{ asset('storage/' . $vendor->vendorProfile->cheque_copy) }}" target="_blank">View</a>
                            @else
                                -
                            @endif
                        </p>
                        <p><strong>Product Categories:</strong> {{ $vendor->vendorProfile->product_categories ?? '-' }}</p>
                        <p><strong>Average Order Value:</strong> {{ $vendor->vendorProfile->avg_order_value ?? '-' }}</p>
                        <p><strong>Expected Listing Count:</strong> {{ $vendor->vendorProfile->expected_listing_count ?? '-' }}
                        </p>
                        <p><strong>Business Model:</strong> {{ $vendor->vendorProfile->business_model ?? '-' }}</p>
                        <p><strong>Product Certification:</strong> {{ $vendor->vendorProfile->product_certification ?? '-' }}
                        </p>
                        <p><strong>PAN Number:</strong> {{ $vendor->vendorProfile->pan_number ?? '-' }}</p>
                        <p><strong>GST Number:</strong> {{ $vendor->vendorProfile->gst_number ?? '-' }}</p>
                        <p><strong>IEC Code:</strong> {{ $vendor->vendorProfile->iec_code ?? '-' }}</p>
                        <p><strong>KYC Docs:</strong>
                            @if($vendor->vendorProfile->kyc_docs)
                                <a href="{{ asset('storage/' . $vendor->vendorProfile->kyc_docs) }}" target="_blank">View</a>
                            @else
                                -
                            @endif
                        </p>
                        <p><strong>Has Own Logistics:</strong> {{ $vendor->vendorProfile->has_own_logistics ? 'Yes' : 'No' }}
                        </p>
                        <p><strong>Preferred Shipping:</strong> {{ $vendor->vendorProfile->preferred_shipping ?? '-' }}</p>
                        <p><strong>Warehouse Address:</strong> {{ $vendor->vendorProfile->warehouse_address ?? '-' }}</p>
                    @else
                        <p class="text-muted">No profile found for this vendor.</p>
                    @endif
                </div>


                {{-- Orders --}}
                <div class="tab-pane fade" id="orders" role="tabpanel">
                    @if($orders->count())
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Total Price</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->created_at->format('d M Y') }}</td>
                                        <td>{{ $order->total_price }}</td>
                                        <td>{{ ucfirst($order->delivery_status) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No orders found for this vendor.</p>
                    @endif
                </div>

                {{-- Products --}}
                {{-- Products Tab --}}
                <div class="tab-pane fade" id="products" role="tabpanel">
                    @if($products->count())
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Status</th>
                                    <!-- <th>Action</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        {{-- Product Thumbnail + Name --}}
                                        <td>
                                            <a href="{{ route('products.show', $product->slug) }}" class="d-flex align-items-center"
                                                target="_blank">

                                                <div class="avatar avatar-sm">
                                                    <img class="rounded-circle" src="{{ uploadedAsset($product->thumbnail_image) }}"
                                                        alt="{{ $product->collectLocalization('name') }}"
                                                        onerror="this.onerror=null;this.src='{{ staticAsset('backend/assets/img/placeholder-thumb.png') }}';" />
                                                </div>

                                                <h6 class="fs-sm mb-0 ms-2">
                                                    {{ $product->collectLocalization('name') }}
                                                </h6>
                                            </a>
                                        </td>

                                        {{-- Price --}}
                                        <td>{{ $product->min_price }} - {{ $product->max_price }}</td>

                                        {{-- Stock --}}
                                        <td>{{ $product->stock_qty }}</td>


                                        {{-- Action --}}
                                        <td>
                                            @if($product->status == 'pending')
                                                {{-- Pending → Approve & Reject --}}
                                                <form action="{{ route('vendor.product.approve', $product->id) }}" method="POST"
                                                    style="display:inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                                </form>
                                                <form action="{{ route('vendor.product.reject', $product->id) }}" method="POST"
                                                    style="display:inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                                </form>
                                            @elseif($product->status == 'approved')
                                                {{-- Approved → Show Approved text + option to Reject --}}
                                                <span class="badge bg-success">Approved</span>
                                                <form action="{{ route('vendor.product.reject', $product->id) }}" method="POST"
                                                    style="display:inline-block; margin-left:5px;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">Reject</button>
                                                </form>
                                            @elseif($product->status == 'rejected')
                                                {{-- Rejected → Show Rejected text + option to Approve --}}
                                                <span class="badge bg-danger">Rejected</span>
                                                <form action="{{ route('vendor.product.approve', $product->id) }}" method="POST"
                                                    style="display:inline-block; margin-left:5px;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-success">Approve</button>
                                                </form>
                                            @else
                                                <span class="text-muted">No Action</span>
                                            @endif
                                        </td>

                                    </tr>

                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>No products found for this vendor.</p>
                    @endif
                </div>

            </div>
        </div>
@endsection
   