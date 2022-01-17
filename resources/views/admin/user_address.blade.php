@extends('admin.app')

@section('title' , 'Med&Law Dashboard Users Section')

@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.show_address') }}</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive"> 
                <table id="html5-extension" class="table table-hover non-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th>id</th>    
                            <th>{{ __('messages.address_name') }}</th>
                            <th>{{ __('messages.phone') }}</th>
                            <th class="text-center" >{{ __('messages.details') }}</th>                            
                            @if(Auth::user()->delete_data)
                                <th class="text-center" >{{ __('messages.delete') }}</th>                            
                            @endif
                      
                        </tr>
                    </thead>
                    <tbody>
                    <?php $i = 1; ?>
                    @foreach ($data['address'] as $address)
                        <tr>
                            <td><?=$i;?></td>
                            <td>{{ $address->address_name }}</td>
                            <td>{{ $address->phone }}</td>
                            <td class="text-center blue-color"><a href="/admin-panel/users/address/details/{{ $address->id }}" ><i class="far fa-eye"></i></a></td>
                            @if(Auth::user()->delete_data)
                                <td class="text-center blue-color" ><a onclick="return confirm('Are you sure you want to delete this item?');" href="/admin-panel/users/address/delete/{{ $address->id }}" ><i class="far fa-trash-alt"></i></a></td>
                            @endif                                            
                            <?php $i++; ?>
                        </tr>
                         @endforeach
                    </tbody>
                </table>
            </div>
        </div>  
    </div>  
@endsection  

