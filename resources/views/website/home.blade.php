@extends('website.layouts.app')

@section('title', 'TradeFlow — Order management for buying houses')

@section('content')
<section class="tf-hero-pattern" style="padding: 4rem 0 5rem;">
    <div class="tf-wrap" style="display:grid;grid-template-columns:1fr;gap:2rem;">
        <div style="max-width:720px;">
            <p style="text-transform:uppercase;letter-spacing:.12em;font-size:.75rem;font-weight:600;color:var(--tf-teal-dark);margin-bottom:1rem;">Buying house operations</p>
            <h1>TradeFlow</h1>
            <p style="font-size:1.25rem;font-family:var(--font-serif);color:var(--tf-slate-900);margin-bottom:1.5rem;">
                Manage Every Buyer Order, Every Factory, Every Shipment.
            </p>
            <p>Coordinate purchase orders, factory allocations, production, quality, and logistics from one multi-tenant platform built for apparel sourcing teams.</p>
            <div style="display:flex;flex-wrap:wrap;gap:.75rem;margin-top:1.75rem;">
                <a href="{{ url('/request-demo') }}" class="tf-btn tf-btn-primary">Schedule a walkthrough</a>
                @if(config('tradeflow.demo.enabled'))
                    <a href="{{ url('/demo-access') }}" class="tf-btn tf-btn-ghost">Try the live demo</a>
                @endif
                <a href="{{ url('/app/login') }}" class="tf-btn tf-btn-ghost">Tenant login</a>
            </div>
        </div>
    </div>
</section>

<section class="tf-wrap" style="padding-bottom:3rem;">
    <h2>From inquiry to ex-factory</h2>
    <div class="tf-grid-3" style="margin-top:1.5rem;">
        <div class="tf-card">
            <h3 style="font-size:1.15rem;margin-bottom:.5rem;">Buyer orders</h3>
            <p style="font-size:.95rem;margin:0;">Capture PO lines, colors, sizes, and delivery schedules with approval workflows.</p>
        </div>
        <div class="tf-card">
            <h3 style="font-size:1.15rem;margin-bottom:.5rem;">Factory allocations</h3>
            <p style="font-size:.95rem;margin:0;">Split quantities across approved factories without over-allocating capacity.</p>
        </div>
        <div class="tf-card">
            <h3 style="font-size:1.15rem;margin-bottom:.5rem;">Shipments &amp; finance</h3>
            <p style="font-size:.95rem;margin:0;">Track containers, documents, and letters of credit alongside operational data.</p>
        </div>
    </div>
</section>
@endsection
