function onOpen(e) {
  SpreadsheetApp
    .getUi()
    .createMenu('VAUVERT 2026')
    .addItem('🚀 Mettre a jour le site', 'updateProd')
    .addToUi();
}


function updateProd() {
  const sheetParser = new SheetParser();

  const data = sheetParser.getData('global');

  console.log(JSON.stringify(data, null, 2))

  const resultat = pushDataToGitHub(JSON.stringify(data));
  
  if (resultat !== '') {
    Logger.log('Upload réussi !');
  } else {
    Logger.log('Échec: ' + resultat.error);
  }
}

function slugify(str) {
  return str.replace(/# /g, '').replace(/ /g, '_').replace(/-/g, '_').toLowerCase();
}

// --- CONFIGURATION ---
const GITHUB_USER = 'xmeunier';
const GITHUB_REPO = 'vauvert2026';
const GITHUB_TOKEN = '****'; // Your Personal Access Token
const FILE_PATH = 'config.json'; // Path within the repo (e.g., folder/filename.csv)
const BRANCH = 'main'; 

function encodeBase64UTF8(str) {
  // Convertit la chaîne en UTF-8 puis en base64
  const bytes = Utilities.newBlob(str).getBytes();
  return Utilities.base64Encode(bytes);
}

function pushDataToGitHub(jsondata) {
  // 1. Get the data from the active sheet
  const sheet = SpreadsheetApp.getActiveSheet();
  const encodedContent = encodeBase64UTF8(jsondata);
  
  // 4. Check if file exists to get the current SHA (Required for updates)
  const currentSha = getFileSha(FILE_PATH);
  
  // 5. Prepare the Payload
  const payload = {
    message: "Updated config.json from Google Sheets via Apps Script V3",
    content: encodedContent,
    branch: BRANCH
  };
  
  // If the file exists, we must include the SHA to update it
  if (currentSha) {
    payload.sha = currentSha;
  }

  // 6. Send the PUT request (This acts as git add + commit + push)
  const url = `https://api.github.com/repos/${GITHUB_USER}/${GITHUB_REPO}/contents/${FILE_PATH}`;
  
  const options = {
    method: "put",
    headers: {
      "Authorization": "Bearer " + GITHUB_TOKEN,
      "Accept": "application/vnd.github.v3+json"
    },
    payload: JSON.stringify(payload),
    muteHttpExceptions: true
  };
  
  const response = UrlFetchApp.fetch(url, options);
  
  // 7. Log result
  if (response.getResponseCode() === 200 || response.getResponseCode() === 201) {
    Logger.log("Success! File committed and pushed.");
  } else {
    Logger.log("Error: " + response.getContentText());
  }
}

// Helper function to get the existing file's SHA
function getFileSha(path) {
  const url = `https://api.github.com/repos/${GITHUB_USER}/${GITHUB_REPO}/contents/${path}?ref=${BRANCH}`;
  const options = {
    method: "get",
    headers: {
      "Authorization": "Bearer " + GITHUB_TOKEN
    },
    muteHttpExceptions: true
  };
  
  const response = UrlFetchApp.fetch(url, options);
  
  if (response.getResponseCode() === 200) {
    const data = JSON.parse(response.getContentText());
    return data.sha;
  }
  return null; // File doesn't exist yet
}

class SheetParser {
getData(sheetName) {
    console.log("==> GET DATA FROM", sheetName);
    this.sheet = SpreadsheetApp.getActiveSpreadsheet().getSheetByName(sheetName);
    const indexData = this.sheet.getDataRange().getValues();
    const indexDataStyled = this.sheet.getDataRange().getRichTextValues();
    let slugSheetName = slugify(sheetName)
    let objectInProgress = '';
    let mainObject = '';
    let objectVariables = [];
    let arrayColumns = []; // Pour stocker les index des colonnes qui doivent être des tableaux
    let data = {};
    let dataInProgress = {}
    let styledColumns = [];

    for (let i = 0; i < indexData.length; i++) {
      let indexLine = indexData[i];
    
      if (indexLine[0][0] === '#') {
        if (objectInProgress !== '') {
          this.checkImages(dataInProgress, slugSheetName + '_' + objectInProgress, i, false);
        }

        if (mainObject !== '') {
          data[mainObject] = dataInProgress;
        }

        objectInProgress = slugify(indexLine[0])
        console.log("SECTION", objectInProgress)
        objectVariables = [];
        arrayColumns = [];
        styledColumns = [];
        dataInProgress = {};

        mainObject = slugify(indexLine[0]);
      } else if (indexLine[0] !== '') {
        if (this.sheet.getRange(i + 1, 1).getBackground() !== "#ffffff" && this.sheet.getRange(i + 1, 1).getBackground() !== "#f8f9fa") {
          if (this.sheet.getRange(i + 1, 2).getBackground() === "#ffffff") {
            let dataVariable = slugify(indexLine[0]);
            dataInProgress[dataVariable] = indexLine[1];

            if (dataVariable.startsWith('image')) {
              dataInProgress['index_' + dataVariable] = i;
            }
          } else {
            let countColumn = 0;
            for (const cell of indexLine) {
              if (cell !== '') {
                // Détecter si le nom de colonne est entre crochets
                let isArray = cell.startsWith('[') && cell.endsWith(']');
                let cleanCell = isArray ? cell.slice(1, -1) : cell;
                
                objectVariables.push(slugify(cleanCell));
                
                if (isArray) {
                  arrayColumns.push(countColumn);
                }

                if (cell.includes(' - styled')) {
                  styledColumns.push(countColumn);
                }
                countColumn++;
              }
            }
            dataInProgress = [];
          }
        } else {
          let lineData = {}
          for (let j = 0; j < objectVariables.length; j++) {
            let value;
            
            if (styledColumns.indexOf(j) === -1) {
              value = indexLine[j];
            } else {
              value = '';

              for (const subText of indexDataStyled[i][j].getRuns()) {
                if (subText.getTextStyle().isBold()) {
                  value += '<b>' + subText.getText() + '</b>';
                } else {
                  value += subText.getText();
                }
              }
            }
            
            // Si la colonne doit être un tableau, on split par virgule
            if (arrayColumns.indexOf(j) !== -1) {
              if (value && value !== '') {
                lineData[objectVariables[j]] = value.split(',').map(item => item.trim());
              } else {
                lineData[objectVariables[j]] = [];
              }
            } else {
              lineData[objectVariables[j]] = value;
            }
          }
          this.checkImages(lineData, slugSheetName + '_' + objectInProgress, i, true);

          dataInProgress.push(lineData);
        }
      }
    }
    
    if (mainObject !== '') {
      data[mainObject] = dataInProgress;
    }

    return data;
  }

  checkImages(data, dataSection, line, sameLine = false) {
    const keys = Object.keys(data);
    let imageKeys = keys.filter((key) => key.startsWith('photo'));
    
    for (let key of imageKeys) {
      Logger.log(keys);
    }
  }
}
