@extends('layouts.client')
@section('styles')
<style>
    .alert-warning,.alert-info{
        font-size: 13px;
    }

    .remove-float{
        float: none !important;
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        font-weight: bolder;
    }

    .img-card-holder{
        height: 170px;
        width: 100%;
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
    }

    .rounded-corners{
        border-radius: 15px;
    }

    .coming-soon{
        position: absolute;
        left: 0;
        top: 0;
        background-color:rgba(0, 0, 0, 0.8);
        background-size: contain !important;
    }

    .remove-bottom-rounded{
        border-bottom-left-radius: 0px !important;
        border-bottom-right-radius: 0px !important;
    }

    .add-bottom-rounded{
        border-bottom-left-radius: 15px !important;
        border-bottom-right-radius: 15px !important;
    }

    .my-points{
        text-align: right;
    }
    .text-18 {
        font-size: 18px !important;
    }
    @media only screen and (max-width: 768px) {
        .my-points{
            text-align: left;
            margin-top: 2.5rem;
        }
    }
</style>
@endsection
@section('content')
<!-- Info boxes -->
    @if(Auth::user()->type == 'player')
       <div class="container">
        <div class="row">
            <div class="col-12 mt-4 mb-4">
                <div class="row border-bottom">
                    <div class="col-md-6">
                        <h4><strong>Today's Events</strong></h4>
                    </div>
                    <div class="col-md-6 my-points">
                        <p class="text-warning">Your Points: <span>{{bcdiv(Auth::user()->wallet->balance,1,2)}}</span></p>
                    </div>
                </div>
            </div>
            @foreach ($events as $ev)
            <div class="col-12 col-sm-6 col-md-6">
                <div class="card">
                    @php
                        $game = \App\Models\Game::find($ev->game_id);
                        $f = \App\Models\Fight::find($ev->active_fight)
                    @endphp

                    <div class="card-body text-center img-card-holder remove-bottom-rounded" style="background-image: url({{asset($game->banner)}})">
                        @if($game->status =='coming_soon')
                            <div class="img-card-holder coming-soon" style="background-image: url({{asset('images/soon.png')}})"></div>
                        @endif
                    </div>
                    <div class="card-footer pt-2">
                        <h6 class="text-left text-18 text-warning mb-2"><strong>{{strtoupper($ev->name)}}</strong></h6>
                        <h6 class="text-left mb-2"><strong>{{date('l, F d, Y')}}</strong></h6>
                        <h6 class="text-left mb-2">Fight #: {{strtoupper($f->fight_number)}}</h6>

                        <a href="{{ $game->status == 'coming_soon' ? '#' : route('live.fight',$ev->id)}}" class="btn btn-outline-warning btn-lg btn-block text-warning mt-3">
                            <span class="text-18">ENTER EVENT</span>
                        </a>

                    </div>



                </div>
            </div>
            @endforeach

        </div>
       </div>
        <!-- /.row -->
    @endif

@endsection
@section('scripts')

@endsection
