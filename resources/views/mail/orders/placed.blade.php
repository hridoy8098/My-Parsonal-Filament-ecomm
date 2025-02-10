<x-mail::message>
# Order placed Successfully!

Thank you for your order. your order number is:{{ $order->id }}.

<x-mail::button :url="$url">
view Order
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
