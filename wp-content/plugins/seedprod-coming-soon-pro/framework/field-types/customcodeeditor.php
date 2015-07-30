<?php
// {$setting_id}[$id] - Contains the setting id, this is what it will be stored in the db as.
// $class - optional class value
// $id - setting id
// $options[$id] value from the db

echo "<textarea id='$id' class='large-text' name='{$setting_id}[$id]' style='height:710px;'>" . $options[ $id ] . "</textarea><br>";

// wp_enqueue_script( 'seed_csp3-codemirror-js', SEED_CSP3_PLUGIN_URL . 'framework/field-types/js/codemirror/lib/codemirror.js' );
// wp_enqueue_script( 'seed_csp3-codemirror-js-mode', SEED_CSP3_PLUGIN_URL . 'framework/field-types/js/codemirror/mode/xml/xml.js' );
// wp_enqueue_script( 'seed_csp3-codemirror-js-mode', SEED_CSP3_PLUGIN_URL . 'framework/field-types/js/codemirror/mode/css/css.js' );
// wp_enqueue_script( 'seed_csp3-codemirror-js-mode', SEED_CSP3_PLUGIN_URL . 'framework/field-types/js/codemirror/mode/javascript/javascript.js' );
// wp_enqueue_script( 'seed_csp3-codemirror-js-mode', SEED_CSP3_PLUGIN_URL . 'framework/field-types/js/codemirror/mode/htmlmixed/htmlmixed.js' );
// wp_register_style( 'seed_csp3-codemirror-css', SEED_CSP3_PLUGIN_URL . 'framework/field-types/js/codemirror/lib/codemirror.css');
// wp_enqueue_style( 'seed_csp3-codemirror-css' );

// echo "<script>
// 		jQuery(document).ready(function($){
//         var seed_csp3_CodeMirror = CodeMirror.fromTextArea(document.getElementById('template'), {mode: 'text/html',lineNumbers: true,enterMode: 'keep'});
//     	});
//       </script>
//       ";
