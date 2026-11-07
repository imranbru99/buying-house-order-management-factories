@extends('website.layouts.app')
@section('content')
<div class="tf-wrap">
    <h1>{{ $title }}</h1>
    <h2 style="font-size:1.2rem;">Can I run TradeFlow locally without MySQL?</h2>
    <p>Yes. SQLite is the default connection for local demos; switch to MySQL for production workloads.</p>
    <h2 style="font-size:1.2rem;">How do I reset demo data?</h2>
    <p>Run <code>php artisan tradeflow:demo-reset</code> when <code>APP_ENV</code> is <code>local</code> or <code>demo</code>.</p>
</div>
@endsection
