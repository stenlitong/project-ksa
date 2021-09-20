@extends('../layouts.base')

@section('title', 'Logistic Dashboard')

@section('container')
<div class="row">
    @include('logistic.sidebar')

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <h2 class="mt-3 mb-3" style="text-align: center">Approved Order List</h2>
        <div class="d-flex justify-content-center">
            {{ $transactions->links() }}
        </div>

        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Order ID</th>
                    <th scope="col">Item</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Department</th>
                    <th scope="col">No. PR</th>
                    <th scope="col">Progress</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $t)
                <tr>
                    <th>{{ $t -> id}}</th>
                    <td>{{ $t -> itemName}}</td>
                    <td>{{ $t -> quantity}}</td>
                    <td>{{ $t -> department}}</td>
                    <td>{{ $t -> noPr }} Bulan</td>
                    @if($t -> status === 'Awaiting Approval')
                        <td>
                            In Progress (Purchasing)
                        </td>
                        <td>
                            <a href="/logistic/order/{{ $t -> id }}/download" class="btn btn-success">Download</a>
                        </td>
                    @elseif($t -> status === 'Approved')
                        <td>Rejected by Purchasing</td>
                        <td>Rejected</td>
                    @endif
                    </tr>
                @endforeach
            </tbody>
        </table>

    </main>
</div>

@endsection