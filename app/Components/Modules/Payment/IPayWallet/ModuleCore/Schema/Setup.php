<?php 
	namespace App\Components\Modules\Payment\B2BPay\ModuleCore\Schema;

	use Illuminate\Database\Schema\Blueprint;
	use Illuminate\Support\Facades\Schema;
	use App\Eloquents\User;

	/**
	 * Class Setup
	 * @package App\Components\Modules\Payment\B2BPay\ModuleCore\Schema
	 */

	class Setup
	{
		static function install()
		{
			$users = User::where(['status'=>false])->get();
			foreach ($users as $user) {
				app()->call([$this,'createuser'],['user'=>$user]);
			}
		}
	}
?>