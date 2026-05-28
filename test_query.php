<?php
echo json_encode(DB::select('SHOW COLUMNS FROM permission'));
