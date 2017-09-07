<?php
namespace docker {
	function adminer_object() {
		require_once('plugins/plugin.php');

		class Adminer extends \AdminerPlugin {
			function _callParent($function, $args) {
				if ($function === 'loginForm') {
					ob_start();
					$return = \Adminer::loginForm();
					$form = ob_get_clean();

					echo str_replace('name="auth[server]" value="" title="hostname[:port]"', 'name="auth[server]" value="db" title="hostname[:port]"', $form);

					return $return;
				}

				return parent::_callParent($function, $args);
			}

// https://github.com/marcopeg/cakephp-adminer/blob/master/index.php
			public function credentials() {
				// return array($this->db_config->default['host'], $this->db_config->default['login'], $this->db_config->default['password']);
				return array('192.168.130.77', 'keycloak', 'keycloak');
			}

			public function database() {
				// return $this->db_config->default['database'];
				return 'keycloak';
			}
		}

		$plugins = [];
		foreach (glob('plugins-enabled/*.php') as $plugin) {
			$plugins[] = require($plugin);
		}

		return new Adminer($plugins);
	}
}

namespace {
	if (basename($_SERVER['REQUEST_URI']) === 'adminer.css' && is_readable('adminer.css')) {
		header('Content-Type: text/css');
		readfile('adminer.css');
		exit;
	}

	function adminer_object() {
		return \docker\adminer_object();
	}

/*
	if ($_SERVER['QUERY_STRING'] == '') {
		//header("Location: /?server=192.168.130.77&username=keycloak&db=keycloak");
		header("Location: /info.php?server=192.168.130.77&username=keycloak&db=keycloak");
	}
*/
	$_SERVER['QUERY_STRING'] = '';

	require('adminer.php');
}
