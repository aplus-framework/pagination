<?php namespace Tests\Pagination;

use PHPUnit\Framework\TestCase;

final class LanguagesTest extends TestCase
{
	protected string $langDir = __DIR__ . '/../src/Languages/';

	/**
	 * @return array<int,string>
	 */
	protected function getCodes() : array
	{
		$codes = \array_filter((array) \glob($this->langDir . '*'), 'is_dir');
		$length = \strlen($this->langDir);
		$result = [];
		foreach ($codes as $dir) {
			if ($dir === false) {
				continue;
			}
			$result[] = \substr($dir, $length);
		}
		return $result;
	}

	public function testKeys() : void
	{
		$keys = require $this->langDir . 'en/pagination.php';
		$keys = \array_keys($keys);
		\sort($keys);
		foreach ($this->getCodes() as $code) {
			$lines = require $this->langDir . $code . '/pagination.php';
			$lines = \array_keys($lines);
			\sort($lines);
			self::assertSame($keys, $lines, 'Language: ' . $code);
		}
	}
}
