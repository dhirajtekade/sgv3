@extends('layouts.app')

@section('content')
<div class="container w-50">

    <div class="row mt-2 ">
        <h2>Current Event Information</h2>
        <table class="table table-dark" >
            <tr>
                <th>Department</th>
                <th>Event Name</th>
                <th>Month</th>
                <th>Year</th>
                <th>Event Location</th>
            </tr>
            @isset($eventinfo)
            <tr>
                <td>{{$eventinfo->department}}</td>
                <td>{{$eventinfo->event_name}}</td>
                <td>{{$eventinfo->month}}</td>
                <td>{{$eventinfo->year}}</td>
                <td>{{$eventinfo->event_location}}</td>
            </tr>
            @endisset

        </table>
    </div>


    <div class="row">
        <div class="col-12">
            <h2>Add New Event</h2>
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
            <form action="{{route('store_eveninfo')}}" method="post">
                @csrf
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Department :</label>
                    <input type="text" class="form-control" name="department" aria-describedby="emailHelp" placeholder="Department">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Event Name :</label>
                    <input type="text" class="form-control" name="event_name" aria-describedby="emailHelp" placeholder="Event Name">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Month :</label>
                        <select class="form-control" name="month" name="month">
                            <option value="Jan" >Jan</option>
                            <option value="Feb">Feb</option>
                            <option value="March">March</option>
                            <option value="April">April</option>
                            <option value="May">May</option>
                            <option value="June">June</option>
                            <option value="July">July</option>
                            <option value="August">August</option>
                            <option value="September">September</option>
                            <option value="October">October</option>
                            <option value="November">November</option>
                            <option value="December">December</option>
                        </select>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Year :</label>
                    <input type="text" class="form-control" name="year" aria-describedby="emailHelp" placeholder="Year">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Event Location :</label>
                    <input type="text" class="form-control" name="event_location" aria-describedby="emailHelp" placeholder="Event Location">
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        {{-- </div> --}}
        </div>
    </div>
</div>
@endsection

