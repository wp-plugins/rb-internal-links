<?php

$linkId = request('linkId');
$linkType = request('linkType');
$linkText = request('linkText');
$linkClass = request('linkClass');
$linkTarget = request('linkTarget');
$linkAnchor = request('linkAnchor');

if(empty($linkId) || empty($linkType))
	$return = array('status' => 'error', 'message' => 'Incorrect link id or type, please start again.');
else
{
	$code = '[intlink';
	$code .= ' id="' . $linkId . '"';
	$code .= ' type="' . $linkType . '"';
	
	$code .= addCodeAttr('class', $linkClass);
	$code .= addCodeAttr('target', $linkTarget);
	$code .= addCodeAttr('anchor', $linkAnchor);

	$code .= ']';
	
	if(!empty($linkText))
	{
		$code .= $linkText;
		$code .= '[/intlink]';
	}
	
	$return = array('status' => 'success', 'code' => $code);
}

die(json_encode($return));


function addCodeAttr($attr, $value)
{
	if(!empty($value))
		return ' ' . $attr . '="' . $value . '"';
}
