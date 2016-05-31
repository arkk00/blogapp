<?php
App::uses('UsersController', 'Users.Controller');
class AppUsersController extends UsersController {

	public function beforeFilter() {

		// p330に記載されていないが、テスト実行時にプラグインコントローラの
		// メール設定"UsersController::_setDefaultEmail()"でエラーが発生する
		// ドキュメントを参考にして、以下のどちらかを設定する
		// 1) モデルクラス(AppUser.php)を作成、app/Config/email.phpを作成
		// 2) 'App.defaultEmail'に任意のメールアドレスを書き込む
		// 当ファイルでは、2)で設定する
		// 以下、ドキュメントURL
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
