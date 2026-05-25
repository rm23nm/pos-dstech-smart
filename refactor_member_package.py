import re

with open("app/Http/Controllers/MemberPackageController.php", "r") as f:
    content = f.read()

# Add tkelompoklampu to index
old_index = """    public function index()
    {
        $user = Auth::user();
        $packages = MemberPackage::where('RecordOwnerID', $user->RecordOwnerID)->get();
        return view('master.memberpackage.index', compact('packages'));
    }"""
new_index = """    public function index()
    {
        $user = Auth::user();
        $packages = MemberPackage::where('RecordOwnerID', $user->RecordOwnerID)->get();
        $kelompokLampu = DB::table('tkelompoklampu')->where('RecordOwnerID', $user->RecordOwnerID)->get();
        return view('master.memberpackage.index', compact('packages', 'kelompokLampu'));
    }"""
content = content.replace(old_index, new_index)

# Add KelompokLampu to store
old_store = """            $package->KategoriPaket = $request->input('KategoriPaket', 'HIBURAN');
            $package->ValidDays = $request->input('ValidDays', 30);"""
new_store = """            $package->KategoriPaket = $request->input('KategoriPaket', 'HIBURAN');
            $package->KelompokLampu = $request->input('KelompokLampu');
            $package->ValidDays = $request->input('ValidDays', 30);"""
content = content.replace(old_store, new_store)

# Add KelompokLampu to update
old_update = """            $package->KategoriPaket = $request->input('KategoriPaket', 'HIBURAN');
            $package->ValidDays = $request->input('ValidDays', 30);"""
new_update = """            $package->KategoriPaket = $request->input('KategoriPaket', 'HIBURAN');
            $package->KelompokLampu = $request->input('KelompokLampu');
            $package->ValidDays = $request->input('ValidDays', 30);"""
content = content.replace(old_update, new_update)

with open("app/Http/Controllers/MemberPackageController.php", "w") as f:
    f.write(content)

print("MemberPackageController updated!")
