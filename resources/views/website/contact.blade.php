@extends('website.layouts.app')
@section('content')
@include('website.partials.simple', ['body' => '<p>Email <a href="mailto:'.e(config('tradeflow.support_email')).'">'.e(config('tradeflow.support_email')).'</a> for product questions or implementation support.</p>'])
@endsection
