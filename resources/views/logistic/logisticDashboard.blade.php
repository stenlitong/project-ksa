@extends('../layouts.base')

@section('title', 'Logistic Dashboard')

@section('container')
<div class="row">
    @include('logistic.sidebar')

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h2>Welcome back, {{ Auth::user()->name }} !</h2>
            <h3>{{ "Today is, " . date('l M Y') }}</h3>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Crew ID</th>
                    <th scope="col">Item</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Department</th>
                    <th scope="col">Item Age</th>
                    <th scope="col">Progress</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $o)
                <tr>
                    <th>{{ $o -> crew_id}}</th>
                    <td>{{ $o -> item -> itemName}}</td>
                    <td>{{ $o -> quantity}}</td>
                    <td>{{ $o -> department}}</td>
                    <td>{{ $o -> item ->itemAge }}</td>
                    <td>{{ ($o -> in_progress === 'in_progress(Logistic)') ? "In Progress" : "Completed"}}</td>
                    <td>
                        <Button class="btn btn-primary">Approve</Button>
                        <Button class="btn btn-danger">Reject</Button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </main>
</div>

@endsection