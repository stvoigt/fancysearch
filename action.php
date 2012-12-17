<?php
if(!defined('DOKU_INC')) die();

class action_plugin_fancysearch extends DokuWiki_Action_Plugin {

    /**
     * Register its handlers with the DokuWiki's event controller
     */
    function register(Doku_Event_Handler &$controller) {
        $controller->register_hook('DOKUWIKI_STARTED', 'AFTER',  $this, '_fixquery');
    }

    /**
     * Put namespace into search
     */
    function _fixquery(Doku_Event &$event, $param) {
        global $QUERY;
        global $ACT;

        if($ACT != 'search'){
            $QUERY = '';
            return;
        }

        if(trim($_REQUEST['namespace'])){
            $QUERY .= ' @'.trim($_REQUEST['namespace']);
        }
    }

    function tpl_searchform($namespaces) {
        global $QUERY;
        $cur_val = isset($_REQUEST['namespace']) ? $_REQUEST['namespace'] : '';

        echo '<form method="post" action="" accept-charset="utf-8">';
        echo '<select class="fancysearch_namespace" name="namespace">';
        foreach ($namespaces as $ns => $class){
            echo '<option value="'.hsc($ns).'"'.($cur_val === $ns ? ' selected="selected"' : '').'>'.hsc($ns).'</option>';
        }
        echo '</select>';
        echo '<div id="fancysearch__ns_custom" class="closed" style="display: none;">';
        echo '<ul>';
        foreach ($namespaces as $ns => $class) {
            echo '<li class="fancysearch_ns_'.$this->css_escape($class).'">'.hsc($ns).'</li>';
        }
        echo '</ul>';
        echo '</div>';

        echo '<input type="hidden" name="do" value="search" />';
        echo '<input type="hidden" id="qsearch__in"/>';
        echo '<input class="query" id="fancysearch__input" type="text" name="id" autocomplete="off" value="'.hsc(preg_replace('/ ?@\S+/','',$QUERY)).'" accesskey="f" />';
        echo '<input class="submit" type="submit" name="submit" value="Search" />';
        echo '</form>';
        echo '<div id="qsearch__out" class="ajax_qsearch JSpopup"></div>';
    }

    function css_escape($s) {
        return hsc(str_replace(':', '_', $s));
    }
}
