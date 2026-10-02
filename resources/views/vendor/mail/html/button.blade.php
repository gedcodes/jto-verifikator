<table class="action" style="text-align: center;" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td style="text-align: center;">
            <table width="100%" style="border: none;" cellpadding="0" cellspacing="0" role="presentation">
                <tr>
                    <td style="text-align: center;">
                        <table style="border: none;" cellpadding="0" cellspacing="0" role="presentation">
                            <tr>
                                <td>
                                    <a href="{{ $url }}" class="button button-{{ $color ?? 'primary' }}"
                                        target="_blank" rel="noopener">{{ $slot }}</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
