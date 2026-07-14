<strong>{{ localize('Pincode') }}: </strong> {{ $address->pincode ?? 'N/A' }} <br>
<strong>{{ localize('District') }}: </strong> {{ $address->district_name ?? 'N/A' }} <br>
<strong>{{ localize('State') }}: </strong> {{ $address->state_name ?? 'N/A' }} <br>
<strong>{{ localize('Country') }}: </strong> {{ $address->country_name ?? 'India' }} <br>
<strong>{{ localize('Village') }}: </strong> {{ $address->village ?? 'N/A' }} <br>