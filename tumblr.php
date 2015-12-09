<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use Grav\Common\Page\Page;
use Grav\Common\Grav;
use RocketTheme\Toolbox\Event\Event;
use Tracy\Debugger;

class TumblrPlugin extends Plugin
{
    protected $active = false;
    protected $tumblr;

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
            'onGetPageTemplates' => ['onGetPageTemplates', 0]
        ];
    }

    /**
     * Initialize configuration
     */
    public function onPluginsInitialized()
    {
        if ($this->isAdmin()) {
            $this->active = false;
            return;
        }

        $this->enable([
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onPageInitialized' => ['onPageInitialized', 0]
        ]);
    }

    /**
     * Add current directory to twig lookup paths.
     */
    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = __DIR__ . '/templates';
    }
    
    
    /**
     * Add page template types.
     */
    public function onGetPageTemplates(Event $event)
    {
        /** @var Types $types */
        $types = $event->types;
        $types->scanTemplates('plugins://tumblr/templates');
    }

    /**
     * Initialize tumblr when detected in a page.
     */
    public function onPageInitialized()
    {
        $page = $this->grav['page'];
        $grav = $this->grav;
        
        $defaults = (array) $this->config->get('plugins.tumblr');
        
        
        if (!$page) {
            return;
        }
        
    

        if (isset($page->header()->tumblr)) {
            $page->header()->tumblr = array_merge($defaults, $page->header()->tumblr);
            
            $this->active = true;

            // Initialize tumblr API Class
            require_once __DIR__ . '/classes/tumblr.php';
            $this->tumblr = new tumblr($grav, $page);

            $this->enable([
                'onTwigSiteVariables' => ['onTwigSiteVariables', 0]
            ]);

        }
    }




    /**
     * Make form accessible from twig.
     */
    public function onTwigSiteVariables()
    {
        // in Twig template: {{ tumblr.client.getBlogPosts(page.header.tumblr_page).posts }}
        $this->grav['twig']->twig_vars['tumblr'] = $this->tumblr;
    }
}
