<div class="flex-wrap flex-md-nowrap align-items-center mb-3 mt-3 ml-3 rcorners1">
    @if((int)date("H") >= 18)
        <h2>Malam, {{ Auth::user()->name }} !</h2>
    @elseif((int)date("H") >= 15)
        <h2>Sore, {{ Auth::user()->name }} !</h2>
    @elseif((int)date("H") >= 12)
        <h2>Siang, {{ Auth::user()->name }} !</h2>
    @else
        <h2>Pagi, {{ Auth::user()->name }} !</h2>
    @endif
    <h3>{{ "Today is " . date('l') . ', ' . date('d M Y')}}</h3>
</div>

<style>
    .rcorners1 {
        border-radius: 25px;
        /* background-color: rgba(255, 0, 0, 0.5); */
        background-color: rgba(245, 97, 97, 0.7);
        padding: 20px;
        width: 600px;
        height: 150px;
    }
</style>