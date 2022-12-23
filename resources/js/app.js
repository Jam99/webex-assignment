import './bootstrap';

import $ from 'jquery';
window.$ = window.jQuery = $;

import '../sass/app.scss';

import * as bootstrap from 'bootstrap';

import {addValidation, setFormPendingStatus, init_drag_and_drop} from "./common";
window.addValidation = addValidation;
window.setFormPendingStatus = setFormPendingStatus;
window.init_drag_and_drop = init_drag_and_drop;

window.admin_ajax_url = "/admin/ajax/";

$().ready(function(){

    $("#admin_login_form").on("real_submit", function(){
        let data = $(this).serialize();
        setFormPendingStatus("admin_login_form");
        $.post(admin_ajax_url + "login", data, function(response){
            if(response.success){
                window.location = "/admin/gallery"
            }
            else{
                $("#login_feedback").html(response.message);
                setFormPendingStatus("admin_login_form", false);
            }
        })
    })

    $("#upload_btn").click(function(e){
        e.preventDefault();
        $("#image_upload_form").trigger("submit");
    })

    $("#image_upload_form").submit(function(e){
        e.preventDefault();

        let files = $("#image_file_input")[0].files;

        let form_data = new FormData();

        for(let i=0; i<files.length; i++){
            form_data.append('images[]', files[i])
        }

        let data = $(this).serializeArray();
        for(let i=0; i<data.length; i++){
            form_data.append(data[i].name, data[i].value);
        }

        $.ajax({
            url:  admin_ajax_url + "upload",
            type: 'POST',
            data: form_data,
            processData: false,
            contentType: false,
            success: function (response) {
                if(response.success){
                    window.location = "/admin/gallery"
                }
                else
                    alert("Unknown error. Failed to upload images.")
            },
            error: function(){
                alert("Unknown error. Failed to upload images.")
            }
        })
    })

    $(".gallery-img-action-toggle").click(function(e){
        e.preventDefault();
        let $a = $(this);

        let data = {
            id: $a.data("img-id"),
            _token: $('meta[name="csrf-token"]').attr('content')
        }

        $.post(admin_ajax_url + "toggle-image", data, function(response){
            if(response.success){
                $a.toggleClass("fa-eye fa-eye-slash");
            }
        })
    })

    $(".gallery-img-action-delete").click(function(e){
        e.preventDefault();
        let $a = $(this);

        let data = {
            id: $a.data("img-id"),
            _token: $('meta[name="csrf-token"]').attr('content')
        }

        $.post(admin_ajax_url + "delete-image", data, function(response){
            if(response.success){
                $a.closest(".gallery-img-container").remove();
            }
        })
    })

    $(".gallery-img").click(function(e){
        e.preventDefault();
        open_image_modal(this.src);
    })
})


function open_image_modal(src){
    let $modal = $("#image_modal")
    let image_modal = new bootstrap.Modal($modal[0], {});
    image_modal.show();

    $modal.find(".modal-content").html("<img src='"+src+"' draggable='false'>")
}
