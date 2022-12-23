//client side validation
function runValidation(e){
    const form_rules = e.data;

    let $element = $(this);
    let $form = $element.closest("form");
    let field_name = $element.attr("name");
    let invalid_feedbacks = [];
    let val = $element.val();

    //if($element.is("[type=hidden]") && $element.val() === "0")
    //val = null;

    if($element.is("[type=checkbox]"))
        val = $element.is(":checked");

    if(form_rules[field_name]){
        let k = Object.keys(form_rules[field_name]);
        let v = Object.values(form_rules[field_name]);

        for(let i=0; i<k.length; i++){
            let $tmp = checkValidationRule($form, k[i], v[i], val);
            if($tmp !== true)
                invalid_feedbacks.push($tmp);
        }
    }

    if(invalid_feedbacks.length){
        for(let i=0; i<invalid_feedbacks.length; i++){
            $element.siblings(".invalid-feedback").each(function(){
                if(!invalid_feedbacks.includes($element.text())){
                    $(this).remove();
                }
            })

            if(!$element.siblings(".invalid-feedback:contains("+ invalid_feedbacks[i] +")").length)
                addInvalidFeedback($element, invalid_feedbacks[i]);
        }
    }
    else{
        $element.filter(".is-invalid").removeClass("is-invalid").siblings(".invalid-feedback").remove();
    }

    //sync validation
    let sync_validation = $element.data("sync-validation");
    if(sync_validation){
        $form.find("[name="+sync_validation+"]").trigger("change");
    }
}


function checkValidationRule($form, rule_name, rule_args, check_val = null){
    //if field is empty it is ok
    if(check_val !== null && check_val.length === 0 && rule_name !== "required" && rule_name !== "required_with_radio_option"){
        return true;
    }

    switch(rule_name){
        case "required":
            if(check_val)
                return true;
            break;
        case "required_with_radio_option":
            console.log(rule_args, $form.find("[name="+ rule_args[1] +"]:checked").val());
            if($form.find("[name="+ rule_args[1] +"]:checked").val() !== rule_args[2])
                return true;
            else if(check_val)
                return true;
            break;
        case "min_length":
            if(check_val.length >= rule_args[1])
                return true;
            break;
        case "max_length":
            if(check_val.length <= rule_args[1])
                return true;
            break;
        case "valid_email":
            if(check_val.toLowerCase() === check_val && check_val.match(/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/))
                return true;
            break;
        case "matches":
            if(check_val === $form.find("[name="+ rule_args[1] +"]").val())
                return true;
            break;
        case "not_matches":
            if(check_val !== $form.find("[name="+ rule_args[1] +"]").val())
                return true;
            break;
        case "matches_regex":
            if(check_val.match(rule_args[1]))
                return true;
            break;
        case "valid_domain":
            if(check_val.match(/^([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,}$/))
                return true;
            break;
        default:
            console.error("Invalid validation rule.");
            return;
    }

    return rule_args[0];
}


export function addValidation(form_id, form_rules){
    let $form = $("#"+form_id);

    if(!$form.length)
        return;

    $form.find("input,textarea").on("input", form_rules, runValidation);
    $form.find("input,textarea,select").on("change", form_rules, runValidation);
    $form.on("submit", function(e){
        e.preventDefault();
        e.stopImmediatePropagation();

        //triggering events on submit to run validations
        $form.find("input,textarea,select").trigger("change");
        //client side checking of reCAPTCHA
        let captcha_is_ok = true;
        let $captcha = $form.find(".g-recaptcha");
        if($captcha.length && !$form.find(".g-recaptcha-response").val()) {
            captcha_is_ok = false;
            if(!$captcha.is(".look-here")) {
                $captcha.addClass("look-here");
                setTimeout(function () {
                    $captcha.removeClass("look-here");
                }, 500)
            }
        }

        let $invalid_inputs = $form.find(".is-invalid:not(.skip-validation)");

        if($invalid_inputs.length)
            $invalid_inputs.first().focus();
        else if(captcha_is_ok)
            $form.trigger("real_submit");
    });

    //preparing data sync attributes for optimized validation
    let v = Object.values(form_rules);
    let k = Object.keys(form_rules);
    for(let i=0; i<v.length; i++){
        let _v = Object.values(v[i]);
        let _k = Object.keys(v[i]);
        for(let j=0; j<_k.length; j++){
            if(_k[j] === "matches" || _k[j] === "not_matches"){
                $form.find("[name="+ _v[j][1] +"]").attr("data-sync-validation", k[i]);
            }
        }
    }
}


function addInvalidFeedback($input, text){
    $input.filter(":not(.is-invalid)").addClass("is-invalid");
    $input.parent().append("<div class='invalid-feedback'>"+ text +"</div>");
}


export function handleSimpleErrors(errors = [], handlers = {}){
    let unknown_count = 0;

    for(let i=0; i<errors.length; i++){
        if(handlers[errors[i]])
            handlers[errors[i]]();
        else
            unknown_count++;
    }

    if(unknown_count > 0){
        alert(unknown_count + " " + translates.unknown_error_part);
    }
    else if(errors.length === 0){
        alert(translates.unknown_error);
    }
}


export function setFormPendingStatus(form_id, pending_status = true){
    let $form = $("#"+form_id)
    let $submit = $form.find("button[type=submit]");

    if(pending_status){
        $submit.prop("disabled", true);
        $submit.prepend("<span class='spinner-border spinner-border-sm me-1' role='status'></span>");
    }
    else{
        $submit.prop("disabled", false);
        $submit.children("span").remove();
    }
}


function sync_drag_and_drop_info(element_id, files){
    const $drop_area = $("#"+element_id);

    let total_size = 0;

    for(let i=0; i<files.length; i++){
        //cross-browser support
        total_size += files[i].size;
    }

    $drop_area.children(":not(input)").remove();
    $drop_area.prepend("<p>"+ files.length +" image"+ (files.length > 1 ? "s" : "") +"<br>"+bytesToSize(total_size)+"</p>");
}


export function init_drag_and_drop(element_id, callback){
    const $drop_area = $("#"+element_id);
    const $file_input = $drop_area.children("input");

    $drop_area.click(function(){
        $file_input[0].click();
    })

    $file_input[0].addEventListener("change", (event) => {
        if(!event.target.files.length)
            return;

        if(!validate_files($file_input.attr("accept").replace(" ", "").split(","), event.target.files))
            return;

        sync_drag_and_drop_info(element_id, event.target.files);
        callback(event.target.files);
    })

    $drop_area[0].addEventListener('dragover', (event) => {
        event.stopPropagation();
        event.preventDefault();
        event.dataTransfer.dropEffect = 'copy';
    });

    $drop_area[0].addEventListener('drop', (event) => {
        event.stopPropagation();
        event.preventDefault();

        if(!event.dataTransfer.files.length)
            return;

        if(!validate_files($file_input.attr("accept").replace(" ", "").split(","), event.dataTransfer.files))
            return;

        $drop_area.removeClass("dragging");
        sync_drag_and_drop_info(element_id, event.dataTransfer.files);
        callback(event.dataTransfer.files);
    });

    $drop_area[0].addEventListener("dragenter", function(){
        $(this).addClass("dragging");
    })

    $drop_area[0].addEventListener("dragleave", function(e){
        if(!$(e.fromElement).parent().is(".drag-and-drop") && !$(e.fromElement).is(".drag-and-drop"))
            $(this).removeClass("dragging");
    })
}

function validate_files(accept_array, files){
    let ok = false;
    for(let j=0; j<files.length; j++) {
        for(let i=0; i<accept_array.length; i++){
            if (accept_array[i] === files[j].type)
                ok = true;
        }
        if(!ok)
            break;
    }

    return ok;
}

function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes == 0) return '0 Byte';
    var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}
