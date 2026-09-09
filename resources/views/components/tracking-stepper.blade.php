@props(['history' => [], 'currentStatus' => 'waiting_arrival'])

@php
    $steps = [
        'waiting_arrival' => ['label' => 'Menunggu', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
        'arrived_wh_china' => ['label' => 'Gudang China', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
        'in_transit' => ['label' => 'OTW Indo', 'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        'arrived_indonesia' => ['label' => 'Tiba Indo', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        'awaiting_payment' => ['label' => 'Tagihan', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        'ready_to_ship' => ['label' => 'Siap Kirim', 'icon' => 'M5 13l4 4L19 7'],
        'completed' => ['label' => 'Diterima', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
    ];

    $stepKeys = array_keys($steps);
    $currentIndex = array_search($currentStatus, $stepKeys);
    if ($currentIndex === false) {
        $currentIndex = 0;
    }
    $totalSteps = count($stepKeys);
@endphp

<div style="width:100%; max-width:100%; box-sizing:border-box;">
    {{-- Horizontal Stepper Track (Fits 100% inside screen width) --}}
    <div style="position:relative; width:100%; padding:0.5rem 0;">
        {{-- Connecting Line --}}
        <div style="position:absolute; top:20px; left:20px; right:20px; height:2px; background:#e2e8f0; z-index:0;">
            @if($currentIndex > 0)
            <div style="height:100%; background:linear-gradient(90deg, #10b981, #4f46e5); width:{{ min(100, ($currentIndex / ($totalSteps - 1)) * 100) }}%; transition:width 0.4s ease;"></div>
            @endif
        </div>

        <div style="display:flex; justify-content:space-between; position:relative; z-index:1; width:100%;">
            @foreach($steps as $key => $step)
                @php
                    $stepIndex = array_search($key, $stepKeys);
                    $isCompleted = $stepIndex < $currentIndex;
                    $isCurrent = $stepIndex === $currentIndex;
                    $isFuture = $stepIndex > $currentIndex;
                    
                    if ($isCompleted) {
                        $circleColor = '#10b981';
                        $circleBg = '#d1fae5';
                        $ringColor = '#10b981';
                    } elseif ($isCurrent) {
                        $circleColor = '#4f46e5';
                        $circleBg = '#e0e7ff';
                        $ringColor = '#4f46e5';
                    } else {
                        $circleColor = '#94a3b8';
                        $circleBg = '#f1f5f9';
                        $ringColor = 'transparent';
                    }
                @endphp
                <div style="display:flex; flex-direction:column; align-items:center; flex:1; text-align:center; min-width:0;">
                    <div style="width:26px; height:26px; background:{{ $circleBg }}; color:{{ $circleColor }}; border-radius:50%; display:flex; align-items:center; justify-content:center; margin-bottom:0.25rem; border:2px solid #fff; box-shadow:0 0 0 1px {{ $ringColor }}; flex-shrink:0;">
                        @if($isCompleted)
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        @else
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $step['icon'] }}"/></svg>
                        @endif
                    </div>
                    <div style="font-size:0.6rem; font-weight:{{ $isCurrent ? '800' : ($isCompleted ? '700' : '600') }}; color:{{ $isFuture ? 'var(--ink-soft)' : 'var(--ink)' }}; line-height:1.1; word-break:break-word; max-width:44px;">
                        {{ $step['label'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@if($history && count($history) > 0)
<div style="margin-top:1.5rem; width:100%;">
    <h4 style="font-size:0.875rem; font-weight:800; color:var(--ink); margin-bottom:0.75rem;">Riwayat Status</h4>
    <div style="position:relative; padding-left:1.5rem;">
        {{-- Timeline line --}}
        <div style="position:absolute; left:4px; top:6px; bottom:6px; width:2px; background:linear-gradient(to bottom, #4f46e5, #e2e8f0);"></div>
        
        @foreach($history->sortByDesc('created_at') as $idx => $log)
            <div style="display:flex; gap:0.6rem; margin-bottom:0.75rem; position:relative;">
                <div style="position:absolute; left:-1.5rem; top:4px; width:10px; height:10px; border-radius:50%; background:{{ $idx === 0 ? '#4f46e5' : '#94a3b8' }}; border:2px solid #fff; box-shadow:0 0 0 1px {{ $idx === 0 ? '#4f46e5' : '#e2e8f0' }};"></div>
                <div style="background:{{ $idx === 0 ? 'rgba(79,70,229,0.05)' : '#f8fafc' }}; padding:0.6rem 0.85rem; border-radius:10px; flex:1;">
                    <div style="font-weight:700; font-size:0.82rem; color:var(--ink);">{{ $steps[$log->to_status]['label'] ?? $log->to_status }}</div>
                    @if($log->from_status)
                        <div style="font-size:0.72rem; color:var(--ink-soft); margin-top:0.1rem;">dari {{ $steps[$log->from_status]['label'] ?? $log->from_status }}</div>
                    @endif
                    <div style="font-size:0.7rem; color:var(--ink-soft); margin-top:0.2rem; display:flex; align-items:center; gap:0.3rem;">
                        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $log->created_at->format('d M Y, H:i') }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif