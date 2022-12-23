@include("admin/head")
@include("admin/nav")
<div class="text-center mt-5">
    <div id="upload_btn_container" class="position-relative d-inline-block">
        <div>
            <i id="upload_icon" class="fa-solid fa-cloud-arrow-up"></i>
        </div>
        <a href="/admin/gallery/upload" class="btn btn-primary rounded-pill mt-2 shadow stretched-link">Upload images</a>
    </div>
    @if($image_upload_count)
        <div class="mt-4 text-secondary">
            Successfuly uploaded {{ $image_upload_count }} image(s).
        </div>
    @endif
    @if(!empty($images))
        <div class="my-5 row px-3">
            @foreach($images as $image)
                <div class="col-md-4 mt-3 col-lg-3 position-relative align-self-center gallery-img-container">
                    <img class="gallery-img shadow img-fluid rounded" src="{{ $image["url"] }}" draggable="false" alt="image - {{ pathinfo($image["url"])["filename"] }}">
                    <div class="gallery-img-actions text-center">
                        <a title="Toggle visibility" class="fa-solid text-decoration-none gallery-img-action-toggle {{ $image["public"] ? "fa-eye" : "fa-eye-slash"}}" href="#" data-img-id="{{ $image["id"] }}"></a>
                        <a title="Delete image" class="fa-solid fa-trash-can text-decoration-none gallery-img-action-delete" href="#" data-img-id="{{ $image["id"] }}"></a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@include("admin/footer")
