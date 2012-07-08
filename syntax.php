<?php
/**
 * Replace regular images with lightbox pop-ups
 *
 * @license CC 2.5
 * @author Dustin Butler <dustin@intrcomm.net>
 */

if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_lightbox extends DokuWiki_Syntax_Plugin {
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
        'url'    => 'http://www.roundporch.com/wiki/doku.php?id=dokuwiki:lightbox',
      );
    }

    /**
     * What kind of syntax are we?
     */
    function getType(){
      return 'substition';
    }

    /**
     * What about paragraphs? (optional)
     */
     function getPType(){
      return 'block';
     }

    /**
     * Where to sort in?
     */
    function getSort(){
        return 300;
    }

    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
      $this->Lexer->addSpecialPattern('{{[^{}]*\.[pjg][npi][gf][^{}]*}}', $mode, 'plugin_lightbox');
    }

    /**
     * Handle the match
     */
    function handle($match, $state, $pos, &$handler){

      if ($state != DOKU_LEXER_SPECIAL) return array();
      // $match will equal our full matching pattern, need to pull the image and size out
      if (preg_match('/{{(.*\.[pjg][npi][gf])\??([0-9]*)x?([0-9]*)\|?(.*)}}/', html_entity_decode($match), $parts)) {
        if (! $parts[2]) {
          $parts[2] = 150;
          $parts[3] = 150;
        }
        return $parts;
      }
      return array();
		  /*
      switch ($state) {
        case DOKU_LEXER_ENTER :
          break;
        case DOKU_LEXER_MATCHED :
          break;
        case DOKU_LEXER_UNMATCHED :
          break;
        case DOKU_LEXER_EXIT :
          break;
        case DOKU_LEXER_SPECIAL :
          break;
      }
		  */

    }

    /**
     * Create output
     */
    function render($mode, &$renderer, $data) {
      if ($mode == 'xhtml' or $mode == 'htmldoc') {
        list($all, $src, $width, $height, $title) = $data;

        //$renderer->doc .= "<!-- $src -->";

        if (! preg_match('/^https?:/', $src)) {
          $src = "/wiki/lib/exe/fetch.php?media=$src";
        }

        // Sized display
        $renderer->doc .= "<a href=\"$src\" rel=\"lightbox\"><img src=\"$src\" class=\"media\" width=$width height=$height alt=\"$title\" title=\"$title\" /></a>";

        //$renderer->doc .= "<div style=\"margin: 3px;border: 1px solid grey;display: inline-block; overflow: hidden; width: ${width}px; height: ${height}px\"><a href=\"$src\" rel=\"lightbox\"><img src=\"$src\" class=\"media\" alt=\"$title\" title=\"$title\" /></a></div>";
        return true;
      }
      return false;
    }
}