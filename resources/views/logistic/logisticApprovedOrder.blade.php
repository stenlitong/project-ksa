@extends('../layouts.base')

@section('title', 'Logistic Approve Order')

@section('container')
<div class="row">
    @include('logistic.sidebar')
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

        <h2 class="mt-3 mb-2" style="text-align: center">Order # {{ $orderHeads -> order_id }}</h2>

            <div class="row mt-5">
                <div class="col">
                    <form method="POST" action="/logistic/order/{{ $orderHeads -> id }}/approve">
                        @csrf
                        <div class="form-group">
                            <label for="boatName">Nama Kapal</label>
                            <input type="text" class="form-control" id="boatName" name="boatName" value="{{ $orderHeads -> boatName }}" readonly>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="sender">Yang Menyerahkan</label>
                                    <input type="text" class="form-control" id="sender" name="sender" value="{{ Auth::user()->name }}" readonly>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="receiver">Yang Menerima</label>
                                        <input type="text" class="form-control" id="receiver" name="receiver" value="{{ $orderHeads -> user -> name }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="cabang">Lokasi Penerima</label>
                            <input type="text" class="form-control" id="cabang" name="cabang" value="{{ $orderHeads -> user -> cabang }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="expedition">Ekspedisi</label>
                            <select class="form-control" id="expedition" name="expedition">
                                <option value="onsite">Onsite</option>
                                <option value="JNE">JNE</option>
                                <option value="TIKI">TIKI</option>
                                <option value="JWT">JWT</option>
                                <option value="Lion">Lion</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="noResi">Nomor Resi (optional)</label>
                            <input type="text" class="form-control" id="noResi" name="noResi"
                                placeholder="Input Nomor Resi (optional)">
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi (optional)</label>
                            <textarea class="form-control" name="description" id="description" rows="3"
                                placeholder="Input Keterangan"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" style="margin-left: 45%">Submit</button>
                    </form>
                </div>
                <div class="col mt-3">
                    <table class="table" id="myTable">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Item Barang</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orderDetails as $od)
                            <tr>
                                <td>{{ $od -> item -> itemName }}</td>
                                <td>{{ $od -> quantity }} {{ $od -> unit }}</td>
                                @if(preg_replace('/[a-zA-z ]/', '', $od -> quantity) > $od -> item -> itemStock)
                                    <td style="color: red">{{ $od -> item -> itemStock}} {{ $od -> item -> unit }} (Stok Tidak Mencukupi)</td>
                                @else
                                    <td style="color: green">{{ $od -> item -> itemStock}} {{ $od -> item -> unit }}</td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
</div>

@endsection