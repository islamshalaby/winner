@extends('admin.app')

@section('title' , 'Admin Panel User Details')

@section('content')

        <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.coupon_details') }}</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive"> 
                <table class="table table-bordered mb-4">
                    <tbody>
                        <tr>
                            <td class="label-table" > {{ __('messages.coupon_number') }}</td>
                            <td>{{ $data['coupon']['coupon_number'] }}</td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.user_name') }}</td>
                            <td>
                                <a style="color : blue" target="_blank" href="/admin-panel/users/details/{{ $data['coupon']['user']['id'] }}" >
                                    {{ $data['coupon']['user']['name'] }}                                    
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.product_name') }}</td>
                            <td>
                                <a style="color : blue" target="_blank" href="/admin-panel/products/details/{{ $data['coupon']['product']['id'] }}" >
                                    {{ $data['coupon']['product']['title'] }}                                
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.winner_or_not') }}</td>
                            <td>
                               {{ $data['coupon']['winner'] == 1 ? __('messages.winner') : __('messages.not_winner') }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>  

@endsection



