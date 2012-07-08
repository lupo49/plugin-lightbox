<?php
/**
 * Task Plugin: Create a list of tasks and track if they are
 *              completed.  I used
 *
 * Action plugin component, for cache validity determination
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Christopher Smith <chris@jalakai.co.uk>
 */
if(!defined('DOKU_INC')) die();  // no Dokuwiki, no go

if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'action.php');

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class action_plugin_lightbox extends DokuWiki_Action_Plugin {

    /**
     * return some info
     */
    function getInfo(){
      return array(
        'author' => 'Dustin Butler',
        'email'  => 'dustin@intrcomm.net',
        'date'   => '2010-11-17',
        'name'   => 'Lightbox For Images',
        'desc'   => 'Pop-up Image Using Lightbox JS',
        'url'    => 'http://www.roundporch.com/wiki?docuwiki:lightbox',
      );
    }

    /**
     * plugin should use this method to register its handlers with the dokuwiki's event controller
     */
    function register(&$controller) {
      $controller->register_hook('PARSER_CACHE_USE','BEFORE', $this, '_cache_prepare');
    }

    /**
     * prepare the cache object for default _useCache action
     */
    function _cache_prepare(&$event, $param) {
      $cache =& $event->data;

      // we're only interested in wiki pages and supported render modes
      if (!isset($cache->page)) return;
      //if (!isset($cache->mode) || in_array($cache->mode,array('i','metadata'))) return;

      // = $this->_cache_maxage($cache->page);
      //if (is_null($max_age)) return;

      //if ($max_age <= 0) {
        // expire the cache
        $event->preventDefault();
        $event->stopPropagation();
        $event->result = false;
        return;
      //}

      //$cache->depends['age'] = !empty($cache->depends['age']) ?  min($cache->depends['age'],$max_age): $max_age;
    }

    /**
     * determine the max allowable age of the cache
     *
     * @param   string    $id         wiki page name
     *
     * @return  int                   max allowable age of the cache
     *                                null means not applicable
     */
     /*
    function _cache_maxage($id) {
      $hasPart = p_get_metadata($id, 'relation haspart');
      if (empty($hasPart) || !is_array($hasPart)) return null;

      $location = $this->getConf('location');

      $age = 0;
      foreach ($hasPart as $file => $data) {
        // ensure the metadata entry was created by or for this plugin
        if (empty($data['owner']) || $data['owner'] != $this->getPluginName()) continue;

        $file = $this->getConf['location'].$file;

        // determine max allowable age for the cache
        // if filemtime can't be determined, either for a non-existent $file or for a $file using
        // an unsupported scheme, expire the cache immediately/always
        $mtime = @filemtime($file);
        if (!$mtime) { return 0; }

        $age = max($age,$mtime);
      }

      return $age ? time()-$age : null;
    }
    */

}