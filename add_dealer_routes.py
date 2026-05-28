import re

file_path = r'd:\OneDrive\My Project Aplikasi\pos.dstechsmart.com\routes\web.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Append Dealer Routes at the very end of the file, assuming there is a main auth group or just globally (since we can apply middleware manually if needed, but it's better to put it inside the auth group).
# Wait, actually let's just append it to the end of the file wrapped in its own auth group! That is much safer and easier.

dealer_routes = """

// DEALER POS ROUTES (ISOLATED)
Route::group(['middleware' => ['auth', 'check.session'], 'prefix' => 'dealer', 'as' => 'dealer.'], function () {
    // Inventory Kendaraan (Inbound)
    Route::get('/inventory', [\App\Http\Controllers\DealerInventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/create', [\App\Http\Controllers\DealerInventoryController::class, 'create'])->name('inventory.create');
    Route::post('/inventory/store', [\App\Http\Controllers\DealerInventoryController::class, 'store'])->name('inventory.store');
    Route::get('/inventory/{id}/edit', [\App\Http\Controllers\DealerInventoryController::class, 'edit'])->name('inventory.edit');
    Route::put('/inventory/{id}', [\App\Http\Controllers\DealerInventoryController::class, 'update'])->name('inventory.update');
    Route::post('/inventory/delete', [\App\Http\Controllers\DealerInventoryController::class, 'destroy'])->name('inventory.destroy');
    
    // POS Penjualan Kendaraan (Sales)
    Route::get('/pos', [\App\Http\Controllers\DealerPOSController::class, 'index'])->name('pos.index');
    Route::post('/pos/store', [\App\Http\Controllers\DealerPOSController::class, 'store'])->name('pos.store');
    Route::get('/pos/{id}/invoice', [\App\Http\Controllers\DealerPOSController::class, 'invoice'])->name('pos.invoice');
    Route::get('/pos/{id}/bstb', [\App\Http\Controllers\DealerPOSController::class, 'bstb'])->name('pos.bstb');
});
"""

if "// DEALER POS ROUTES" not in content:
    with open(file_path, 'a', encoding='utf-8') as f:
        f.write(dealer_routes)
    print("Dealer routes added successfully!")
else:
    print("Dealer routes already exist.")
