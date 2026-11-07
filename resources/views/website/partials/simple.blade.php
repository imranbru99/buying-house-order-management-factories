<div class="tf-wrap">
    <h1>{{ $title }}</h1>
    @if(!empty($lead))
        <p style="font-size:1.1rem;color:var(--tf-slate-900);">{{ $lead }}</p>
    @endif
    @isset($body)
        {!! $body !!}
    @endisset
    @yield('page_body')
</div>
