<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/


$hook['post_controller_constructor'][] = array(
									'class'    => 'HookClass',
									'function' => 'EnableProfiler',
									'filename' => 'debugger.hook.php',
									'filepath' => 'hooks',
									'params'   => array()
                                );

$hook['display_override'][] = array(
									'class'    => 'HookClass',
									'function' => 'AttechProfilerView',
									'filename' => 'debugger.hook.php',
									'filepath' => 'hooks',
									'params'   => array()
                                );