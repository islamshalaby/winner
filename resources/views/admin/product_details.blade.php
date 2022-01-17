@extends('admin.app')

@section('title' , 'Admin Panel Ad Details')

@section('content')
        <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.product_details') }}</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive"> 
                <table class="table table-bordered mb-4">
                    <tbody>
                        @if(count($data['product']['images']) > 0 )
                            <tr>
                                <td class="label-table" > {{ __('messages.image') }} </td>
                                <td>
                                    @foreach ($data['product']['images'] as  $product_image)
                                        <a href="https://res.cloudinary.com/daww6jsmc/image/upload/v1583756977/{{ $product_image['image'] }}" target="_blank" >
                                            <img src="https://res.cloudinary.com/daww6jsmc/image/upload/w_100,q_100/v1583756977/{{ $product_image['image'] }}" />                                        
                                        </a>
                                    @endforeach
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td class="label-table" > {{ __('messages.title') }}</td>
                            <td>
                                {{ $data['product']['title'] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.details') }}</td>
                            <td>
                                {{ $data['product']['description'] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.price') }}</td>
                            <td>
                                {{ $data['product']['price'] }} {{ __('messages.dinar') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.quantity') }}</td>
                            <td>
                                {{ $data['product']['quantity'] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.remaining_quantity') }}</td>
                            <td>
                                {{ $data['product']['remaining_quantity'] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.prize_name') }}</td>
                            <td>
                                {{ $data['product']['prize_name'] }}
                            </td>
                        </tr>
                        @if($data['product']['prize_image'])
                            <tr>
                                <td class="label-table" > {{ __('messages.prize_image') }} </td>
                                <td>
                                    <a href="https://res.cloudinary.com/daww6jsmc/image/upload/v1583756977/{{ $data['product']['prize_image'] }}" target="_blank" >
                                        <img src="https://res.cloudinary.com/daww6jsmc/image/upload/w_100,q_100/v1583756977/{{ $data['product']['prize_image'] }}" />                                    
                                    </a>
                                </td>
                            </tr>
                        @endif                        
                    </tbody>
                </table>
            </div>

            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.the_winner') }}</h4>
                    </div>
                </div>
            </div>

           @if($data['product']['competition_over'] == 0 ) 
                @if(count($data['product']['coupons']) > 0 )
                
                @if (session('status'))
                    <div class="alert alert-danger mb-4" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">x</button>
                        <strong>Error!</strong> {{ session('status') }} </button>
                    </div> 
                @endif

                <form method="post" action="/admin-panel/coupons/winner/{{$data['product']['id']}}" enctype="multipart/form-data" >
                    @csrf
                    <div class="form-group mb-4">
                        <label for="winner_video_url">{{ __('messages.winner_video_url') }}</label>
                        <input type="text" name="winner_video" class="form-control" id="winner_video_url" placeholder="{{ __('messages.winner_video_url') }}" value="{{ $data['product']['winner_video'] }}" >
                    </div>

                    <div class="row" >
                       <div class="col-12" >
                           <label> {{ __('messages.select_winner') }} </label>
                       </div>
                        @foreach ($data['product']['coupons'] as $coupon)
                            <div class="col-md-3" >
                                 <div >
                                    <label class="new-control new-checkbox new-checkbox-text checkbox-primary">
                                      <input name="coupon" value="{{ $coupon->id }}" type="radio" class="new-control-input">
                                      <span class="new-control-indicator"></span><span class="new-chk-content">{{ $coupon->coupon_number }}</span>
                                    </label>
                                </div>     
                            </div> 
                        @endforeach
                    
                    </div>
                    <br>
                    <input type="submit" value="{{ __('messages.submit') }}" class="btn btn-primary">
                </form>
                @else
                    {{ __('messages.no_orders') }}
                @endif
           @else
                <div class="table-responsive"> 
                    <table class="table table-bordered mb-4">
                        <tr>
                            <td class="label-table" > {{ __('messages.winner_video_url') }}</td>
                            <td>
                                <a style="color: blue" href="{{ $data['product']['winner_video'] }}" target="_blank" >
                                    {{ __('messages.open_video') }}
                                </a>
                            </td>
                        </tr>

                        @foreach ($data['product']['coupons'] as $coupon)
                        <tr>
                            <td class="label-table" > {{ __('messages.coupon_number') }}</td>
                            <td>
                                <a style="color: blue" href="/admin-panel/coupons/details/{{ $coupon->id }}" target="_blank" >
                                    {{ $coupon->coupon_number }}
                                </a>
                                &nbsp;
                                <span style="color : {{ $coupon->winner == 1 ? 'green' : 'red' }}" >
                                    {{ $coupon->winner == 1 ? __('messages.winner') : __('messages.not_winner') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach    

                    </table>
                </div>    
           @endif 

        </div>
    </div>  
    
@endsection