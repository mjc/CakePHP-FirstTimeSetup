<?php


App::uses("Security","Utility");

class FirstTimeSetup {
	private static $default_salt = 'DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi';
	private static $default_cipherseed = '76859309657453542496749683645';
	private static $force_setup = false;
	private static $patterns = array(
		"salt" => "/(Configure::write\('Security.salt',\s+?').+('\);)/x",
		"cipherseed" => "/(Configure::write\('Security.cipherSeed',\s+?').+('\);)/x"
	);

	public static function should_run() {
		return self::$force_setup || (Configure::read('debug') == 0 && self::is_crypto_default());
	}

	public static function modify_core() {
		$patterns = self::$patterns;
		$replacements = self::replacements();
		return file_put_contents(self::core_path(),preg_replace($patterns,$replacements,file_get_contents(self::core_path())));
	}

	private static function replacements() {
		return array(
			"salt" => '${1}'.self::new_salt().'${2}',
			"cipherseed" => '${1}'.self::new_cipherseed().'${2}'
		);
	}

	private static function core_path() {
		return APP .  'Config' . DS . 'core.php';
	}

	private static function check_default_salt() {
		return Configure::read('Security.salt') == self::$default_salt;
	}

	private static function check_default_cipherseed() {
		Configure::read('Security.default_cipherseed' == self::$default_cipherseed);
	}

	private static function is_crypto_default() {
		return self::check_default_salt() || self::check_default_cipherseed();
	}

	private static function new_salt() {
		return bin2hex(openssl_random_pseudo_bytes(20));
	}

	private static function new_cipherseed() {
		return hexdec(bin2hex(openssl_random_pseudo_bytes(6))).hexdec(bin2hex(openssl_random_pseudo_bytes(6)));
	}
}

if (FirstTimeSetup::should_run()) FirstTimeSetup::modify_core();
