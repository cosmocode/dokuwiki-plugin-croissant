<?php
/**
 * DokuWiki Plugin croissant (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Adrian Lang <lang@cosmocode.de>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');

require_once DOKU_PLUGIN.'syntax.php';

class syntax_plugin_croissant extends DokuWiki_Syntax_Plugin {
    function getType() {
        return 'substition';
    }

    function getPType() {
        return 'normal';
    }

    function getSort() {
        return 1;
    }

    function connectTo($mode) {
        $this->Lexer->addSpecialPattern('~~bc:.*?~~',$mode,'plugin_croissant');
    }

    function handle($match, $state, $pos, &$handler){
        return trim(substr($match, 5, -2));
    }

    function render($mode, &$renderer, $data) {
        if($mode === 'metadata') {
            $renderer->meta['plugin_croissant_bctitle'] = $data;
        }
        return true;
    }

    /**
     * Greatly cleaned up copy&paste from tpl_youarehere without wiki start page
     *  and with custom titles
     */
    function tpl($sep=' &raquo; ') {
        global $ID;
        global $lang;

        $parts = explode(':', $ID);
        $count = count($parts);

        echo '<span class="bchead">'.$lang['youarehere'].': </span>';

        // always print the startpage
        tpl_pagelink(':'.$conf['start']);

        // print intermediate namespace links
        $part = $page = '';
        for($i=0; $i<$count; $i++){
            $old_page = $page;
            $part .= $parts[$i].':';
            $page = $part;
            resolve_pageid('', $page);
            if ($page !== $old_page) {
                echo $sep;
                tpl_pagelink($page, p_get_metadata($page, 'plugin_croissant_bctitle'));
            }
        }

        return true;
    }
}
