@php
$booking = $booking??'';
@endphp
@section('head')
<meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ticketsApp</title>
        <link rel="stylesheet" href="{{ env('APP_URL') }}/css/bootstrap.min.css" />
        <link rel="stylesheet" href="{{ env('APP_URL') }}/icons/bootstrap-icons.css">
        <link rel="stylesheet" href="{{ env('APP_URL') }}/css/style.css">
        @if($booking)
        <script src="{{ env('APP_URL') }}/js/scheme-designer.min.js"></script>
        @endif
@endsection
