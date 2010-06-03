<?php
class Lcsh_IndexController extends Omeka_Controller_Action
{
    const LCSH_SUGGEST_URL = 'http://id.loc.gov/authorities/suggest/';
    
    public function lcshProxyAction()
    {
        $client = new Zend_Http_Client();
        $client->setUri(self::LCSH_SUGGEST_URL);
        $client->setParameterGet('q', $this->getRequest()->getParam('q'));
        $json = json_decode($client->request()->getBody());
        header('X-JSON: (' . json_encode($json) . ')');
        exit;
    }
}