<?php
/**
 * LetterTest.php
 * @author Roman Revin
 * @link http://phptime.ru
 */

namespace rmrevin\yii\postman\tests\unit\postman;

use rmrevin\yii\postman\RawLetter;
use rmrevin\yii\postman\tests\unit\TestCase;

class LetterTest extends TestCase
{

	public function testAddRecipients()
	{
		$Letter = new RawLetter('Subject', 'Text');

		$Letter->add_address(
			['test1@email.com', 'Name Test 1'],
			['test2@email.com', 'Name Test 2'],
			['test3@email.com', 'Name Test 3']
		);
		$Letter->add_cc_address(
			['cc1@email.com', 'Name Cc 1'],
			['cc2@email.com', 'Name Cc 2'],
			['cc3@email.com', 'Name Cc 3']
		);
		$Letter->add_bcc_address(
			['bcc1@email.com', 'Name Bcc 1'],
			['bcc2@email.com', 'Name Bcc 2'],
			['bcc3@email.com', 'Name Bcc 3']
		);
		$Letter->add_reply_to(
			['reply1@email.com', 'Name Reply 1'],
			['reply2@email.com', 'Name Reply 2'],
			['reply3@email.com', 'Name Reply 3']
		);

		$this->assertNotEmpty($Letter->get_recipients());

		$this->assertEquals(13, $Letter->get_count_recipients());

		$Letter->add_address_list(
			[['test1@email.com'], ['test2@email.com'], ['test3@email.com']],
			[['cc1@email.com'], ['cc2@email.com'], ['cc3@email.com']],
			[['bcc1@email.com'], ['bcc2@email.com'], ['bcc3@email.com']],
			[['reply1@email.com'], ['reply2@email.com'], ['reply3@email.com']]
		);

		$this->assertEquals(25, $Letter->get_count_recipients());
	}

	public function testAddAttachments()
	{
		$Letter = new RawLetter('Subject', 'Text');

		$test_file_name = 'phptime.ru.png';
		$Letter->add_attachment(realpath(__DIR__ . '/../data/' . $test_file_name), $test_file_name);

		$attachments = $Letter->get_attachments();

		$this->assertEquals(substr($attachments[0]['path'], -31), '/tests/unit/data/' . $test_file_name);
		$this->assertEquals($attachments[0]['name'], $test_file_name);
		$this->assertEquals($attachments[0]['encoding'], 'base64');
		$this->assertEquals($attachments[0]['type'], 'application/octet-stream');
	}

	/**
	 * @expectedException \rmrevin\yii\postman\LetterException
	 */
	public function testPostmanNotSet()
	{
		\Yii::$app->setComponent('postman', null);
		new RawLetter('', '');
	}
}