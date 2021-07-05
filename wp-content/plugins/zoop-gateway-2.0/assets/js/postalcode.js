function cv_searchPostalCode(this_field = null) { //revisado
  jQuery(".progressbar").show();
  var postalcode = this_field.val();
  var message = document.getElementById('postalcodeMessage');
  jQuery.ajax({
    url: wpurl.siteurl + '/wp-admin/admin-ajax.php',
    type: "POST",
    data: {'action': 'CV_check_postal_code', postalcode: postalcode},
    dataType: "json",
    success: function (response) {
      if (response.street_type && response.street) {
        jQuery('#street').val(response.street_type + " " + response.street);
        jQuery('#street').prop('readonly', true);
      } else {
        jQuery('#street').prop('readonly', false);
      }
      if (response.city) {
        jQuery('#city').val(response.city);
        jQuery('#city').prop('readonly', true);
      } else {
        jQuery('#city').prop('readonly', false);
      }
      if (response.district) {
        jQuery('#district').val(response.district);
        jQuery('#district').prop('readonly', true);
      } else {
        jQuery('#district').prop('readonly', false);
      }
      if (response.state) {
        jQuery('#state').val(response.state);
        jQuery('#state').prop('readonly', true);
      } else {
        jQuery('#state').prop('readonly', false);
      }
      if (response.text) {
        message.innerHTML = response.text;
      } else {
        message.innerHTML = '';
      }
      jQuery(".progressbar").hide();
    },
    error: function (response) {
      jQuery('#street').prop('readonly', false);
      jQuery('#city').prop('readonly', false);
      jQuery('#district').prop('readonly', false);
      jQuery('#state').prop('readonly', false);
      jQuery(".progressbar").hide();
    }
  });
}

function cv_searchPostalCode1(this_field = null) { //revisado
  jQuery(".progressbar").show();
  var postalcode = this_field.val();
  var message = document.getElementById('postalcodeMessage1');
  jQuery.ajax({
    url: wpurl.siteurl + '/wp-admin/admin-ajax.php',
    type: "POST",
    data: {'action': 'CV_check_postal_code', postalcode: postalcode},
    dataType: "json",
    success: function (response) {
      if (response.street_type && response.street) {
        jQuery('#rgAddressStreet').val(response.street_type + " " + response.street);
        jQuery('#rgAddressStreet').prop('readonly', true);
      } else {
        jQuery('#rgAddressStreet').prop('readonly', false);
      }
      if (response.city) {
        jQuery('#rgAddressCity').val(response.city);
        jQuery('#rgAddressCity').prop('readonly', true);
      } else {
        jQuery('#rgAddressCity').prop('readonly', false);
      }
      if (response.district) {
        jQuery('#rgAddressDistrict').val(response.district);
        jQuery('#rgAddressDistrict').prop('readonly', true);
      } else {
        jQuery('#rgAddressDistrict').prop('readonly', false);
      }
      if (response.state) {
        jQuery('#rgAddressUF').val(response.state);
        jQuery('#rgAddressUF').prop('readonly', true);
      } else {
        jQuery('#rgAddressUF').prop('readonly', false);
      }
      if (response.text) {
        message.innerHTML = response.text;
      } else {
        message.innerHTML = '';
      }
      jQuery(".progressbar").hide();
    },
    error: function (response) {
      jQuery('#rgAddressStreet').prop('readonly', false);
      jQuery('#rgAddressCity').prop('readonly', false);
      jQuery('#rgAddressDistrict').prop('readonly', false);
      jQuery('#rgAddressUF').prop('readonly', false);
      jQuery(".progressbar").hide();
    }
  });
}