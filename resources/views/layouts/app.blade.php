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
                @yield("content")
                <!--/Content -->
            </div>
        </div>

        <div class="app-drawer-overlay d-none animated fadeIn"></div>

        @include("layouts.foot")

    </body>
</html>

@yield("sms-content")

