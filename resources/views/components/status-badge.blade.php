@props(['status'])

@php
    $class = match($status) {
        'waiting_for_arrival' => 'badge-waiting',
        'arrived_at_china_wh' => 'badge-china',
        'in_transit'          => 'badge-transit',
        'arrived_at_indo_wh'  => 'badge-indonesia',
        'payment_pending'     => 'badge-payment',
        'ready_for_pickup'    => 'badge-ready',
        'completed'           => 'badge-paid',
        default               => 'badge-waiting',
    };
    $label = \App\Models\Resi::$statusLabels[$status] ?? 'Unknown';
@endphp

<span class="badge {{ $class }}">
    @if($status === 'waiting_for_arrival')
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    @elseif($status === 'arrived_at_china_wh' || $status === 'arrived_at_indo_wh')
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    @elseif($status === 'in_transit')
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 21.05v-5M12 6.55v-2M18.36 18.36l-3.53-3.53M8.17 11.17L4.64 7.64M21.05 12h-5M6.55 12h-2m13.81-6.36l-3.53 3.53M11.17 15.83l-3.53 3.53M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
    @elseif($status === 'payment_pending')
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    @else
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    @endif
    {{ $label }}
</span>
