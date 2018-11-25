// Copy to clipboard:
const copyToClipboard = (str) => {
  const el = document.createElement('textarea');  // Create a <textarea> element
  el.value = str;                                 // Set its value to the string that you want copied
  el.setAttribute('readonly', '');                // Make it readonly to be tamper-proof
  el.style.position = 'absolute';                 
  el.style.left = '-9999px';                      // Move outside the screen to make it invisible
  document.body.appendChild(el);                  // Append the <textarea> element to the HTML document
  const selected =            
    document.getSelection().rangeCount > 0        // Check if there is any content selected previously
      ? document.getSelection().getRangeAt(0)     // Store selection if found
      : false;                                    // Mark as false to know no selection existed before
  el.select();                                    // Select the <textarea> content
  document.execCommand('copy');                   // Copy - only works as a result of a user action (e.g. click events)
  document.body.removeChild(el);                  // Remove the <textarea> element
  if (selected) {                                 // If a selection existed before copying
    document.getSelection().removeAllRanges();    // Unselect everything on the HTML document
    document.getSelection().addRange(selected);   // Restore the original selection
  }
};

// building the form for the admin promotional page depending on what was selected.
function setuppromotional(ans) {
if (ans != "") {
  var littext = '';
  var litfields = '';
  if (ans == "banner") {
    littext = 'Image&nbsp;URL:';
    litfields = litfields + '<input type="text" name="promotionalimage" size="55" maxlength="255" class="typein">';
    document.getElementById('previewfield').style.visibility = 'visible';
    document.getElementById('previewfield').style.display = 'block';
    document.getElementById('promotionaloptionstext').style.visibility = 'visible';
    document.getElementById('promotionaloptionsfields').style.visibility = 'visible';
    document.getElementById('promotionaloptionstext').innerHTML=littext;
    document.getElementById('promotionaloptionsfields').innerHTML=litfields;
    tinyMCE.execCommand('mceFocus', false, 'promotionaladbody');                    
    tinyMCE.execCommand('mceRemoveControl', false, 'promotionaladbody');
  }
  if (ans == "email") {
    littext = 'Subject&nbsp;and&nbsp;Message:';
    litfields = litfields + '<input type="text" name="promotionalsubject" size="55" maxlength="255" class="typein"><br><textarea name="promotionaladbody" id="promotionaladbody" rows="20" cols="80"></textarea>';
    document.getElementById('previewfield').style.visibility = 'hidden';
    document.getElementById('previewfield').style.display = 'none';
    document.getElementById('promotionaloptionstext').style.visibility = 'visible';
    document.getElementById('promotionaloptionsfields').style.visibility = 'visible';
    document.getElementById('promotionaloptionstext').innerHTML=littext;
    document.getElementById('promotionaloptionsfields').innerHTML=litfields;
    tinyMCE.execCommand('mceAddControl', false, 'promotionaladbody');
  }
}
if (ans == "") {
  document.getElementById('previewfield').style.visibility = 'hidden';
  document.getElementById('previewfield').style.display = 'none';
  tinyMCE.execCommand('mceFocus', false, 'promotionaladbody');                    
  tinyMCE.execCommand('mceRemoveControl', false, 'promotionaladbody');
  document.getElementById('promotionaloptionstext').style.visibility = 'hidden';
  document.getElementById('promotionaloptionsfields').style.visibility = 'hidden';
  document.getElementById('promotionaloptionstext').innerHTML='';
  document.getElementById('promotionaloptionsfields').innerHTML='';
}
}