<?php namespace Tests\Pagination;

use PHPUnit\Framework\TestCase;

class LanguagesTest extends TestCase
{
	protected string $langDir = __DIR__ . '/../src/Languages/';

	protected function getCodes()
	{
		$codes = \array_filter(\glob($this->langDir . '*'), 'is_dir');
		$length = \strlen($this->langDir);
		foreach ($codes as &$dir) {
			$dir = \substr($dir, $length);
		}
		return $codes;
	}

	public function testKeys()
	{
		$keys = require $this->langDir . 'en/pagination.php';
		$keys = \array_keys($keys);
		\sort($keys);
		foreach ($this->getCodes() as $code) {
			$lines = require $this->langDir . $code . '/pagination.php';
			$lines = \array_keys($lines);
			\sort($lines);
			$this->assertEquals($keys, $lines, 'Language: ' . $code);
		}
	}
}
