@extends('admin.app')

@section('title' , 'Winner Dashboard Orders Section')

@section('content')
    <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.show_orders') }}</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive"> 
                <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>{{ __('messages.order_number') }}</th>
                            <th>{{ __('messages.product_name') }}</th>
                            <th>{{ __('messages.user_name') }}</th>
                            <th>{{ __('messages.coupons') }}</th>
                            <th>{{ __('messages.date') }}</th>
                            <th>{{ __('messages.seen?') }}</th>            
                            <th class="text-center">{{ __('messages.details') }}</th>   
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($data['orders'] as $order)
                            <tr class="{{$order->seen == 0 ? 'unread' : '' }}" >
                                <td><?=$i;?></td>
                                <td>{{ $order->order_number }}</td>
                                <td>
                                    <a href="/admin-panel/products/details/{{ $order->product->id }}" style="color: blue" target="_blank" >
                                        {{ $order->product->title }}                                    
                                    </a>
                                </td>
                                <td>
                                    <a href="/admin-panel/users/details/{{ $order->user->id }}" style="color: blue" target="_blank" >
                                        {{ $order->user->name }}
                                    </a>
                                </td>
                                <td>
                                    @if (count($order->coupons) > 0)
                                    @foreach ($order->coupons as $coupon)
                                    <span class="badge outline-badge-primary">{{ $coupon->coupon_number }}</span> <br/>
                                    @endforeach
                                    @endif
                                    
                                </td>
                                <td>{{ $order->created_at }}</td>
                                <td style="font-weight : bold" class="text-center blue-color" >
                                    {{ $order->seen == 0 ?  __('messages.unseen')  :  __('messages.seen')  }}
                                </td>

                                <td class="text-center blue-color"><a href="/admin-panel/orders/details/{{ $order->id }}" ><i class="far fa-eye"></i></a></td>
                                <?php $i++; ?>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- <div class="paginating-container pagination-solid">
            <ul class="pagination">
                <li class="prev"><a href="{{$data['users']->previousPageUrl()}}">Prev</a></li>
                @for($i = 1 ; $i <= $data['users']->lastPage(); $i++ )
                    <li class="{{ $data['users']->currentPage() == $i ? "active" : '' }}"><a href="/admin-panel/users/show?page={{$i}}">{{$i}}</a></li>               
                @endfor
                <li class="next"><a href="{{$data['users']->nextPageUrl()}}">Next</a></li>
            </ul>
        </div>   --}}
    </div>  
@endsection  

