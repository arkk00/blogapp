<?php
App::uses('Post', 'Model');
App::uses('Fabricate', 'Fabricate.Lib');

class PostTest extends CakeTestCase {

	public $fixtures = array(
		'app.post'
	);

	public function setUp() {
		parent::setUp();
		$this->Post = ClassRegistry::init('Post');
	}

	public function tearDown() {
		unset($this->Post);

		parent::tearDown();
	}

        /** 
         * @dataProvider exampleValidationErrors
         */
	public function testバリデーションエラー($column, $value, $message) {
	    $post = Fabricate::build('Post', [$column => $value]);
	    $this->assertFalse($post->validates());
	    $this->assertEquals([$message], $this->Post->validationErrors[$column]); // (C)
	}

	public function exampleValidationErrors() {
		return [
			['title', '', 'タイトルは必須入力です'],
			['title', str_pad('', 256, "a"), 'タイトルは255文字以内で入力してください'],
		];
	}
}
