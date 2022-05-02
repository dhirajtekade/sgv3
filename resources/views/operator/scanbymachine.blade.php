@extends('layouts.app')

@section('content')

<div class="container">
    <h6>Scan here to checkout</h6>
    <input type="text" id="scannedcode" class="scannedcode" placeholder="Scan result" value="" autofocus="">
    <div class="checkout_result"></div>
</div>


<script src="{{ asset('js/scanner.js') }}" defer></script>
<script>
    var partialcheckout_Url = "{{ route('partialcheckout') }}";
    var checkoutallinone_Url = "{{ route('checkoutallinone') }}";
</script>
@endsection
