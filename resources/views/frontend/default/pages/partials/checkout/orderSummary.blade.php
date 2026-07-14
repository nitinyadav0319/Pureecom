<div class="sidebar-widget py-6 px-4 bg-white rounded-2">
    <div class="widget-title d-flex">
        <h5 class="mb-0 flex-shrink-0">{{ localize('Order Summery') }}</h5>
        <span class="hr-line w-100 position-relative d-block align-self-end ms-1"></span>
    </div>
    <table class="sidebar-table w-100 mt-5">
        <tr>
            <td>(+) {{ localize('Items') }}({{ count($carts) }}):</td>
            <td class="text-end">{{ formatPrice(getSubTotal($carts, false, '', false)) }}</td>
        </tr>

        <tr>
            <td>(+) {{ localize('Tax') }}:</td>
            <td class="text-end">{{ formatPrice(getTotalTax($carts)) }}</td>
        </tr>

        @if (isset($shippingAmount))
            <tr>
                <td>(+) {{ localize('Shipping Charge') }}:</td>
                <td class="text-end">{{ formatPrice($shippingAmount) }}</td>
            </tr>
        @endif

        @php
            $is_free_shipping = false;
            if (getCoupon() != '' && getCouponDiscount(getSubTotal($carts, false), getCoupon()) > 0) {
                $coupon = \App\Models\Coupon::where('code', getCoupon())->first();
                if (!is_null($coupon) && $coupon->is_free_shipping == 1) {
                    $is_free_shipping = true;
                }
            }
        @endphp

        @php
            $shipping = 0;
            if (isset($shippingAmount) && $is_free_shipping == false) {
                $shipping = $shippingAmount;
            }
            $total = getSubTotal($carts, false, '', false) + getTotalTax($carts) + $shipping - getCouponDiscount(getSubTotal($carts, false), getCoupon());
        @endphp


        @if (getCoupon() != '')

            @if (getCouponDiscount(getSubTotal($carts, false), getCoupon()) > 0)
                <tr>
                    <td>(-) {{ localize('Coupon Discount') }}:</td>
                    <td class="text-end">{{ formatPrice(getCouponDiscount(getSubTotal($carts, false), getCoupon())) }}
                    </td>
                </tr>
            @endif

            @if ($is_free_shipping && isset($shippingAmount))
                <tr>
                    <td>(-) {{ localize('Shipping Discount') }}:</td>
                    <td class="text-end">{{ formatPrice($shippingAmount) }}
                    </td>
                </tr>
            @endif
        @endif
    </table>

    <span class="sidebar-spacer d-block my-4 opacity-50"></span>

    <div class="d-flex align-items-center justify-content-between">
        <h6 class="mb-0 fs-md">{{ localize('Total') }}</h6>
        <h6 class="mb-0 fs-md">{{ formatPrice($total) }}</h6>
    </div>

    <span class="sidebar-spacer d-block my-4 opacity-50"></span>

    <div class="label-input-field mt-6">
        <label>{{ 'Add Tips For Deliveryman?' }}</label>
        <input type="number" name="tips" value="0" min="0" step="0.001">
        <input type="hidden" name="merchant_id" value="2657173"/>
        <input type="hidden" name="amount" value="{{ $total }}"/>
        <input type="hidden" name="order_id" value="1"/>
        <input type="hidden" name="currency" value="INR"/>
        <input type="hidden" name="redirect_url" value="http://www.localhost.com/ccavResponseHandler.php"/>
        <input type="hidden" name="cancel_url" value="http://www.localhost.com/ccavResponseHandler.php"/>
    </div>

    <button type="submit" class="btn btn-primary btn-md rounded mt-6 w-100" id="btn_cod">{{ localize('Place Order') }}</button>
    <button type="submit" class="btn btn-primary btn-md rounded mt-6 w-100" id="btn_ccavenue" style="display:none;">{{ localize('Place Order') }}</button>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script>
    jQuery(document).ready(function(){
        $('.cod').click(function(e){
            var btn_value = $(this).val();
        });
    });

</script>

