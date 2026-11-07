@extends('website.layouts.app')
@section('content')
@include('website.partials.simple', ['body' => '<p>Starter, Growth, and Enterprise tiers include core organization and buyer order modules. Optional modules such as trade finance, compliance, and API access are priced per tenant.</p><p><a href="'.url('/contact').'">Contact sales</a> for a tailored quote.</p>'])
@endsection
