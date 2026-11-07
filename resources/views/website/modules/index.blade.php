@extends('website.layouts.app')
@section('content')
<div class="tf-wrap">
    <h1>{{ $title }}</h1>
    <p>Activate capabilities per buying house via the module registry.</p>
    <ul style="list-style:none;margin-top:1.5rem;">
        @foreach($modules as $module)
            <li style="padding:1rem 0;border-bottom:1px solid #e2e8f0;">
                <a href="{{ url('/modules/'.$module['key']) }}" style="font-weight:600;">{{ $module['name'] }}</a>
                <span style="color:var(--tf-slate-500);font-size:.9rem;display:block;">{{ $module['description'] }}</span>
            </li>
        @endforeach
    </ul>
</div>
@endsection
