@include("admin/head")
@include("admin/nav")
<div class="mt-5">
    <h1 class="h3 text-center">Upload your images</h1>
    <div class="text-center">
        <a href="/admin/gallery" class="btn btn-sm btn-outline-primary">Cancel</a>
        <a id="upload_btn" href="#" class="btn btn-sm btn-primary ms-2 disabled">Upload</a>
    </div>
    <div class="px-2">
        <form id="image_upload_form" enctype="multipart/form-data">
            @csrf
            <div class="drag-and-drop py-xl my-4 mx-2 mw-800 mx-auto" id="image_upload_dad">
                <p>Drag & drop your images</p>
                <p class="drag-and-drop-ext-info">png / jpg / jpeg / webp</p>
                <input type="file" id="image_file_input" accept="image/png, image/jpg, image/jpeg, image/webp" multiple>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            init_drag_and_drop("image_upload_dad", function () {
                $("#upload_btn.disabled").removeClass("disabled");
            })
        })
    </script>
</div>
@include("admin/footer")
