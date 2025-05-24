<?php
function format_price($price) {
    if ((int)$price === 0) {
        return 'Free';
    }
    return number_format($price, 0, ',', '.') . ' VNĐ';
}
