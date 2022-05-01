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
                    <input type="text" class="form-control" name="department" aria-describedby="emailHelp" placeholder="Department" value="Samanghar">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Event Name :</label>
                    <input type="text" class="form-control" name="event_name" aria-describedby="emailHelp" placeholder="Event Name">
                </div>

                <div class="form-group">
                    <label for="exampleFormControlSelect1">Event Start Date :</label>
                    <input type="date" class="form-control" id="event_start_date" name="event_start_date" value="{{$todaydate}}">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Event End Date :</label>
                    <input type="date" class="form-control" id="event_end_date" name="event_end_date" value="{{$todaydate}}">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Month :</label>
                        <select class="form-control" name="month" name="month">
                            <option value="January" {{($todaymonth == 'January') ? 'selected': ''}}>January</option>
                            <option value="February" {{($todaymonth == 'February') ? 'selected': ''}}>February</option>
                            <option value="March" {{($todaymonth == 'March') ? 'selected': ''}}>March</option>
                            <option value="April" {{($todaymonth == 'April') ? 'selected': ''}}>April</option>
                            <option value="May" {{($todaymonth == 'May') ? 'selected': ''}}>May</option>
                            <option value="June" {{($todaymonth == 'June') ? 'selected': ''}}>June</option>
                            <option value="July" {{($todaymonth == 'July') ? 'selected': ''}}>July</option>
                            <option value="August" {{($todaymonth == 'August') ? 'selected': ''}}>August</option>
                            <option value="September" {{($todaymonth == 'September') ? 'selected': ''}}>September</option>
                            <option value="October" {{($todaymonth == 'October') ? 'selected': ''}}>October</option>
                            <option value="November" {{($todaymonth == 'November') ? 'selected': ''}}>November</option>
                            <option value="December" {{($todaymonth == 'December') ? 'selected': ''}}>December</option>
                        </select>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Year :</label>
                    <input type="text" class="form-control" name="year" aria-describedby="emailHelp" placeholder="Year" value="{{$todayyear}}">
                </div>
                <div class="form-group">
                    <label for="exampleFormControlSelect1">Event Location :</label>
                    <input type="text" class="form-control" name="event_location" aria-describedby="emailHelp" placeholder="Event Location" value="Adalaj">
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        {{-- </div> --}}
        </div>
    </div>
</div>
@endsection

