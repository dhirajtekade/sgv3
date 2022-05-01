@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
            @if (\Session::has('success'))
                <div class="alert alert-success">
                    {!! \Session::get('success') !!}
                </div>
            @endif
            @if (\Session::has('error'))
                <div class="alert alert-danger">
                    {!! \Session::get('error') !!}
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-5">
            <fieldset class="border p-2">
                <legend  class="w-auto">Export</legend>
                <div class="row">
                    <div class="col-6">
                        <form method="post" enctype="multipart/form-data" action="{{route('export_excel')}}" >
                            @csrf
                            {{-- <div class="form-group">
                                <label for="title">Choose CSV</label>
                                <input type="file" name="file" class="form-control" />
                            </div> --}}
                            <button type="submit" class="btn btn-primary">Export Mht data</button>
                        </form>
                    </div>
                </div>
                <hr>
                <div class="row mt-2">
                    <div class="col-6">
                        <form method="post" enctype="multipart/form-data" action="{{route('export_report')}}" >
                            @csrf
                            <div class="form-group">
                                <label for="exampleFormControlSelect1">Select Event</label>
                                    @if(isset($eventdata))
                                    <select class="form-control" name="event_id" name="event_id">
                                        @foreach ($eventdata as $event)
                                        <option value="{{$event->id}}" >{{$event->event_name}}</option>

                                        @endforeach
                                    </select>
                                    @endif
                            </div>
                            <div class="d-inline-flex mt-2">
                                <input type="date" id="export_startdate" name="export_startdate" value="{{$event_start_date}}">&nbsp;
                                <input type="date" id="export_enddate" name="export_enddate" value="{{$event_end_date}}">
                            </div>
                            <button type="submit" class="btn btn-primary">Export Event Report</button>
                        </form>
                    </div>
                </div>
                {{-- <button type="submit" class="btn btn-primary">Export to CSV</button> --}}
             </fieldset>
        </div>
        <div class="col-7">
                <fieldset class="border p-2">
                    <legend  class="w-auto">Import</legend>
                    <p>Format to follow:</p>
                        <table class="table table-bordered import_format">
                            <tr>
                                <td>mht_id</td>
                                <td>fname</td>
                                <td>mname</td>
                                <td>lname</td>
                                <td>center_name</td>
                                <td>whatsapp_no</td>
                                <td>alternate_no</td>
                                <td>city</td>
                            </tr>
                        </table>

                    <form method="post" enctype="multipart/form-data" action="{{route('mhtdata_import')}}" >
                        @csrf
                        <div class="form-group">
                            <label for="title">Choose Excel</label>
                            <input type="file" name="file" class="form-control" />
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>

                 </fieldset>
        </div>
    </div>
</div>

@endsection
