<!doctype html>
<html>
@include('email.layouts.head')
<body style="background-color: #eaebed; font-family: sans-serif;">
<table class="body" width="100%" bgcolor="#eaebed">
    <tr>
        <td>&nbsp;</td>
        <td class="container" width="580" style="margin: 0 auto; max-width: 580px;">
            @include('email.layouts.header')
            <div class="content" style="padding: 10px;">
                <table class="main" width="100%" bgcolor="#ffffff" style="border-radius: 3px;">
                    <tr>
                        <td class="wrapper" style="padding: 20px;">
                            @yield('content')
                        </td>
                    </tr>
                </table>
            </div>
            @include('email.layouts.footer')
        </td>
        <td>&nbsp;</td>
    </tr>
</table>
</body>
</html>
