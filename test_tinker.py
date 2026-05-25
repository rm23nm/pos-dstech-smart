import os

php_code = """
$req = Illuminate\Http\Request::create('/billing-new', 'GET');
$req->headers->set('Cookie', 'laravel_session=YOUR_SESSION_ID');
$user = App\Models\User::first();
Auth::login($user);
$req->setRouteResolver(function() use ($req) { return app('router')->getRoutes()->match($req); });
$res = app()->handle($req);
file_put_contents('test_billing.html', $res->getContent());
"""

with open('test.php', 'w') as f:
    f.write(php_code)

os.system('C:\\xampp\\php\\php.exe artisan tinker < test.php')
