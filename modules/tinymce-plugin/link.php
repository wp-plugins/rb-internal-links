<?php

$linkId = Rb_Internal_Links::requestVar('linkId');
$linkType = Rb_Internal_Links::requestVar('linkType');
$linkText = Rb_Internal_Links::requestVar('linkText');
$linkClass = Rb_Internal_Links::requestVar('linkClass');
$linkTarget = Rb_Internal_Links::requestVar('linkTarget');
$linkAnchor = Rb_Internal_Links::requestVar('linkAnchor');

if (empty($linkId) || empty($linkType))
    $return = array('status' => 'error', 'message' => __('Incorrect link id or type, please try again.'));
else {
    $code = '[intlink';
    $code .= ' id="' . $linkId . '"';
    $code .= ' type="' . $linkType . '"';

    $code .= addCodeAttr('class', $linkClass);
    $code .= addCodeAttr('target', $linkTarget);
    $code .= addCodeAttr('anchor', $linkAnchor);

    $code .= ']';

    $code .= empty($linkText) ? '{{empty}}' : $linkText;
    $code .= '[/intlink]';

    $return = array('status' => 'success', 'code' => $code);
}

die(json_encode($return));

function addCodeAttr($attr, $value) {
    if (!empty($value))
        return ' ' . $attr . '="' . $value . '"';
}
