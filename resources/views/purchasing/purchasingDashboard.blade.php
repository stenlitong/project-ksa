@extends('../layouts.base')

@section('title', 'Purchasing Dashboard')

@section('container')
<div class="row">
    @include('purchasing.sidebar')

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        @include('../layouts/time')

        <div class="row">
            <div class="col">
                <h1>Supplier Card</h1>
            </div>
            <div class="col">
                <h2 class="mt-3 mb-4" style="text-align: center">Order List</h2>
                <div class="col-md-6">
                    <form action="">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Search by Order ID or Status..." name="search" id="search">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </form>
                </div>
                <table class="table">
                    <thead class="thead-dark">
                      <tr>
                        <th scope="col">Order ID</th>
                        <th scope="col">Cabang</th>
                        <th scope="col">Detail</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                      </tr>
                      <tr>
                        <td>Jacob</td>
                        <td>Thornton</td>
                        <td>@fat</td>
                      </tr>
                      <tr>
                        <td>Larry</td>
                        <td>the Bird</td>
                        <td>@twitter</td>
                      </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>

@endsection