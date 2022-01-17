@extends('admin.app')

@section('title' , 'Admin Panel Add New Ad')

@section('content')
    <div class="col-lg-12 col-12 layout-spacing">
        <div class="statbox widget box box-shadow">
            <div class="widget-header">
                <div class="row">
                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                        <h4>{{ __('messages.add_new_ad') }}</h4>
                 </div>
        </div>
        @if (session('status'))
            <div class="alert alert-danger mb-4" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">x</button>
                <strong>Error!</strong> {{ session('status') }} </button>
            </div> 
        @endif
        <form action="" method="post" enctype="multipart/form-data" >
            @csrf
            <div class="custom-file-container" data-upload-id="myFirstImage">
                <label>{{ __('messages.upload') }} ({{ __('messages.single_image') }}) <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
                <label class="custom-file-container__custom-file" >
                    <input type="file" required name="image" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
                    <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                    <span class="custom-file-container__custom-file__custom-file-control"></span>
                </label>
                <div class="custom-file-container__image-preview"></div>
            </div>
            <div class="form-group mb-4">
                <label for="adtype">{{ __('messages.adtype') }}</label>
                <select required name="type" class="form-control select-ad-type">
                    <option selected disabled >{{ __('messages.select') }}</option>
                    <option value="inside" >{{ __('messages.insideapp') }}</option>
                    <option value="outside" >{{ __('messages.outsideapp') }}</option>
                </select>                
            </div>

            <div class="form-group mb-4  outside-div hidden-div">
                <label for="link">{{ __('messages.link') }}</label>
                <input  type="text" name="contentLink" class="form-control" id="link" placeholder="{{ __('messages.link') }}" value="" >
            </div>
            
            <div class="form-group mb-4 inside-div hidden-div">
                <label for="link">{{ __('messages.select_product') }}</label>
                <select  name="contentId" class="form-control">
                    <option selected disabled >{{ __('messages.select') }}</option>
                    @foreach ( $data['products'] as $product )
                        <option value="{{ $product->id }}" >{{ $product->title }}</option>
                    @endforeach                
                </select> 
            </div>
            <input type="submit" value="{{ __('messages.submit') }}" class="btn btn-primary">
        </form>
    </div>
@endsection