@extends('admin.app')

@section('title' , 'Med&Law Dashboard Users Section')

@section('content')
    <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.show_products') }}</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive"> 
                <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>{{ __('messages.title') }}</th>
                            <th>{{ __('messages.image') }}</th>
                            <th>{{ __('messages.competition') }}</th>                            
                            <th class="text-center">{{ __('messages.details') }}</th>
                            @if(Auth::user()->update_data) 
                                <th class="text-center">{{ __('messages.edit') }}</th>
                            @endif                            
                            @if(Auth::user()->delete_data) 
                                <th class="text-center">{{ __('messages.delete') }}</th>
                            @endif    
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($data['products'] as $product)
                            <tr>
                                    <td><?=$i;?></td>
                                    <td>{{ $product->title }}</td>
                                    <td>
                                        @if($product->image)
                                            <img src="https://res.cloudinary.com/daww6jsmc/image/upload/w_100,q_100/v1583756977/{{ $product->image }}"  />
                                        @else
                                            No Image
                                        @endif
                                    </td>
                                    <td style="color: {{ $product->competition_over == 1 ? 'green' : 'red' }}" >
                                        {{ $product->competition_over == 1 ? __('messages.finished') : __('messages.not_finished') }}
                                    </td>
                                    <td class="text-center blue-color" ><a href="/admin-panel/products/details/{{ $product->id }}" ><i class="far fa-eye"></i></a></td>
                                
                                @if(Auth::user()->update_data) 
                                    <td class="text-center blue-color" ><a href="/admin-panel/products/edit/{{ $product->id }}" ><i class="far fa-edit"></i></a></td>
                                @endif
                                @if(Auth::user()->delete_data) 
                                    <td class="text-center blue-color" ><a onclick="return confirm('Are you sure you want to delete this item?');" href="/admin-panel/products/delete/{{ $product->id }}" ><i class="far fa-trash-alt"></i></a></td>
                                @endif                                
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

