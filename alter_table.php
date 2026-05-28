$types = DB::table('itemmaster')->select('TypeItem')->distinct()->get();
foreach($types as $t) { echo $t->TypeItem . "\n"; }
