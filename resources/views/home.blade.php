@include("head")
<div class="pt-5">
    <h1 class="display-3 text-center">Webex Gallery<br><small class="text-secondary display-5">Assignment</small></h1>
</div>
<a id="admin_link" title="Admin" class="link-primary" href="/admin"><i class="fa-solid fa-gears"></i></a>
@if(!empty($images))
    <div class="my-5 row px-3">
        @foreach($images as $image)
            <div class="col-md-4 mt-3 col-lg-3 position-relative align-self-center gallery-img-container">
                <img data-bs-toggle="modal" data-bs-target="#image_modal" class="gallery-img shadow img-fluid rounded" src="{{ $image["url"] }}" draggable="false" alt="image - {{ pathinfo($image["url"])["filename"] }}">
            </div>
        @endforeach
    </div>
    <div id="image_modal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content"></div>
        </div>
    </div>
@else
    <div class="text-center mt-100 text-secondary display-6">
        <p>This gallery seems to be empty right now.</p>
        <i class="fa-regular fa-face-frown-open"></i>
    </div>
@endif
@include("footer")
