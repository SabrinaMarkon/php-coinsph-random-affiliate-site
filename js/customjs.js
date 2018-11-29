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

    var litfields = '';
    if (ans == "banner") {
      litfields = litfields + '<label for="promotionalimage">Image URL:</label><input type="text" name="promotionalimage" id="promotionalimage" size="55" maxlength="255" class="form-control w-50">';
      document.getElementById('previewfield').style.visibility = 'visible';
      document.getElementById('previewfield').style.display = 'block';
      document.getElementById('promotionaloptionsfields').style.visibility = 'visible';
      document.getElementById('promotionaloptionsfields').innerHTML=litfields;
      tinyMCE.execCommand('mceFocus', false, 'promotionaladbody');                    
      tinyMCE.execCommand('mceRemoveEditor', false, 'promotionaladbody');
      document.getElementById('type').focus();
    }

    if (ans == "email") {
      litfields = litfields + '<label for="promotionalsubject">Email Subject:</label><input type="text" name="promotionalsubject" size="55" maxlength="255" class="form-control w-50">';
      litfields += '<label for="promotionaladbody">Email Message:</label><br><textarea name="promotionaladbody" id="promotionaladbody" rows="20"></textarea>';
      document.getElementById('previewfield').style.visibility = 'hidden';
      document.getElementById('previewfield').style.display = 'none';
      document.getElementById('promotionaloptionsfields').style.visibility = 'visible';
      document.getElementById('promotionaloptionsfields').innerHTML=litfields;
      tinyMCE.execCommand('mceAddEditor', true, 'promotionaladbody');
      document.getElementById('type').focus();
    }

  }

  if (ans == "") {
    
    document.getElementById('previewfield').style.visibility = 'hidden';
    document.getElementById('previewfield').style.display = 'none';
    tinyMCE.execCommand('mceFocus', false, 'promotionaladbody');                    
    tinyMCE.execCommand('mceRemoveEditor', false, 'promotionaladbody');
    document.getElementById('promotionaloptionsfields').style.visibility = 'hidden';
    document.getElementById('promotionaloptionsfields').innerHTML='';
    document.getElementById('type').focus();
  }
}

var text_max = 200;
$('#count_message').html(text_max + ' remaining');

$('#text').keyup(function() {
  var text_length = $('#text').val().length;
  var text_remaining = text_max - text_length;
  
  $('#count_message').html(text_remaining + ' remaining');
});

