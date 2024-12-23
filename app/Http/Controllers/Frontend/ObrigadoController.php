<?php 
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
class ObrigadoController extends Controller {
    public function index() {
        return view('frontend.obrigado');
    }
}
