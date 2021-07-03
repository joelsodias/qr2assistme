<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\BaseConnection;
use CodeIgniter\Database\Seeder;
use Exception;
use Ramsey\Uuid\Nonstandard\UuidV6;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Provider\Node\RandomNodeProvider;
use Ramsey\Uuid\Codec\OrderedTimeCodec;
use Ramsey\Uuid\UuidFactory;
use Config\Database;




class BaseSeeder extends Seeder
{
	protected $_faker;


	/**
	 * Seeder constructor.
	 *
	 * @param Database            $config
	 * @param BaseConnection|null $db
	 */
	public function __construct(Database $config, BaseConnection $db = null)
	{
		parent::__construct($config, $db);
	}

	protected function getFaker($locale = null)
	{
		if (!isset($_faker)) {
			$_faker = \Faker\Factory::create($locale);
		} 

		return $_faker;
	}

	protected function getNewUUid()
	{
		$nodeProvider = new RandomNodeProvider();
		return UuidV6::uuid6($nodeProvider->getNode());
	}

	protected function getNewUUidString()
	{
		return (string) $this->getNewUUid();
	}

	function getRandomString($length = 10, string $alphabet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
	{
		$charactersLength = strlen($alphabet);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $alphabet[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	function getRandomPassword($length = 10, string $alphabet = "~`!@#$%^&*()_-+={[}]|\\:;\"'<,>.?/|0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ")
	{
		$charactersLength = strlen($alphabet);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $alphabet[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	protected function getRandomItem(array $items = [false])
	{
		return $items[array_rand($items, 1)];
	}

	protected function getRandomDateString(string $start_date, string $end_date, string $format = 'Y-m-d H:i:s')
	{
		// Convert to timetamps
		$min = strtotime($start_date);
		$max = strtotime($end_date);

		// Generate random number using above bounds
		$val = mt_rand($min, $max);

		// Convert back to desired date format
		return date($format, $val);
	}

	protected function getRandomTimestamp(string $start_date, string $end_date, string $format = 'Y-m-d H:i:s')
	{
		// Convert to timetamps
		$min = strtotime($start_date);
		$max = strtotime($end_date);

		// Generate random number using above bounds
		$val = mt_rand($min, $max);

		// Convert back to desired date format
		return $val;
	}

	function getRandomDate(\DateTime $start, \DateTime $end)
	{
		$randomTimestamp = mt_rand($start->getTimestamp(), $end->getTimestamp());
		$randomDate = new \DateTime();
		$randomDate->setTimestamp($randomTimestamp);
		return $randomDate;
	}


	function normalizeString($string)
	{
		$a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
		$b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
		$string = utf8_decode($string);
		$string = strtr($string, utf8_decode($a), $b);
		$string = strtolower($string);
		return utf8_encode($string);
	}
}
