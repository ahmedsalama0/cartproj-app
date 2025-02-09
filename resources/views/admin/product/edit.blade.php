<x-app-layout>
    <section class="wsus__product mt_145 pb_100">
        <div class="container">
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger">{{ $error }}</div>
                @endforeach
            @endif
            <h4 class="pt-3 py-3 text-primary">Dashboard</h4>
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5>Update Product</h5>
                    <a href="{{ route('product.index') }}" class="btn btn-primary">Go Back</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('product.update', $product->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <div>
                                <img src="{{ asset($product->image) }}" style="width: 100px !important;"
                                    alt="product image">
                            </div>
                            <x-input-label value="Image" />
                            <x-text-input type="file" class="form-control" name="image" />
                        </div>
                        <div class="form-group">
                            <div class="d-flex">
                                @foreach ($product->images as $image)
                                    <img src="{{ asset($image->path) }}" class="ms-2 me-2 mt-2 mb-2"
                                        style="width: 100px !important;" alt="product image">
                                @endforeach
                            </div>
                            <x-input-label value="Images" />
                            <x-text-input type="file" class="form-control" name="images[]" multiple />
                        </div>
                        <div class="form-group">
                            <x-input-label value="Name" />
                            <x-text-input type="text" class="form-control" name="name"
                                value="{{ $product->name }}" />
                        </div>
                        <div class="form-group">
                            <x-input-label value="Price" />
                            <x-text-input type="text" class="form-control" name="price"
                                value="{{ $product->price }}" />
                        </div>
                        <div class="form-group">
                            <x-input-label value="Colors" />
                            <x-select-input name="colors[]" multiple>
                                <option value="">Select</option>
                                <option value="black" @selected(in_array('black', $colors))>Black</option>
                                <option value="green" @selected(in_array('green', $colors))>Green</option>
                                <option value="red" @selected(in_array('red', $colors))>Red</option>
                                <option value="cyan" @selected(in_array('cyan', $colors))>Cyan</option>
                            </x-select-input>
                        </div>
                        <div class="form-group">
                            <x-input-label value="Short Description" />
                            <x-text-input type="text" class="form-control" name="short_description"
                                value="{{ $product->short_description }}" />
                        </div>
                        <div class="form-group">
                            <x-input-label value="Qty" />
                            <x-text-input type="text" class="form-control" name="qty"
                                value="{{ $product->qty }}" />
                        </div>
                        <div class="form-group">
                            <x-input-label value="Sku" />
                            <x-text-input type="text" class="form-control" name="sku"
                                value="{{ $product->sku }}" />
                        </div>
                      
                        <div class="form-group">
                            <x-input-label value="Description" />
                            <textarea name="description" id="editor" cols="30" rows="10">{!! $product->description !!}</textarea>
                        </div>
                        <x-primary-button>Submit</x-primary-button>
                </div>
                </form>
            </div>
        </div>
    </section>

    <x-slot name="scripts">
        <script>
            tinymce.init({
                selector: 'textarea#editor',
                height: 500,
                plugins: [
                    'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                    'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                    'insertdatetime', 'media', 'table', 'help', 'wordcount'
                ],
                toolbar: 'undo redo | blocks | ' +
                    'bold italic backcolor | alignleft aligncenter ' +
                    'alignright alignjustify | bullist numlist outdent indent | ' +
                    'removeformat | help',
                content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
            });
        </script>
    </x-slot>
</x-app-layout>
