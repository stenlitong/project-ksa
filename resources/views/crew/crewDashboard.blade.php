@extends('../layouts.base')

@section('title', 'Crew Dashboard')

@section('container')
<div class="row">
    @include('crew.sidebar')

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h2>Welcome back, {{ Auth::user()->name }} !</h2>
            <h3>{{ "Today is, " . date('l M Y')}}</h3>
        </div>
        
    </main>
</div>

@endsection