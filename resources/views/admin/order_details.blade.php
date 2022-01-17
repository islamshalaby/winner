@extends('admin.app')

@section('title' , 'Admin Panel Ad Details')

@section('content')
        <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.order_details') }}</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive"> 
                <table class="table table-bordered mb-4">
                    <tbody>
                        <tr>
                            <td class="label-table" > {{ __('messages.order_number') }}</td>
                            <td>
                                {{ $data['order']['order_number'] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.user_name') }}</td>
                            <td>
                                <a href="/admin-panel/users/details/{{ $data['order']['user']['id'] }}" style="color: blue" target="_blank" >
                                    {{ $data['order']['user']['name'] }}                                
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.product_name') }}</td>
                            <td>
                                <a href="/admin-panel/products/details/{{ $data['order']['product']['id'] }}" style="color: blue" target="_blank" >
                                    {{ $data['order']['product']['title'] }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.product_image') }}</td>
                            <td>
                                <img src="https://res.cloudinary.com/daww6jsmc/image/upload/w_100,q_100/v1583756977/{{$data['order']['product']['image']}}" />
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.unit_cost') }}</td>
                            <td>
                                {{ $data['order']['unit_cost'] . ' ' .__('messages.dinar')  }} 
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.product_count') }}</td>
                            <td>
                                {{ $data['order']['count'] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.delivery_cost') }}</td>
                            <td>
                                {{ $data['order']['delivery_cost'] . ' ' .__('messages.dinar')  }} 
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.total_cost') }}</td>
                            <td>
                                {{ $data['order']['total_cost'] . ' ' .__('messages.dinar')  }} 
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.payment_method') }}</td>
                            <td>
                                @switch($data['order']['payment_method'])
                                    @case(1)
                                        {{ __('messages.myfatora') }}    
                                        @break

                                    @case(2)
                                        {{ __('messages.cash') }}
                                        @break
                                    
                                @endswitch 
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.coupons') }}</td>
                            <td>
                                @if (count($data['order']->coupons) > 0)
                                    @foreach ($data['order']->coupons as $coupon)
                                    <span class="badge outline-badge-primary">{{ $coupon->coupon_number }}</span> <br/>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.order_status') }}</td>
                            <td style="color : {{ $data['order']['delivered'] == 1 ? "green" : "red" }}" >
                                 {{ $data['order']['delivered'] == 1 ? __('messages.delivered') : __('messages.not_delivered') }} 
                                 &nbsp; &nbsp; 
                                 @if($data['order']['delivered'] != 1)
                                    - &nbsp; &nbsp; <a  style="color: blue" href="/admin-panel/orders/delivered/{{$data['order']['id']}}" > {{__('messages.delivered?')}} </a>
                                 @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.order_date') }}</td>
                            <td>
                                {{ $data['order']['created_at'] }} 
                            </td>
                        </tr>                        
                    </tbody>
                </table>
            </div>
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.order_address_details') }}</h4>
                    </div>
                </div>
            </div>

            <div class="table-responsive"> 
                <table class="table table-bordered mb-4">
                    <tbody>
                        <tr>
                            <td class="label-table" > {{ __('messages.address_name') }}</td>
                            <td>
                                {{ $data['order']['address']['address_name'] }}
                            </td>
                        </tr> 
                                               
                        <tr>
                            <td class="label-table" > {{ __('messages.phone') }}</td>
                            <td>
                                {{ $data['order']['address']['phone'] }}
                            </td>
                        </tr>
                        @if($data['order']['address']['latitude'])
                            <tr>
                                <td class="label-table" > {{ __('messages.location') }}</td>
                                <td>
                                    <a style="color: blue" target="_blank" href="{{'https://www.google.com.eg/maps/@'.$data['order']['address']['latitude'].','.$data['order']['address']['longitude'].',14z'}}" >
                                        {{ __('messages.open_map') }}
                                    </a>
                                </td>
                            </tr>                        
                        @endif
                        <tr>
                            <td class="label-table" > {{ __('messages.government') }}</td>
                            <td>
                                {{ $data['order']['address']['government'] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.sector') }}</td>
                            <td>
                                {{ $data['order']['address']['sector'] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.gadah') }}</td>
                            <td>
                                {{ $data['order']['address']['gadah'] }}
                            </td>
                        </tr>
                        @if($data['order']['address']['street'])
                            <tr>
                                <td class="label-table" > {{ __('messages.street') }}</td>
                                <td>
                                    {{ $data['order']['address']['street'] }}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td class="label-table" > {{ __('messages.building') }}</td>
                            <td>
                                {{ $data['order']['address']['building'] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.floor') }}</td>
                            <td>
                                {{ $data['order']['address']['floor'] }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.flat') }}</td>
                            <td>
                                {{ $data['order']['address']['flat'] }}
                            </td>
                        </tr>
                        @if($data['order']['address']['extra_details'])
                            <tr>
                                <td class="label-table" > {{ __('messages.extra_details') }}</td>
                                <td>
                                    {{ $data['order']['address']['extra_details'] }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>        
            </div>    
        </div>
    </div>  
    
@endsection