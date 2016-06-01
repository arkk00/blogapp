<?php
App::uses('UsersController', 'Users.Controller');
class AppUsersController extends UsersController {

	public function beforeFilter() {

		// p330�ɋL�ڂ���Ă��Ȃ����A�e�X�g���s���Ƀv���O�C���R���g���[����
		// ���[���ݒ�"UsersController::_setDefaultEmail()"�ŃG���[����������
		// �h�L�������g���Q�l�ɂ��āA�ȉ��̂ǂ��炩��ݒ肷��
		// 1) ���f���N���X(AppUser.php)���쐬�Aapp/Config/email.php���쐬
		// 2) 'App.defaultEmail'�ɔC�ӂ̃��[���A�h���X����������
		// ���t�@�C���ł́A2)�Őݒ肷��
		// �ȉ��A�h�L�������gURL
		// https://github.com/CakeDC/users/blob/2.x/Docs/Documentation/Configuration.md
		// https://github.com/CakeDC/users/blob/2.x/Docs/Documentation/Extending-the-Plugin.md
		Configure::write('App.defaultEmail','noreply@example.com');

		parent::beforeFilter();
		if (Configure::read('app.disableValidatePost') === true) {
			$this->Security->validatePost = false;
		}
	}

	public function render($view = null, $layout = null) {
		if (is_null($view)) {
			$view = $this->action;
		}
		$viewPath = substr(get_class($this), 0, strlen(get_class($this)) - 10);
		if (!file_exists(APP . 'View' . DS . $viewPath . DS . $view . '.ctp')) {
			$this->plugin = 'Users';
		} else {
			$this->viewPath = $viewPath;
		}
		return parent::render($view, $layout);
	}
}
