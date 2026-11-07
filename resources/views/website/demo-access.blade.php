@extends('website.layouts.app')

@section('content')
<div class="tf-wrap">
    <h1>{{ $title }}</h1>
    <p>Explore the seeded tenant <strong>Global Apparel Sourcing Ltd</strong> (<code>GAS</code> / <code>global-apparel</code>). Sign in at <a href="{{ $loginUrl }}">{{ $loginUrl }}</a>.</p>

    @if($showPassword)
        <p class="tf-card" style="margin:1.5rem 0;background:#f0fdfa;border-color:#99f6e4;">
            Shared demo password: <strong>{{ $password }}</strong>
        </p>
    @endif

    <table style="width:100%;border-collapse:collapse;margin-top:1rem;font-size:.95rem;">
        <thead>
            <tr style="border-bottom:2px solid #e2e8f0;text-align:left;">
                <th style="padding:.75rem 0;">Email</th>
                <th style="padding:.75rem 0;">Role / portal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($credentials as $row)
                <tr style="border-bottom:1px solid #e2e8f0;">
                    <td style="padding:.65rem 0;"><code>{{ $row['email'] }}</code></td>
                    <td style="padding:.65rem 0;">{{ $row['role'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
