<!doctype html>
<html lang="fr">
<head>
@include("layouts.head")
<body>

<div class="app-container app-theme-white body-tabs-shadow fixed-header fixed-sidebar">

    <!-- Header -->
@include("layouts.header")
<!--/Header -->

    <div class="app-main">
        <!-- Sidebar -->
    @include("layouts.sidebar")
    <!--/Sidebar -->

        <!-- Content -->
        <div class="app-main__outer">
            <div class="app-main__inner" style="">
                @yield("content")
            </div>
        </div>
        <!--/Content -->
    </div>
</div>

<div class="app-drawer-overlay d-none animated fadeIn"></div>

<!-- Modal -->
@yield("modal")
<!--/Modal -->

@include("layouts.foot")

</body>
</html>

@yield("sms-content")

