<?php
class XenForo_Pages_proxyforum
{
    public static function includescript(XenForo_ControllerPublic_Abstract  $controller, XenForo_ControllerResponse_Abstract &$response)
    {
        ob_start();
		require('proxy.php');
		$myContent = ob_get_contents();
        ob_end_clean();
        $params = array(
            'myContent'  => $myContent
        );
        $response->params = array_merge(
            $response->params,
            $params
        );
    }
}
?>
