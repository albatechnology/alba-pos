<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const STATUS_ERROR = false;
	const STATUS_SUCCESS = true;

	const KEY_STATUS = 'success';
	const KEY_MESSAGE = 'message';
	const KEY_DATA = 'data';

	public static function ajaxError($message, MessageBag $error_message_bag = null)
	{
		if ($error_message_bag) {
			$message = collect($error_message_bag)->collapse()->implode("\n");
		}

		$data = [self::KEY_STATUS => self::STATUS_ERROR, self::KEY_MESSAGE => $message];

		return response()->json($data);
	}

	public static function ajaxSuccess($message = null, $datas= null)
	{
		$data = [self::KEY_STATUS => self::STATUS_SUCCESS];

		if ($message) {
			$data[self::KEY_MESSAGE] = $message;
		}

		if ($datas) {
			$data[self::KEY_DATA] = $datas;
		}

		return response()->json($data);
	}

    public function flash_data($type, $msg, $icon = null)
    {
        if ($icon == null) {
            switch ($type) {
                case 'success':
                    $icon = "fa fa-check";
                    $alert = "alert-success";
                    break;
                case 'error':
                    $icon = "fa fa-ban";
                    $alert = "alert-danger";
                    break;
                case 'warning':
                    $icon = "fa fa-warning";
                    $alert = "alert-warning";
                    break;
                case 'info':
                    $icon = "fa fa-info";
                    $alert = "alert-info";
                    break;
                default:
                    $icon = "fa fa-ban";
                    $alert = "alert-danger";
                    break;
            }
        }
        // return '<div class="alert ' . $alert . ' alert-dismissable"><button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button><i class="icon ' . $icon . '"></i> ' . $msg . '</div>';
        return '<div class="alert ' . $alert . ' alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  ' . $msg . '
                </div>';
    }
}
