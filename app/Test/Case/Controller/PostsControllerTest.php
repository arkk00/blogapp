<?php
App::uses('PostsController', 'Controller');
App::uses('Fabricate', 'Fabricate.Lib');

/**
 * PostsController Test Case
 *
 */
class PostsControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = [
		'app.post'
	];

	public function setUp() {
		parent::setUp();
		$this->controller = $this->generate('Posts', [
			'components' => ['Paginator', 'Session', 'Auth'],
			'models' => ['Post' => ['save']],
			'methods' => ['redirect']
		]);
		$this->controller->autoRender = false;
	}

	public function testIndexアクションではページングの結果がpostsにセットされること() {
	    $data = [
	        ['Posts' => ['id' => 1, 'title' => 'Title1', 'body' => 'Body1']],
	    ];
	    $this->controller->Paginator->expects($this->once()) // (8.1)
	        ->method('paginate')->will($this->returnValue($data)); // (8.2)
	    $vars = $this->testAction('/user/blog', ['method' => 'get', 'return' => 'vars']); // (9)
	    $this->assertEquals($data, $vars['posts']);
	}

	public function testAddアクションで保存が失敗したときメッセージがセットされること() {
	    $this->controller->Post->expects($this->once())
	        ->method('save')->will($this->returnValue(false)); // (10)
	    $this->controller->Session->expects($this->once())
	        ->method('setFlash')->with($this->equalTo('記事の投稿に失敗しました。入力内容を確認して再度投稿してください。')); // (11)
	    $this->testAction('/blogs/new', ['method' => 'post', 'data' => ['title' => 'Title1', 'body' => 'Body1']]);
	}

	public function testAddアクションで保存が成功したときはメッセージがセットされ一覧表示にリダイレクトされること() {
	    $this->controller->Post->expects($this->once())
	        ->method('save')->will($this->returnValue(true));
	    $this->controller->Session->expects($this->once())
	        ->method('setFlash')->with($this->equalTo('新しい記事を受け付けました。'));
	    $this->controller->expects($this->once())
	        ->method('redirect')->with($this->equalTo(['action' => 'index']));// (12)
	    $this->testAction('/blogs/new', ['method' => 'post', 'data' => ['title' => 'Title1', 'body' => 'Body1']]);
	}

}
