@extends('admin.app')

@section('title' , 'Admin Panel User Details')

@section('content')

        <div id="tableSimple" class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.address_details') }}</h4>
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive"> 
                <table class="table table-bordered mb-4">
                    <tbody>
                        <tr>
                            <td class="label-table" > {{ __('messages.address_name') }}</td>
                            <td>{{ $data['address']['address_name'] }}</td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.phone') }}</td>
                            <td>{{ $data['address']['phone'] }}</td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.phone') }}</td>
                            <td>{{ $data['address']['phone'] }}</td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.government') }}</td>
                            <td>{{ $data['address']['government'] }}</td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.sector') }}</td>
                            <td>{{ $data['address']['sector'] }}</td>
                        </tr>
                        @if($data['address']['street'])
                        <tr>
                            <td class="label-table" > {{ __('messages.street') }}</td>
                            <td>{{ $data['address']['street'] }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="label-table" > {{ __('messages.gadah') }}</td>
                            <td>{{ $data['address']['gadah'] }}</td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.building') }}</td>
                            <td>{{ $data['address']['building'] }}</td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.floor') }}</td>
                            <td>{{ $data['address']['floor'] }}</td>
                        </tr>
                        <tr>
                            <td class="label-table" > {{ __('messages.flat') }}</td>
                            <td>{{ $data['address']['flat'] }}</td>
                        </tr>
                        @if($data['address']['extra_details'])
                        <tr>
                            <td class="label-table" > {{ __('messages.extra_details') }}</td>
                            <td>{{ $data['address']['extra_details'] }}</td>
                        </tr>
                        @endif
                        @if($data['address']['latitude'])
                        <tr>
                            <td class="label-table" > {{ __('messages.location') }}</td>
                            <td>
                                <a target="_blank" href="https://www.google.com.eg/maps/@{{ $data['address']['latitude'] }},{{ $data['address']['longitude'] }},15z" style="color:blue" >
                                    {{ __('messages.open_map') }}
                                </a>
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>  

@endsection



