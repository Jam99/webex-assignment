<div class="p-4 pb-1 shadow row">
    <nav class="col">
        <h1 class="fs-5 d-inline-block mb-0 text-nowrap">
            <i class="fa-solid fa-user d-inline-block d-sm-none"></i>
            <span class="d-none d-sm-inline-block">You are now managing the gallery as</span>
            {{ \Illuminate\Support\Facades\Auth::getUser()->name }}
        </h1>
        <a class="text-decoration-none d-block" href="/">
            <i class="fa-solid fa-house"></i>
            <span class="d-none d-sm-inline-block">Back to main site.</span>
        </a>
    </nav>
    <div class="col text-end">
        <a href="/admin/logout" class="link-danger fs-6 text-decoration-none">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span class="d-none d-sm-inline-block">Logout</span>
        </a>
    </div>
</div>
