@extends('admin.app')

@section('title' , 'Admin Panel Add New Product')

@section('content')
<div class="col-lg-12 col-12 layout-spacing">
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                    <h4>{{ __('messages.add_new_product') }}</h4>
             </div>
    </div>
    
    @if (session('status'))
        <div class="alert alert-danger mb-4" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">x</button>
            <strong>Error!</strong> {{ session('status') }} </button>
        </div> 
    @endif

    <form method="post" action="" enctype="multipart/form-data" >
     @csrf
        <div class="custom-file-container" data-upload-id="myFirstImage">
        <label>{{ __('messages.product_images') }} ({{ __('messages.multi_images') }}) <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
        <label class="custom-file-container__custom-file" >
            <input required type="file" multiple name="image[]" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
            <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
            <span class="custom-file-container__custom-file__custom-file-control"></span>
        </label>
        <div class="custom-file-container__image-preview"></div>
    </div>
 
    <div class="form-group mb-4">
        <label for="title">{{ __('messages.title') }}</label>
        <input required type="text" name="title" class="form-control" id="title" placeholder="{{ __('messages.title') }}" value="" >
    </div>       
    <div class="form-group mb-4">
        <label for="quantity">{{ __('messages.quantity') }}</label>
        <input required type="number" name="quantity" class="form-control" id="quantity" placeholder="{{ __('messages.quantity') }}" value="" >
    </div>       
    <div class="form-group mb-4">
        <label for="price">{{ __('messages.price') }}</label>
        <input required type="number" name="price" step="any" min="0" class="form-control" id="price" placeholder="{{ __('messages.price') }}" value="" >
    </div>       
    <div class="form-group mb-4">
        <label for="description">{{ __('messages.description') }}</label>
        <textarea required type="text" name="description" class="form-control" id="description" placeholder="{{ __('messages.description') }}" value="" ></textarea>
    </div>       
    <div class="form-group mb-4">
        <label for="prize_name">{{ __('messages.prize_name') }}</label>
        <input required type="text" name="prize_name" class="form-control" id="prize_name" placeholder="{{ __('messages.prize_name') }}" value="" >
    </div>
    <div class="custom-file-container" data-upload-id="mySecondImage">
        <label>{{ __('messages.prize_image') }} ({{ __('messages.single_image') }}) <a href="javascript:void(0)" class="custom-file-container__image-clear" title="Clear Image">x</a></label>
        <label class="custom-file-container__custom-file" >
            <input required type="file"  name="prize_image" class="custom-file-container__custom-file__custom-file-input" accept="image/*">
            <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
            <span class="custom-file-container__custom-file__custom-file-control"></span>
        </label>
        <div class="custom-file-container__image-preview"></div>
    </div>

    <input type="submit" value="{{ __('messages.submit') }}" class="btn btn-primary">
</form>
</div>

@endsection