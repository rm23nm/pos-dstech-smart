const fs = require('fs');

const appFile = 'd:/OneDrive/My Project Aplikasi/Aplikasi Gate Acses DStechsmart/frontend/src/App.jsx';
let app = fs.readFileSync(appFile, 'utf8');

const startTag = '{/* Info Tarif Tambahan */}';
const startIndex = app.indexOf(startTag);

if (startIndex !== -1) {
    // Find the end of the block. The block is wrapped in <div style={{ display: 'grid'...
    // It contains TARIF MOTOR and TARIF MOBIL cards.
    // It ends right before </div> </div> for the Right Column's first premium-glass block.
    
    // Just find the end of the second TARIF MOBIL card's closing div.
    // It ends with: Rp {lostTicketFineMobil?.toLocaleString() || 0}</span></div></div></div>
    
    const endStr = "lostTicketFineMobil?.toLocaleString() || 0}</span>\n                            </div>\n                          </div>\n                        </div>";
    
    let endStrIndex = app.indexOf(endStr, startIndex);
    if (endStrIndex !== -1) {
        const fullBlockEnd = endStrIndex + endStr.length;
        
        let blockToMove = app.substring(startIndex, fullBlockEnd);
        // Include preceding whitespace
        const prevNewline = app.lastIndexOf('\n', startIndex);
        if (prevNewline !== -1) {
            blockToMove = app.substring(prevNewline + 1, fullBlockEnd);
        }

        // Remove from original
        app = app.replace(blockToMove, '');

        // Now insert it into Left column, under the RESET button
        const resetBtnStr = `if(scanInputRef.current) scanInputRef.current.value = ''; }}>RESET</button>\n                    </div>`;
        const resetBtnIndex = app.indexOf(resetBtnStr);
        
        if (resetBtnIndex !== -1) {
            const insertPos = resetBtnIndex + resetBtnStr.length;
            
            app = app.substring(0, insertPos) + '\n\n' + '                    ' + blockToMove + app.substring(insertPos);
            
            fs.writeFileSync(appFile, app);
            console.log("Successfully moved using index search.");
        } else {
            console.log("Could not find reset button.");
        }
    } else {
        console.log("Could not find end of tarif block.");
    }
} else {
    console.log("Could not find start tag.");
}
