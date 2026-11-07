@extends('website.layouts.app')
@section('content')
<div class="tf-wrap">
    <h1>{{ $title }}</h1>
    <p>Composable modules you can switch on per tenant.</p>
    <div class="tf-grid-3" style="margin-top:1.5rem;">
        @foreach(collect($modules)->take(12) as $module)
            <a href="{{ url('/modules/'.$module['key']) }}" class="tf-card" style="display:block;text-decoration:none;color:inherit;">
                <h3 style="font-size:1.05rem;margin-bottom:.35rem;">{{ $module['name'] }}</h3>
                <p style="font-size:.9rem;margin:0;color:var(--tf-slate-500);">{{ $module['description'] }}</p>
            </a>
        @endforeach
    </div>
</div>
@endsection
