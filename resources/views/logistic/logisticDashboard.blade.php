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

        <h2 class="mt-3 mb-3" style="text-align: center">Order List</h2>
        <div class="d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
        <table class="table">
            <thead class="thead-dark">
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
                    @if($o -> in_progress === 'in_progress(Logistic)')
                        <td>
                            In Progress (Logistic)
                        </td>
                        <td>
                            {{-- <Button type="button" class="btn btn-primary">Approve</Button> --}}
                            <a href="/logistic/order/{{ $o -> id }}/approve" class="btn btn-success">Approve</a>
                            <a href="/logistic/order/{{ $o -> id }}/reject" class="btn btn-danger">Reject</a>
                            <!-- Button trigger modal -->
                            {{-- <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#reject">
                                Reject
                            </button> --}}

                            <!-- Modal -->
                            {{-- <div class="modal fade" id="reject" tabindex="-1" role="dialog" aria-labelledby="rejectTitle"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="rejectTitle">Reject Order</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="/logistic/order/{{ $o -> id }}">
                                                @csrf
                                                @method('put')
                                                <div class="form-group">
                                                    <label for="reason">{{ $o -> id }}</label>
                                                    <textarea class="form-control" name="reason" id="reason" rows="3"></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Reject Order</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        </td>
                    @elseif($o -> in_progress === 'rejected(Logistic)')
                        <td>Rejected</td>
                        <td>Rejected</td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>

    </main>
</div>

@endsection