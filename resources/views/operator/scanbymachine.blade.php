@extends('layouts.app')

@section('content')

<div class="container">

    <input type="text" id="scannedcode" class="scannedcode" placeholder="Result of scanning" value="" autofocus="">
    <div class="checkout_result"></div>
</div>


<script src="{{ asset('js/scanner.js') }}" defer></script>
<script>
    var partialcheckout_Url = "{{ route('partialcheckout') }}";

</script>
@endsection
