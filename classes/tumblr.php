<?php
namespace Grav\Plugin;

use Grav\Common\Iterator;
use Grav\Common\Grav;
use Grav\Common\Page\Page;

class Tumblr extends Iterator {

    protected $grav;
    protected $page;
    public $client;

    public function __construct(Grav $grav, $page) {
        require_once __DIR__ . '/../vendor/autoload.php';
        
        /*$consumerKey = $grav['config']->get('plugins.tumblr.settings.consumerKey');
        $consumerSecret = $grav['config']->get('plugins.tumblr.settings.consumerSecret');*/
        //echo '<pre>'; print_r($page->header()->tumblr['settings']['page']); echo '</pre>';
        $consumerKey = $page->header()->tumblr['settings']['consumerKey'];
        $consumerSecret = $page->header()->tumblr['settings']['consumerSecret'];
          
        $this->client  = new \Tumblr\API\Client($consumerKey, $consumerSecret);
        
        /* ----
        this comes from the tumblr indications it probably only needs to be managed to make modifications to content
        //$this->client ->setToken($token, $tokenSecret);
        ---- */
        

    }
}
