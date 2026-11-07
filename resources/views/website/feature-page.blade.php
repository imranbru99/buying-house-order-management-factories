@extends('website.layouts.app')

@section('content')
<section class="feature-page">
    <div class="container narrow">
        <p class="eyebrow">Capability</p>
        <h1>{{ $title }}</h1>
        <p class="lead">{{ $lead }}</p>
        <div class="prose">
            <p>
                TradeFlow supports {{ strtolower($title) }} as part of the end-to-end buying-house workflow:
                inquiry, costing, sampling, purchase orders, multi-factory allocation, production, quality,
                shipment, and commercial close-out.
            </p>
            <p>
                Each organization enables modules independently. Sensitive actions remain permission-gated,
                tenant-scoped, and auditable. Explore the live module catalog for activation details.
            </p>
            <div class="cta-row">
                <a class="btn btn-primary" href="{{ url('/request-demo') }}">Request a demo</a>
                <a class="btn btn-secondary" href="{{ url('/modules') }}">Browse modules</a>
            </div>
        </div>
    </div>
</section>
@endsection
