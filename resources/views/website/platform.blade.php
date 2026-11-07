@extends('website.layouts.app')
@section('content')
@include('website.partials.simple', ['body' => '<p>Multi-tenant architecture with Filament-powered workspaces, REST API, and per-tenant module settings.</p><p>Resolve tenants by subdomain, custom domain, or <code>X-Tenant</code> header for integrations.</p>'])
@endsection
