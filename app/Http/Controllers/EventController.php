<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
class EventController extends Controller
{
	public function event()
	{
		echo $_GET['echostr'];
	}
}