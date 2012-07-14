
function Validator(form) {
  this.form = form;

  this.form.addEventListener("submit", this.validateForm.bind(this), false);
}

Validator.prototype.validateForm = function(e) {
  // Loop through every input element in the form
  // and validate the ones that have a matching className
  var el;
  var pass1, pass2;
  this.failed = false;

  for (var i = 0; i < this.form.length && !this.failed; i++) {
    el = this.form[i];

    // Validate input formats
    if (el.className.match(/\bvPassword(1|2)?\b/)
          && !this.validatePassword(el)) {
      this.fail(el, "8-20 characters; letters, numbers and symbols only");
    }
    if (el.className.match(/\bvUsername\b/)
          && !this.validateUsername(el)) {
      this.fail(el, "4-20 characters; letters, numbers, and underscore only");
    }
    if (el.className.match(/\bvEmail\b/)
          && !this.validateEmail(el)) {
      this.fail(el, "Must be valid email address");
    }
    if (el.className.match(/\bvDate\b/)
          && !this.validateDate(el)) {
      this.fail(el, "Must be a valid date format with day, month, and year");
    }
    if (el.className.match(/\bvTime\b/)
          && !this.validateTime(el)) {
      this.fail(el, "Must be a valid time format");
    }
    if (el.className.match(/\bvPhone\b/)
          && !this.validatePhone(el)) {
      this.fail(el, "Must be a valid phone number");
    }

    // Validate matching passwords
    if (el.className.match(/\bvPassword1\b/)) {
      pass1 = el;
      if (pass1 && pass2 && !this.validatePasswordsMatch(pass1, pass2)) {
        this.fail(el, "Passwords must match");
      }
    }
    if (el.className.match(/\bvPassword2\b/)) {
      pass2 = el;
      if (pass1 && pass2 && !this.validatePasswordsMatch(pass1, pass2)) {
        this.fail(el, "Passwords must match");
      }
    }
   
  }

  // Stop form submission if we failed validation
  if (e && this.failed) {
    e.preventDefault();
  }
  return !this.failed;
}


Validator.prototype.fail = function(el, message) {
  this.failed = true;

  // Let the user know the problem
  // Should be a nicer user experience in future...
  alert(message);
  el.focus();
}

Validator.prototype.validatePassword = function(el) {
  return Boolean(el.value.match(/^[\w!@#$%^&*()_+=~`-]{8,20}$/));
}

Validator.prototype.validatePasswordsMatch = function(el1, el2) {
  return el1.value == el2.value;
}

Validator.prototype.validateEmail = function(el) {
  return Boolean(el.value.match(/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i));
}

Validator.prototype.validateUsername = function(el) {
  return Boolean(el.value.match(/^[A-Z0-9_]{4,20}$/i));
}

Validator.prototype.validateDate = function(el) {
  return Boolean(Date.parse(el.value));
}

Validator.prototype.validateTime = function(el) {
  var result;
  result = el.value.match(/^(\d{1,2})(?::(\d\d))? ?(?:(a|p)\.?m?\.?)?$/i);

  if (!result) {
    return false;
  }

  var h = parseInt(result[1]);
  var m = parseInt(result[2]);
  var ampm = Boolean(result[3]);
  
  if(h > 24
        || (h == 24 && m > 0)
        || m > 59
        || (h > 12 && ampm)) {
    return false;
  }

  return true;
}

Validator.prototype.validatePhone = function(el) {
  return Boolean(el.value.match(/^\(?\d{3}\)?-?\d{3}-?\d{4}$/));
}

