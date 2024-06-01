<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
    <tr>
        <td>&nbsp;</td>
        <td class="container">
            <div class="content">
                <span class="preheader">{{ isset($title) ? $title : config('app.name') }}</span>
                <table role="presentation" class="main">
                    <tr>
                        <td class="wrapper">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="wrapper">
                                        <h3 style="text-align: center; color: #41b882;margin-bottom: 10px;">{{ isset($title) ? $title : config('app.name') }}</h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {{ $slot }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <div class="footer">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="content-block powered-by">
                                Powered by <a href="{{ url('/') }}">{{ config('app.name') }}</a>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </td>
        <td>&nbsp;</td>
    </tr>
</table>
