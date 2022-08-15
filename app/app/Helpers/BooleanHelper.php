<?php


namespace App\Helpers;


use Illuminate\Support\HtmlString;

class BooleanHelper
{
    public static function toHtml($val): HtmlString
    {
        if ($val) {
            return new HtmlString('<i class="fa fa-check" aria-hidden="true" style="color:lawngreen"></i>');
        }
        return new HtmlString('<i class="fa fa-minus" aria-hidden="true" style="color:red"></i>');
    }
}
