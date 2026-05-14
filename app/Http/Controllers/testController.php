<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
class TestController extends Controller {
    public function checkColumns() {
        $columns = DB::select("DESCRIBE tableorderheader");
        return response()->json($columns);
    }
}
