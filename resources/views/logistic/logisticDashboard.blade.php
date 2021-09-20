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

        <br>
        @error('reason')
        <div class="alert alert-danger" style="width: 40%; margin-left: 30%">
            Alasan Wajib Diisi
        </div>
        @enderror

        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Order ID</th>
                    <th scope="col">Status</th>
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
                    <td>{{ $o -> item ->itemAge }} Bulan</td>
                    @if($o -> in_progress === 'in_progress(Logistic)')
                        <td>
                            In Progress (Logistic)
                        </td>
                        <td>
                            <a href="/logistic/order/{{ $o -> id }}/approve" class="btn btn-success">Approve</a>
                            <!-- Button trigger modal #1 -->
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#reject-{{ $o -> id }}">
                                Reject
                            </button>
                        </td>
                    @elseif($o -> in_progress === 'rejected(Logistic)')
                        <td>Rejected by Logistic</td>
                        <td>Rejected</td>
                    @elseif($o -> in_progress === 'in_progress(Purchasing)')
                        <td>In Progress (Purchasing)</td>
                        <td>Awaiting Approval on Purchasing</td>
                    @endif
                    </tr>
                @endforeach
                @foreach($orders as $o)
                    <!-- Modal #1-->
                    <div class="modal fade" id="reject-{{ $o->id }}" tabindex="-1" role="dialog" aria-labelledby="rejectTitle"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="rejectTitle">Reject Order : {{ $o -> item -> itemName }} | {{ $o -> quantity }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="/logistic/order/{{ $o -> id }}/reject">
                                        @csrf
                                        @method('put')
                                        <div class="form-group">
                                            <label for="reason">Reason</label>
                                            <textarea class="form-control" name="reason" id="reason" rows="3"></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Reject Order</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>

    </main>
</div>

@endsection