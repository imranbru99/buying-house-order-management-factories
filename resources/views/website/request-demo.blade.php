@extends('website.layouts.app')
@section('content')
@include('website.partials.simple', ['body' => '<p>Tell us about your buying house and order volume. We will schedule a guided session with the Global Apparel demo tenant.</p><p>Or jump straight to the <a href="'.url('/demo-access').'">self-serve demo credentials</a> when enabled.</p>'])
@endsection
