@extends('layouts.app')

@section('content')
<div class="container w-50">
    <div class="row">
        <div class="col-12">
            <h2>Settings Form for Token Print</h2>
        </div>
        <div class="col-12">
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
        </div>
        <div class="col-12">
        {{-- <div class="card"> --}}
            <form action="{{route('store_setting_form')}}" method="post">
                @csrf
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Code Print :</label>
                    <select class="form-control" id="qr_code_print" name="qr_code_print">
                        <option value="double" {{(isset($settings->qr_code_print) && $settings->qr_code_print == 'double') ? 'selected':'' }} >Bag and Mahatma Print</option>
                        <option value="single"  {{(isset($settings->qr_code_print) && $settings->qr_code_print == 'single') ? 'selected':'' }}>Only Bag Print</option>
                        <option value="no print"  {{(isset($settings->qr_code_print) && $settings->qr_code_print == 'no print') ? 'selected':'' }}>No Print</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Show Token Number : </label>
                    <select class="form-control" id="show_token_number" name="show_token_number">
                    <option value="single" {{(isset($settings->show_token_number) && $settings->show_token_number == 'single') ? 'selected':'' }}>Only on Bag Token</option>
                    <option value="both" {{(isset($settings->show_token_number) && $settings->show_token_number == 'both') ? 'selected':'' }}>All Tokens</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Show Bag Number : </label>
                    <select class="form-control" id="show_bag_number" name="show_bag_number">
                    <option value="yes" {{(isset($settings->show_bag_number) && $settings->show_bag_number == 'yes') ? 'selected':'' }}>Yes</option>
                    <option value="no" {{(isset($settings->show_bag_number) && $settings->show_bag_number == 'no') ? 'selected':'' }}>No</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        {{-- </div> --}}
        </div>
    </div>
</div>
@endsection

