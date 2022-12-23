@include("admin.head")
<div class="mx-auto mw-600 mt-100 p-3">
    <h1 class="text-center">Admin login</h1>
    <form id="admin_login_form" class="mw-400 mx-auto">
        @csrf
        <div class="form-floating mb-3">
            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
            <label for="email">Email address</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
            <label for="password">Password</label>
        </div>
        <div id="login_feedback" class="text-danger text-center mt-3"></div>
        <button type="submit" class="btn btn-primary mt-3 mx-auto d-block px-4">Login</button>
    </form>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            addValidation("admin_login_form", {
                email: {
                    valid_email: ["Invalid email."],
                    required: ["Required field."]
                },
                password: {
                    required: ["Required field."]
                }
            })
        })
    </script>
</div>
@include("admin.footer")
