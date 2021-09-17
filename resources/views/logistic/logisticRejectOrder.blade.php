@extends('../layouts.base')

@section('title', 'Logistic Dashboard')

@section('container')
<div class="row">
    @include('logistic.sidebar')

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <h2 class="mt-3 mb-5" style="text-align: center">Reject Order : #{{ $order -> id }}</h2>
        <div class="d-flex flex-row align-items-end">
            <h3 class="p-2">Item Name &emsp;: </h3>
            <h4 class="p-2 ">{{ $order -> item -> itemName }}</h4>
        </div>
        <div class="d-flex flex-row align-items-end">
            <h3 class="p-2">Quantity &emsp;&emsp;: </h3>
            <h4 class="p-2 ">{{ $order -> quantity }}</h4>
        </div>
        <div class="d-flex flex-row align-items-end mb-5">
            <h3 class="p-2">Department &ensp;: </h3>
            <h4 class="p-2 ">{{ $order -> department }}</h4>
        </div>

        <form method="POST" action="/logistic/order/{{ $order -> id }}/reject">
            @csrf
            <label for="reason">Reason</label>
            <textarea class="form-control" name="reason" id="reason" rows="3"></textarea>
            <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>
    </main>
</div>

@endsection
