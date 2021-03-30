@extends('shopify-app::layouts.default')
@section('styles')
    <link rel="stylesheet" href="{{ asset('/css/app.css') }}">
@endsection
@section('content')
    <div id="root"></div>
@endsection

@section('scripts')
    @parent
    <script src="{{ asset('/js/app.js') }}" defer></script>

    <script type="text/javascript">
        var actions = AppBridge.actions;
    </script>
@endsection