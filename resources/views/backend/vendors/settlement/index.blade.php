@extends('backend.layouts.master')

@section('title', 'Vendor Settlement')

@section('contents')
<div class="container">
    <h2>Vendor Settlement</h2>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Product / Variation</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Admin Commission</th>
                <th>Vendor Earning</th>
                <th>Commission %</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr data-bs-toggle="modal" data-bs-target="#orderDetailModal{{ $order->id }}" style="cursor: pointer;">
                <td>
                    {{ optional(optional($order->productVariation)->product)->name ?? 'Product Missing' }}
                    @if(optional($order->productVariation)->variation_key)
                        ({{ $order->productVariation->variation_key }})
                    @endif
                </td>
                <td>{{ $order->qty }}</td>
                <td>{{ number_format($order->total_price, 2) }}</td>
                <td>{{ number_format($order->admin_commission, 2) }}</td>
                <td>{{ number_format($order->vendor_earning, 2) }}</td>
                <td>
                    @if($order->total_price > 0)
                        {{ number_format(($order->admin_commission / $order->total_price) * 100, 2) }}%
                    @else
                        0%
                    @endif
                </td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
            </tr>

            <!-- Modal for this order -->
            <div class="modal fade" id="orderDetailModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                {{ optional(optional($order->productVariation)->product)->name ?? 'Product Missing' }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Variation:</strong> {{ optional($order->productVariation)->variation_key ?? 'No Variation' }}</p>
                            <p><strong>Quantity:</strong> {{ $order->qty }}</p>
                            <p><strong>Total Price:</strong> {{ number_format($order->total_price, 2) }}</p>
                            <p><strong>Admin Commission:</strong> {{ number_format($order->admin_commission, 2) }}</p>
                            <p><strong>Vendor Earning:</strong> {{ number_format($order->vendor_earning, 2) }}</p>
                            <p><strong>Commission %:</strong> 
                                @if($order->total_price>0)
                                    {{ number_format(($order->admin_commission/$order->total_price)*100,2) }}%
                                @else
                                    0%
                                @endif
                            </p>
                            <p><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <tr>
                <td colspan="7" class="text-center">No orders found.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Total</th>
                <th>{{ number_format($totalAdminCommission, 2) }}</th>
                <th>{{ number_format($totalVendorEarning, 2) }}</th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>

<!-- Initialize Bootstrap tooltips if needed -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endsection
