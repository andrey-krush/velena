/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!************************!*\
  !*** ./src/js/main.js ***!
  \************************/
function dropdown(options) {
  let opts = {
    closeOnClick: true,
    timing: false,
    closeBtnClass: false,
    globalContainer: '',
    effect: 'fade'
  };
  let timing = 300;
  $.extend(opts, options);
  function open(e) {
    e.preventDefault();
    let container = $(this).closest('.' + opts.containerClass);
    let thisDropdown = container.find(opts.dropdownSelector);
    if (e.type === 'focusin') container.addClass('focusin');
    if ((container.hasClass('is-open') || container.hasClass('disabled')) && !container.hasClass('focusin')) {
      close();
      return;
    }
    if (e.type !== 'focusin') container.removeClass('focusin');
    close(container);
    container.addClass('is-open').css('z-index', '4');
    if (opts.effect === 'fade') {
      thisDropdown.fadeIn(timing);
    } else {
      thisDropdown.slideDown(timing);
    }
  }
  function close() {
    let dontClose = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
    let dropdownsToClose = $('.' + opts.containerClass);
    if (dontClose) dropdownsToClose = dropdownsToClose.not(dontClose);
    dropdownsToClose.find('li').removeClass('hover');
    dropdownsToClose.removeClass('is-open');
    if (opts.effect === 'fade') {
      dropdownsToClose.find(opts.dropdownSelector).fadeOut(timing);
    } else {
      dropdownsToClose.find(opts.dropdownSelector).slideUp(timing);
    }
    setTimeout(function () {
      dropdownsToClose.removeAttr('style');
    }, timing);
  }
  $(document).on('click', function (e) {
    let thisEl = $(e.target);
    if (opts.closeBtnClass ? thisEl.hasClass(opts.closeBtnClass) : false) close();
    if (!thisEl.hasClass(opts.containerClass) && !thisEl.closest('.' + opts.containerClass).length) close();
  });
  $(document).on('click', opts.globalContainer + ' .' + opts.containerClass + ' ' + opts.btnSelector, open);
  $(document).on('focusin', opts.globalContainer + ' .' + opts.containerClass + ' ' + opts.btnSelector, open);
  $(document).on('focusout', opts.globalContainer + ' .' + opts.containerClass + ' ' + opts.btnSelector, function () {
    $(this).closest('.' + opts.containerClass).removeClass('focusin');
    close($(this).closest('.' + opts.containerClass));
  });
  $(document).on('close-dropdown', close);
  if (options.timing !== false) timing = options.timing;
  if (options.containerClass === 'select') timing = 0;
  if (opts.closeOnClick) {
    $(document).on('click', opts.globalContainer + ' .' + opts.containerClass + ' ' + opts.dropdownSelector, function () {
      if (!$(this).closest('.' + opts.containerClass).hasClass('checkbox')) close();
    });
  }
}
function fadeIn(el, timeout, display) {
  let afterFunc = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : false;
  el.style.opacity = 0;
  el.style.display = display || 'block';
  el.style.transition = `opacity ${timeout}ms`;
  setTimeout(() => {
    el.style.opacity = 1;
    if (afterFunc) {
      setTimeout(function () {
        afterFunc();
      }, timeout);
    }
  }, 10);
}
function fadeOut(el, timeout) {
  let afterFunc = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : false;
  el.style.opacity = 1;
  el.style.transition = `opacity ${timeout}ms`;
  el.style.opacity = 0;
  setTimeout(() => {
    el.style.display = 'none';
    if (afterFunc) {
      afterFunc();
    }
  }, timeout);
}
const only_num = /^[0-9.]+$/;
const tel_reg = /^[0-9+]+$/;
const only_num_replace = /[^0-9.]/g;
const tel_reg_replace = /[^0-9+]/g;
const password_reg = /^(?=.*[a-zA-Z])(?=.*\d).*$/;
const email_reg = /^(([^<>()\[\]\\.,;:\s@"]{1,62}(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))$/;

// const email_reg = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
const email_reg_full = /^(([^<>()\[\]\\.,;:\s@"]{1,62}(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z-аА-яЯ\-0-9]+\.)+[a-zA-Z-аА-яЯ]{2,62}))$/;
const validationRules = {
  'email': {
    'rules': {
      regex: email_reg_full
    }
  },
  'numeric': {
    'rules': {
      regex: only_num
    }
  },
  'numeric-replace': {
    'rules': {
      regexReplace: only_num_replace
    }
  },
  'phone': {
    'rules': {
      regexReplace: tel_reg_replace
    }
  },
  'password': {
    'rules': {
      password: true
    }
  },
  'password_repeat': {
    'rules': {
      password_repeat: true
    }
  }
};
function selectAll(selector) {
  let container = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : false;
  return Array.from(!container ? document.querySelectorAll(selector) : container.querySelectorAll(selector));
}
function printf(string) {
  let vars = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : [];
  vars.forEach(function (thisVar, index) {
    let r = new RegExp('&' + (index + 1) + '(?![0-9])', 'g');
    if (r.test(string)) {
      string = string.replace(r, thisVar);
    } else {
      string += ' ' + thisVar;
    }
  });
  return string;
}
function validate(form) {
  let newOpts = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
  let defaultOpts = {
    methodsOnInput: ['regexReplace', 'maxlength'],
    submitFunction: null,
    highlightFunction: null,
    unhighlightFunction: null,
    checkOnInput: false,
    checkOnInputAfterSubmit: true,
    checkOnFocusOut: true,
    errorClass: 'is-error'
  };
  let opts = {
    ...defaultOpts,
    ...newOpts
  };
  if (typeof form === 'string') form = document.querySelector(form);
  let _this = {
    isValid: true,
    allInputs: selectAll('input:not([type="hidden"])[name], .output_value, select, textarea', form),
    formSubmitted: false,
    init: function () {
      form.setAttribute('novalidate', 'novalidate');
      form.setAttribute('data-js-validation', 'novalidate');
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        _this.validate();
        _this.formSubmitted = true;
      });
      form.valid = function () {
        let addErrors = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
        return _this.validate(false, addErrors);
      };
      form.addInput = addInput;
      function addInput(input) {
        let thisInputMethods = [];
        let dataValidation = input.getAttribute('data-validation');
        if (_this.allInputs.indexOf(input) === -1) {
          _this.allInputs.push(input);
        }
        if (input.hasAttribute('required')) {
          thisInputMethods.push({
            callback: methods['required'],
            errorMessage: (validationErrors[dataValidation] ? validationErrors[dataValidation]['required'] : false) || validationErrors['required'] || 'This field is required'
          });
        }
        if (input.hasAttribute('data-tel-mask')) {
          thisInputMethods.push({
            callback: methods['tel_mask'],
            errorMessage: ''
          });
        }
        if (input.hasAttribute('minlength')) {
          thisInputMethods.push({
            callback: methods['minlength'],
            errorMessage: (validationErrors[dataValidation] ? validationErrors[dataValidation]['minlength'] + input.getAttribute('minlength') : false) || validationErrors['minlength'] + input.getAttribute('minlength') || 'Min length is ' + input.getAttribute('minlength') + ' symbols'
          });
        }
        if (input.hasAttribute('maxlength')) {
          thisInputMethods.push({
            callback: methods['maxlength'],
            errorMessage: (validationErrors[dataValidation] ? validationErrors[dataValidation]['maxlength'] : false) || validationErrors['maxlength'] || 'Max length is ' + input.getAttribute('maxlength') + ' symbols'
          });
        }
        // if (input.getAttribute('type') === 'email') {
        //   thisInputMethods.push({
        //     callback: methods['regex'],
        //     passedValue: email_reg,
        //     errorMessage: validationErrors['email']['regex'] || validationErrors['invalid'] || 'This field is invalid'
        //   });
        // }
        if (dataValidation) {
          let thisValidation = validationRules[input.getAttribute('data-validation')];
          if (thisValidation) {
            thisValidation = thisValidation['rules'];
          }
          if (thisValidation) {
            Object.keys(thisValidation).forEach(methodName => {
              let existingMethod = false;
              let thisValidationValue = thisValidation[methodName];
              if (methods[methodName]) {
                existingMethod = {
                  callback: methods[methodName],
                  passedValue: thisValidationValue,
                  errorMessage: (validationErrors[dataValidation] ? validationErrors[dataValidation][methodName] : false) || validationErrors['invalid'] || 'This field is invalid'
                };
              }
              if (existingMethod) thisInputMethods.push(existingMethod);
            });
          }
        }
        function isInputRequired() {
          let removeIt = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : false;
          let thisInputActualMethods = input['validationMethods'];
          let hasRequired = false;
          thisInputActualMethods.map(function (method) {
            if (method.callback.name === 'required') {
              hasRequired = true;
              if (removeIt) thisInputActualMethods.splice(thisInputActualMethods.indexOf(method), 1);
            }
          });
          return hasRequired;
        }
        function setRequired() {
          let thisInputActualMethods = input['validationMethods'];
          if (isInputRequired()) return;
          thisInputActualMethods.push({
            callback: methods['required'],
            errorMessage: (validationErrors[dataValidation] ? validationErrors[dataValidation]['required'] : false) || validationErrors['required'] || 'This field is required'
          });
          input['validationMethods'] = thisInputMethods;
        }
        function removeRequired() {
          isInputRequired(true);
        }
        function setError(message) {
          _this.highlight(input);
          _this.errorPlacement(message, input);
        }
        function removeError() {
          _this.unhighlight(input);
          _this.errorRemove(input);
        }
        input['setError'] = setError;
        input['removeError'] = removeError;
        input['setRequired'] = setRequired;
        input['removeRequired'] = removeRequired;
        input['isRequired'] = isInputRequired;
        input['validationMethods'] = thisInputMethods;
        input['had_input'] = false;
        input['had_focusout'] = false;
        input['isValid'] = function () {
          return _this.valid(input);
        };
        input.addEventListener('input', function () {
          this['had_input'] = true;
          if (opts.methodsOnInput.length) {
            _this.valid(this, opts.methodsOnInput);
            return;
          }
          if (opts.checkOnFocusOut && input['had_focusout']) {
            _this.valid(this);
            return;
          }
          if (opts.checkOnInput) {
            _this.valid(this);
            return;
          }
          if (opts.checkOnInputAfterSubmit && _this.formSubmitted) {
            _this.valid(this);
          }
        });
        if (!opts.checkOnInput && opts.checkOnFocusOut) input.addEventListener('focusout', function () {
          this['had_focusout'] = true;
          if (!this['had_focusout'] || !this['had_input']) return;
          _this.valid(this);
        });
      }
      _this.allInputs.map(function (input) {
        addInput(input);
      });
      if (opts['rules']) {
        Object.keys(opts['rules']).forEach(function (rule) {
          let input = document.querySelector('[name="' + rule + '"]');
          let thisRuleValue = opts['rules'][rule];
          let thisInputMethods = input['validationMethods'] || [];
          if (!input) return;
          if (thisRuleValue['laravelRequired']) thisRuleValue = 'required';
          let thisRuleMessage = (validationErrors[thisRuleValue] ? validationErrors[thisRuleValue] : false) || validationErrors[thisRuleValue] || 'Це поле обов\'язкове';
          if (opts['messages'] && opts['messages'][rule] && (opts['messages'][rule][thisRuleValue] || opts['messages'][rule]['laravelRequired'])) thisRuleMessage = opts['messages'][rule][thisRuleValue] || opts['messages'][rule]['laravelRequired'];
          if (methods[thisRuleValue]) {
            thisInputMethods.push({
              callback: methods[thisRuleValue],
              errorMessage: thisRuleMessage
            });
            input['validationMethods'] = thisInputMethods;
          }
        });
      }
    },
    valid: function (input) {
      let checkMethods = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : [];
      let addError = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
      let thisMethods = input['validationMethods'];
      if (!thisMethods) return true;
      let isInputValid = true;
      let isInputHidden = input.closest('.form__input--hidden') || input.closest('[style="display: none;"]');
      if (checkMethods.length) {
        thisMethods = [];
        checkMethods.forEach(function (thisMethod) {
          let thisInputMethod = input['validationMethods'].find(obj => obj.callback.name === thisMethod);
          if (thisInputMethod) {
            thisMethods.push(thisInputMethod);
          }
        });
      }
      thisMethods.forEach(function (thisMethod) {
        if (!isInputValid || isInputHidden) return;
        let isThisValid = thisMethod['callback'](input.value, input, thisMethod['passedValue']);
        if (!isThisValid) {
          if (addError) {
            _this.errorPlacement(thisMethod['errorMessage'], input);
            _this.highlight(input);
          }
          _this.isValid = isInputValid = input['validity']['valid'] = false;
        }
      });
      if (isInputValid) {
        _this.errorRemove(input);
        _this.unhighlight(input);
        input['validity']['valid'] = true;
      }
      return isInputValid;
    },
    validate: function () {
      let submit = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : true;
      let addError = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
      _this.isValid = true;
      _this.allInputs.map(function (input) {
        console.log('check ', input);
        if (!_this.valid(input, addError)) {
          _this.isValid = false;
          console.log('not valid ', input);
        } else {
          console.log('valid ', input);
        }
      });
      console.log('form is valid?', _this.isValid);
      if (_this.isValid && submit) _this.submitHandler();
      return _this.isValid;
    },
    highlight: function (element) {
      if (typeof opts.highlightFunction === 'function') {
        opts.highlightFunction(form, element);
        return;
      }
      console.log(element);
      let container = element.parentElement;
      if (!container) container = element.closest('.form-group');
      if (container) container.classList.add(opts.errorClass);
    },
    unhighlight: function (element) {
      if (typeof opts.unhighlightFunction === 'function') {
        opts.unhighlightFunction(form, element);
        return;
      }
      let container = element.parentElement;
      if (!container) container = element.closest('.form-group');
      if (container) container.classList.remove(opts.errorClass);
    },
    errorPlacement: function (error, element) {
      if (!error) return;
      let container = element.parentElement;
      let errorEl = container.querySelector('.input__error');
      if (!errorEl) {
        errorEl = document.createElement('div');
        errorEl.classList.add('input__error');
        container.append(errorEl);
      }
      errorEl.innerHTML = error;
    },
    errorRemove: function (element) {
      let container = element.parentElement;
      if (!container) return;
      container = container.querySelector('.input__error');
      if (!container) return;
      container.innerHTML = '';
    },
    submitHandler: function () {
      if (typeof opts.submitFunction === 'function') {
        opts.submitFunction(form);
      } else {
        form.submit();
      }
    }
  };
  let methods = {
    "regex": function (value, element, regexp) {
      return value == '' || new RegExp(regexp).test(value);
    },
    "required": function (value, input) {
      let woocommerceContainer = input.closest('.form-row');
      let isHidden = false;
      if (woocommerceContainer) {
        if (woocommerceContainer.style.display === 'none') {
          isHidden = true;
        }
      }
      return isHidden || (input.getAttribute('type') === 'checkbox' || input.getAttribute('type') === 'radio' ? input.checked : !!value);
    },
    "regexReplace": function (value, element, regexp) {
      element.value = element.value.replace(new RegExp(regexp), "");
      return true;
    },
    "password": function (value, element, regexp) {
      return !element.hasAttribute('required') && !value || value.length >= 8;
    },
    "password_repeat": function (value, element, regexp) {
      let password = element.closest('form').querySelector('[data-validation="password"]');
      return !element.hasAttribute('required') && !value || value.length >= 8 && value === password.value;
    },
    "tel_mask": function (value, element, regexp) {
      if (typeof element['checkValidCallback'] !== 'undefined') {
        element.checkValidCallback();
      }
      return typeof element['telMask'] !== 'undefined' ? element['telMask'].isValidNumber() || value === '' : true;
    },
    "minlength": function (value, element, passedValue) {
      let min = passedValue || +element.getAttribute("minlength");
      if (!min || !value) return true;
      return value.length >= min;
    },
    "maxlength": function (value, element, regexp) {
      let max = +element.getAttribute("maxlength");
      if (!max) return true;
      if (element.value.length > max) {
        element.value = element.value.substr(0, max);
      }
      return true;
    }
  };
  if (!form.hasAttribute('data-js-validation')) _this.init();
}
function formReset(form) {
  if (form.classList.contains('dont-reset')) return;
  form.reset();
  form.querySelectorAll('.image-preview').forEach(item => item.remove());
  form.querySelectorAll('.ql-editor').forEach(item => item.innerHTML = '');
  form.querySelectorAll('.is-visible').forEach(item => item.classList.remove('is-visible'));
  selectAll('.input--file', form).map(function (input) {
    input.querySelector('[data-placeholder]').innerHTML = input.querySelector('[data-placeholder]').getAttribute('data-placeholder');
  });
}
function ajaxSuccess(form, data) {
  let popupSuccess = form.dataset.successPopup;
  let formBtn = form.querySelector('[type="submit"]');
  let redirectUrl = data['data'] ? data['data']["redirect_url"] : false;
  let successContainer = $(form).closest('.show-hide-container').length ? $(form).closest('.show-hide-container') : $(form);
  let successBlockShow = successContainer.find('.show-on-success');
  let successBlockHide = successContainer.find('.hide-on-success');
  if (redirectUrl) {
    window.location = redirectUrl;
    return;
  }
  if (successContainer.length && successBlockShow.length) {
    if (successBlockHide.length) {
      successBlockHide.fadeOut(300, function () {
        successBlockShow.fadeIn(300);
      });
    } else {
      successBlockShow.fadeIn(300);
    }
    if (!$('.fancybox__container').length && !successContainer.hasClass('no-scroll')) $("html, body").animate({
      scrollTop: 0
    }, "slow");
  }
  if (formBtn) formBtn.removeAttribute('disabled');
  Fancybox.close();
  if (popupSuccess) {
    Fancybox.show([{
      src: popupSuccess,
      type: 'inline',
      placeFocusBack: false,
      trapFocus: false,
      autoFocus: false
    }], {
      dragToClose: false
    });
  }
  if (form['ajaxSuccess']) {
    form['ajaxSuccess'](form, data);
  }
  formReset(form);
}
function ajaxError(form, data) {
  let formBtn = form.querySelector('[type="submit"]');
  let popupError = form.dataset.errorPopup;
  if (formBtn) formBtn.removeAttribute('disabled');
  if (popupError) {
    Fancybox.show([{
      src: popupError,
      type: 'inline',
      placeFocusBack: false,
      trapFocus: false,
      autoFocus: false
    }], {
      dragToClose: false
    });
  }
  if (data['responseJSON'] && data['responseJSON']['data']) {
    if (typeof data['responseJSON']['data'] === 'string') {
      form.querySelector('.form__error').textContent = data['responseJSON']['data'];
      form.classList.add('ajax-error');
    } else if (data['responseJSON']['data']['name'] && data['responseJSON']['data']['error']) {
      let errorInput = form.querySelector('[name="' + data['responseJSON']['data']['name'] + '"]');
      if (errorInput && errorInput['setError']) {
        errorInput.setError(data['responseJSON']['data']['error']);
      }
    }
  }
  if (form['ajaxError']) {
    form['ajaxError'](form, data);
  }
}
function onSubmit(form) {
  let formData = new FormData(form);
  let action = form.getAttribute('action') || '/wp-admin/admin-ajax.php';
  let method = form.getAttribute('method') || 'post';
  let formBtn = form.querySelector('[type="submit"]');
  let editors = form.querySelectorAll('.ql-editor');
  if (editors.length) {
    editors.forEach(function (editor) {
      let thisName = editor.closest('[data-name]');
      editor.querySelectorAll('.ql-emojiblot').forEach(function (emoji) {
        emoji.outerHTML = emoji.textContent;
      });
      let thisValue = editor.innerHTML;
      if (!thisName) return;
      thisName = thisName.dataset.name;
      formData.append(thisName, thisValue);
    });
  }
  if (formBtn) formBtn.setAttribute('disabled', 'disabled');
  form.classList.remove('ajax-error');
  $.ajax({
    url: action,
    method: method,
    enctype: 'multipart/form-data',
    processData: false,
    contentType: false,
    cache: false,
    data: formData,
    success: function (data) {
      ajaxSuccess(form, data);
    },
    error: function (data) {
      ajaxError(form, data);
    }
  });
}
function accordion(options) {
  let opts = {
    globalContainer: '.accordion-container',
    containerSelector: '.accordion',
    btnSelector: '.accordion__head',
    dropdownSelector: '.accordion__content',
    timing: 300
  };
  $.extend(opts, options);
  $(opts.containerSelector).each(function () {
    if (!$(this).find(opts.dropdownSelector).length) {
      $(this).addClass('is-empty');
    }
  });
  $(opts.containerSelector + '.is-open').each(function () {
    $(this).find(opts.dropdownSelector).show();
  });
  $(document).on('click', opts.containerSelector + ':not(.is-empty) ' + opts.btnSelector, function () {
    let thisContainer = $(this).closest(opts.containerSelector);
    let accordionsContainer = thisContainer.closest(opts.globalContainer);
    let allAccordions = accordionsContainer.find('.is-open').not(thisContainer);
    let thisContent = thisContainer.find(opts.dropdownSelector);
    if (allAccordions.length) {
      allAccordions.removeClass('is-open');
      allAccordions.find(opts.dropdownSelector).slideUp(300);
    }
    thisContent.slideToggle(300);
    setTimeout(function () {
      thisContainer.toggleClass('is-open');
    }, 1);
  });
}
let hideTimeout = 0;
function headerMessage(type, product) {
  function closeMessages(afterFunction) {
    let waitForHover = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
    let openMessages = $('.header-message');
    if (!openMessages.length) {
      if (typeof afterFunction === 'function') afterFunction();
      return;
    }
    openMessages.each(function () {
      $(this).addClass('is-closing');
      if (!$(this).hasClass('is-hovering') || !waitForHover) {
        $(this).fadeOut(300, function () {
          $(this).remove();
          $('.header').removeClass('header--locked');
          $(document).off('click', closeMessages);
          if (typeof afterFunction === 'function') afterFunction();
        });
      }
    });
  }
  function openMessage(productHtml) {
    let container;
    let header = $('.header');
    if (window.scrollY > header[0].offsetHeight) {
      container = $('.header__fixed ' + ($(window).width() <= 768 ? '.header-mobile' : '.header__container') + ' .header-' + type);
      header.addClass('header--locked');
      if (!header.hasClass('header--fixed')) {
        $(document).trigger('show-fixed-header');
      }
    } else {
      container = $('.header__static ' + ($(window).width() <= 768 ? '.header-mobile' : '.header__container') + ' .header-' + type);
    }
    if (!container.length) {
      return;
    }
    closeMessages(function () {
      let thisMessage = $(`
            <div class="header-message">
                <div class="header-message__title">
                    <span>${container.attr('data-added-title')}</span>
                </div>
                <div class="header-message__content">
                    ${productHtml}
                </div>
                ${product['text'] ? `
                  <div class="header-message__text">
                      ${product['text']}
                  </div>
                ` : ''}
                <div class="header-message__btn">
                    <a href="${container.attr('data-btn-url')}" class="btn btn--white btn--small">
                        <span>${container.attr('data-added-btn')}</span>
                    </a>
                </div>
            </div>
            `);
      container.append(thisMessage);
      thisMessage.fadeIn(300);
      thisMessage.hover(function () {
        $(this).addClass('is-hovering');
      }, function () {
        $(this).removeClass('is-hovering');
        if ($(this).hasClass('can-close')) {
          closeMessages();
        }
      });
      $(document).on('click', closeMessages);
      clearTimeout(hideTimeout);
      hideTimeout = setTimeout(function () {
        thisMessage.addClass('can-close');
        closeMessages();
      }, 3000);
    });
  }
  openMessage(`
    <div class="cart-item cart-item--smallest">
        ${product['img'] ? `
        <div class="cart-item__img">
            <img src="${product['img']}" alt="cart-item">
        </div>
        ` : ''}
        <div class="product-card__content">
            <div class="cart-item__title title-h5">
                <span>${product['title']}</span>
            </div>
            ${product['total'] ? `
            <div class="cart-item__total">
                ${product['total']}
            </div>
            ` : ''}
        </div>
        ${product['quantity'] ? `
        <div class="cart-item__quantity">
            <span>x${product['quantity']}</span>
        </div>
        ` : ''}
    </div>
    `);
  $(document).on('close-messages', closeMessages);
}
function getCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == ' ') c = c.substring(1, c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
  }
  return null;
}
function anchors() {
  $(document).on('click', 'a[href*="#"]', function () {
    let href = $(this).attr("href").split("#").pop();
    if (!href) {
      return;
    }
    let block = $("#" + href);
    if (block.length && typeof $(this).attr('data-fancybox') === 'undefined') {
      $("html, body").animate({
        scrollTop: block.offset().top - 40 - 10 + "px"
      }, {
        duration: 1e3
      });
    }
  });
}
function blocks() {
  let methods = {
    '.header': function (header) {
      let scrollPos = 0;
      let menu = document.querySelector(".menu");
      let catalog = $('.header__menu');
      let catalogMob = $('.menu .header__menu-inner');
      let catalogBtn = $('.header-catalog-btn');
      let catalogBtnMob = $('.header-catalog-btn-mob');
      let catalogSubs = document.querySelector('.header__menu-subs');
      let catalogItems = $('.header__menu-item[data-sub]');
      function open() {
        if (menu.classList.contains('is-active')) return;
        header[0].classList.add('header--fixed');
      }
      function close() {
        if (menu.classList.contains('is-active')) return;
        if (header[0].classList.contains('header--locked')) return;
        header[0].classList.remove('header--fixed');
      }
      let dontRemove = false;
      function alignCatalog() {
        if (!catalog.hasClass('is-open')) return;
        if (window.scrollY > header[0].offsetHeight) {
          if (!header[0].classList.contains('header--fixed')) {
            catalog.css({
              'transition': 'top .3s ease-in-out',
              'top': '0px'
            });
            setTimeout(function () {
              catalog.removeAttr('style');
            }, 300);
            if (!header[0].classList.contains('header--fixed')) open();
          }
        } else {
          if (!dontRemove) catalog.removeAttr('style');
          if (header[0].classList.contains('header--fixed')) {
            close();
            catalog.css({
              'transition': 'top .2s ease-in-out'
            });
            dontRemove = true;
            setTimeout(function () {
              dontRemove = false;
              catalog.removeAttr('style');
              catalog.css('top', -window.scrollY + 'px');
            }, 200);
          }
          catalog.css('top', -window.scrollY + 'px');
        }
      }
      document.addEventListener('scroll', function () {
        if (!catalog.hasClass('is-open')) {
          if (window.scrollY > header[0].offsetHeight && scrollPos > window.scrollY) {
            if (!header[0].classList.contains('header--fixed')) {
              open();
            }
          } else if (header[0].classList.contains('header--fixed')) {
            close();
            $(document).trigger('close-mini-cart');
          }
        }
        scrollPos = window.scrollY;
        alignCatalog();
      });
      document.querySelectorAll('.header__static, .header__fixed, .popup-search').forEach(function (section) {
        let searchBtn = section.querySelector('.header__search-btn');
        let searchForm = section.querySelector('.header__search-form');
        let searchInput = section.querySelector('.header__search-form input');
        let searchList = section.querySelector('.header__search-list>ul');
        function sendSearch() {
          $.ajax({
            url: '/wp-admin/admin-ajax.php',
            method: 'post',
            data: {
              action: 'search',
              search: searchInput.value
            },
            success: function (response) {
              searchList.innerHTML = response;
              searchForm.classList.add('dropdown-open');
            }
          });
        }
        if (searchBtn) searchBtn.addEventListener('click', function () {
          searchForm.classList.add('is-open');
        });
        let searchTimeout = 0;
        $(searchInput).on('click', function () {
          if (searchList.querySelectorAll('.header__search-item').length) {
            searchForm.classList.add('dropdown-open');
          }
        });
        $(searchInput).on('input', function () {
          if (this.value.length < 2) return;
          clearInterval(searchTimeout);
          searchTimeout = setTimeout(function () {
            sendSearch();
          }, 1000);
        });
        if (section.classList.contains('popup-search')) return;
        document.addEventListener('click', function (e) {
          let target = e.target;
          if (!target.closest('.header__search')) {
            searchForm.classList.remove('is-open');
            searchForm.classList.remove('dropdown-open');
          }
        });
      });
      $(document).on('show-fixed-header', open);
      $(document).on('hide-fixed-header', close);
      $(document).on('click', function (e) {
        if (!catalog.hasClass('is-open')) return;
        setTimeout(function () {
          if (!e.target.closest('.header')) {
            catalogBtn.removeClass('is-open');
            catalog.removeClass('is-open');
          }
        }, 50);
      });
      catalogBtn.on('click', function () {
        catalogBtn.toggleClass('is-open');
        catalog.toggleClass('is-open');
      });
      catalogBtnMob.on('click', function () {
        catalogMob.slideToggle(400);
      });
      $('.header__menu-use .section-arrow').on('click', function () {
        let list = $(this).closest('.header__menu-use').find('.header__menu-list');
        list.slideToggle(400);
        $(this).toggleClass('is-open');
      });
      catalogItems.on('click', function (e) {
        let context = $(this).closest('.header__menu-inner');
        let thisSub = context[0].querySelector('.header__menu-sub[data-sub="' + this.dataset['sub'] + '"]');
        if (thisSub) {
          e.preventDefault();
        }
        this.classList.add('is-selected');
        $(this).siblings().removeClass('is-selected');
        catalogSubs = $(thisSub).closest('.header__menu-subs');
        if ($(window).width() <= 768) {
          context.find('.header__menu-subs').not(catalogSubs).css('height', '0px');
          context.find('.header__menu-item.is-selected').not($(this)).removeClass('is-selected');
        }
        $(thisSub).siblings().css('opacity', '0');
        setTimeout(function () {
          $(thisSub).siblings().removeAttr('style');
          setTimeout(function () {
            thisSub.style.display = 'flex';
            catalogSubs[0].style.height = thisSub.clientHeight + 'px';
            setTimeout(function () {
              thisSub.style.opacity = '1';
            }, 10);
          }, 10);
        }, 300);
      });
      alignCatalog();
    },
    '.header-mobile': function (sections) {
      sections.forEach(function (section) {
        let burger = section.querySelector(".burger");
        let menu = document.querySelector(".menu");
        let body = document.querySelector(".page__body");
        let searchBtns = section.querySelectorAll(".header-mobile__search");
        burger.addEventListener('click', function () {
          menu.classList.toggle('is-active');
          burger.classList.toggle('is-active');
          body.classList.toggle('menu-open');
        });
        searchBtns.forEach(function (searchBtn) {
          searchBtn.addEventListener('click', function () {
            Fancybox.show([{
              src: '#popup-search',
              type: 'inline',
              placeFocusBack: false,
              trapFocus: false,
              autoFocus: false
            }], {
              dragToClose: false
            });
          });
        });
      });
    },
    '.search': function (sections) {
      sections.forEach(function (section) {
        let btnPrev = section.querySelector('.search__slider-btn--prev');
        let btnNext = section.querySelector('.search__slider-btn--next');
        let searchSwiper = section.querySelector('.search__slider .swiper');
        new Swiper(searchSwiper, {
          spaceBetween: 16,
          slidesPerView: 1,
          speed: 690,
          loop: true,
          navigation: {
            nextEl: btnNext,
            prevEl: btnPrev
          }
        });
      });
    },
    '.crown-category__container': function (sections) {
      sections.forEach(function (section) {
        if (section.querySelector('.crown-category__info')) {
          section.classList.add('has-info');
        }
      });
    },
    '.quantity': function (sections) {
      let quantTimeout = 0;
      $(document).on('click', '.quantity .quantity__btn', function () {
        const direction = this.getAttribute('data-direction');
        const counterInput = this.closest('.quantity').querySelector('.quantity__input');
        const inputValue = +counterInput.value;
        let inputMin = parseInt(counterInput.getAttribute('min'));
        const inputMax = parseInt(counterInput.getAttribute('max'));
        let newValue;
        let cartItem = $(this).closest('[data-cart-id]');
        let cartId = cartItem.attr('data-cart-id');
        let isWholesale = cartItem.hasClass('product-wholesale');
        if (direction === 'plus') {
          if (!isNaN(inputMax)) {
            newValue = inputValue + 1 <= inputMax ? inputValue + 1 : inputMax;
          } else {
            newValue = inputValue + 1;
          }
        } else {
          if (isNaN(inputMin)) {
            inputMin = 1;
          }
          newValue = inputValue - 1 >= inputMin ? inputValue - 1 : inputMin;
          if (inputValue - 1 < inputMin) {
            return;
          }
        }
        counterInput.value = newValue;
        if (cartId) {
          clearTimeout(quantTimeout);
          cartItem.closest('.cart').find('.cart__bottom .btn').addClass('disabled');
          quantTimeout = setTimeout(function () {
            $.ajax({
              url: '/wp-admin/admin-ajax.php',
              method: 'post',
              data: {
                "action": isWholesale ? 'wholesale_change_quantity' : "change_quantity",
                "cart_id": cartId,
                "quantity": newValue
              },
              success: function (response) {
                let cartHtml = response['data']['html'];
                let cartContainer = cartItem.closest('.cart__container');
                if (cartContainer.length) {
                  cartContainer.find('.cart__body').remove();
                  cartContainer.find('.cart__bottom').remove();
                  cartContainer.find('.cart__title').after(cartHtml);
                }
              }
            });
          }, 500);
        }
      });
    },
    '.product-card': function (cards) {
      let maxZ = 999;
      cards.forEach(function (card) {
        card.style.zIndex = maxZ;
        maxZ--;
      });
      $(document).on('click', '.js-add-favorite:not(.loading)', function (e) {
        e.preventDefault();
        let thisBtn = $(this);
        let isLiked = thisBtn.hasClass('is-added');
        let isUnavailable = thisBtn.hasClass('is-unavailable');
        let productId = thisBtn.attr('data-product-id') || thisBtn.closest('[data-product-id]').attr('data-product-id');
        if (!productId) return;
        thisBtn.addClass('loading');
        $.ajax({
          url: '/wp-admin/admin-ajax.php',
          method: 'post',
          data: {
            "action": "like_product",
            "is_liked": !isLiked,
            "product_id": productId
          },
          success: function () {
            let thisCard = thisBtn.closest('.product-card');
            thisBtn.toggleClass('is-added');
            thisBtn.removeClass('loading');
            if (!isLiked) {
              let product;
              let container = thisBtn.closest('.product-card');
              if (thisBtn.attr('data-text-added')) {
                thisBtn.find('span').text(thisBtn.attr('data-text-added'));
              }
              if (container.length) {
                product = {
                  img: container.find('.product-card__img img').attr('src'),
                  title: container.find('.product-card__title').text().trim(),
                  text: isUnavailable ? validationErrors['favorites_unavailable'] : ''
                };
              } else {
                container = thisBtn.closest('.product');
                product = {
                  img: container.find('.product__gallery-main__item img').eq(0).attr('src'),
                  title: container.find('.product__form .product__title').text().trim(),
                  text: isUnavailable ? validationErrors['favorites_unavailable'] : ''
                };
              }
              headerMessage('favorite', product);
            } else {
              if (thisBtn.attr('data-text-added')) {
                thisBtn.find('span').text(thisBtn.attr('data-text-add'));
              }
              if (thisBtn.closest('.remove-cards').length) {
                thisCard.css({
                  'transition': 'none'
                });
                thisCard.fadeOut(400, function () {
                  thisCard.remove();
                });
              }
            }
          },
          error: function () {
            console.log('error', thisBtn);
            thisBtn.removeClass('loading');
          }
        });
      });
    },
    '.product': function (sections) {
      sections.forEach(function (section) {
        let sliderThumbsEl = section.querySelector('.swiper-thumbs');
        let sliderImagesEl = section.querySelector('.swiper-images');
        let btnPrev = section.querySelector('.arr-prev-next');
        let btnNext = section.querySelector('.arr-next-next');
        let btnPrev1 = section.querySelector('.arr-prev-main');
        let btnNext1 = section.querySelector('.arr-next-main');
        const sliderThumbs = new Swiper(sliderThumbsEl, {
          slidesPerView: 3,
          spaceBetween: 10,
          navigation: {
            nextEl: btnNext,
            prevEl: btnPrev
          }
        });
        const sliderImages = new Swiper(sliderImagesEl, {
          slidesPerView: 1,
          spaceBetween: 32,
          navigation: {
            nextEl: btnNext1,
            prevEl: btnPrev1
          },
          thumbs: {
            swiper: sliderThumbs
          }
        });
      });
    },
    '.page-promo': function (sections) {
      sections.forEach(function (section) {
        let bgSlider = section.querySelector('.page-promo__bg-slider .swiper');
        let contentSlider = section.querySelector('.page-promo__container .swiper');
        if (bgSlider) {
          let defaultSpeed = 3000;
          let firstInit = true;
          let contentSwiper = new Swiper(contentSlider, {
            slidesPerView: 1,
            spaceBetween: 0,
            // loop: true,
            speed: 500,
            allowTouchMove: false,
            grabCursor: false,
            effect: "creative",
            creativeEffect: {
              prev: {
                opacity: 0,
                translate: ["0%", 0, 0]
              },
              next: {
                opacity: 0,
                translate: [0, "50px", 0]
              }
            },
            navigation: {
              nextEl: '.swiper-button-next',
              prevEl: '.swiper-button-prev'
            },
            autoplay: {
              delay: defaultSpeed,
              disableOnInteraction: false
            },
            pagination: {
              el: '.swiper-pagination',
              clickable: true
            },
            on: {
              afterInit: function (swiper) {
                setTimeout(function () {
                  contentSlider.querySelector('.swiper-pagination').classList.add('can-play');
                }, 200);
              }
            }
          });
          let bgSwiper = new Swiper(bgSlider, {
            slidesPerView: 1,
            spaceBetween: 0,
            speed: 800,
            allowTouchMove: false,
            effect: "creative",
            creativeEffect: {
              prev: {
                opacity: 0
              },
              next: {
                opacity: 0
              }
            }
          });
          contentSwiper.controller.control = bgSwiper;
          function checkForVideo() {
            let activeSlide = $(contentSwiper.controller.control.slides[contentSwiper.controller.control.activeIndex]);
            let thisVideo = activeSlide.find('video');
            let slideDuration = defaultSpeed;
            if (firstInit) {
              slideDuration -= 200;
              firstInit = false;
            }
            if (!thisVideo.length) {
              $(bgSlider).find('.swiper-slide').removeClass('is-playing');
              contentSwiper.params.autoplay.delay = slideDuration;
              $(contentSlider).find('.swiper-pagination-bullet-active').css('transition-duration', slideDuration + 'ms');
              return;
            }
            if (activeSlide.hasClass('is-playing')) return;
            slideDuration = thisVideo[0].duration * 1000 - 500;
            if (firstInit) {
              slideDuration -= 200;
              firstInit = false;
            }
            thisVideo[0].currentTime = 0;
            contentSwiper.params.autoplay.delay = slideDuration;
            $(contentSlider).find('.swiper-pagination-bullet-active').css('transition-duration', slideDuration + 'ms');
            thisVideo[0].play();
            activeSlide.addClass('is-playing');
          }
          contentSlider.classList.add('is-started');
          checkForVideo();
          contentSwiper.on('transitionStart', function () {
            // setTimeout(function () {
            checkForVideo();
            // }, 200)
          });
        }
      });
    },

    '.cart': function (cart) {
      cart = $(cart);
      let cartItemTotals = cart.find('.cart-item__total');
      let setWidthTimeout = 0;
      function alignCartTotal() {
        let maxTotalWidth = 0;
        cartItemTotals.removeAttr('style');
        if ($(window).width() > 768) {
          cartItemTotals.each(function () {
            if ($(this).width() > maxTotalWidth) {
              maxTotalWidth = $(this).width();
            }
          });
          clearTimeout(setWidthTimeout);
          setWidthTimeout = setTimeout(function () {
            cartItemTotals.css('width', maxTotalWidth);
          }, 10);
        }
      }
      if ($('.cart__form').length) {
        validate('.cart__form', {
          submitFunction: onSubmit
        });
      }
      setTimeout(function () {
        alignCartTotal();
      }, 1000);
      $(window).on('resize', alignCartTotal);
      $(document).on('click', '.cart-item__close', function () {
        let thisBtn = $(this);
        let thisItem = thisBtn.closest('[data-cart-id]');
        let thisId = thisItem.attr('data-cart-id');
        let isWholesale = thisItem.hasClass('product-wholesale');
        cart.find('.cart__bottom .btn').addClass('disabled');
        $.ajax({
          url: '/wp-admin/admin-ajax.php',
          method: 'post',
          data: {
            "action": isWholesale ? "wholesale_delete_item" : "delete_item",
            "cart_id": thisId
          },
          success: function (response) {
            let cartHtml = response['data']['html'];
            let cartContainer = thisBtn.closest('.cart__container');
            if (cartContainer.length) {
              cartContainer.find('.cart__body').remove();
              cartContainer.find('.cart__bottom').remove();
              cartContainer.find('.cart__title').after(cartHtml);
            }
            cartItemTotals = cart.find('.cart-item__total');
            if (!$('.cart-item').length) {
              if (isWholesale) {
                $('.header-wholesale').addClass('wholesale-empty');
              } else {
                $('.header-regular').addClass('cart-empty');
              }
            }
          }
        });
      });
    },
    '.mini-cart': function (carts) {
      function close(after) {
        document.querySelectorAll('.mini-cart-target').forEach(function (item) {
          item.classList.remove('mini-cart-target');
        });
        carts.forEach(function (thisCart) {
          thisCart.classList.remove('is-open');
          fadeOut(thisCart, 300, function () {
            document.querySelector('body').append(thisCart);
            if (typeof after === 'function') {
              after();
            }
          });
        });
      }
      function open(thisBtn) {
        let miniType = thisBtn.dataset.showMiniCart || 'regular';
        let thisMiniCart = document.querySelector('.mini-cart--' + miniType);
        if (!thisMiniCart) return;
        thisBtn.classList.add('mini-cart-target');
        thisBtn.append(thisMiniCart);
        thisMiniCart.classList.add('is-open');
        fadeIn(thisMiniCart, 300);
      }
      function updateCart(cartData, type) {
        let thisMiniCart = document.querySelector('.mini-cart--' + type);
        let miniCartList = thisMiniCart.querySelector('.mini-cart__list') || thisMiniCart.querySelector('.mini-cart__list-not-empty');
        let miniCartListTotal = thisMiniCart.querySelector('.mini-cart__total .product-card__price--new');
        let newHtml = '';
        if (cartData['cart']) {
          cartData['cart'].forEach(function (cartItem) {
            newHtml += `
                    <div class="cart-item cart-item--smallest">
                        ${cartItem['thumbnail_url'] ? `
                          <a href="${cartItem['permalink']}" class="cart-item__img">
                              <img src="${cartItem['thumbnail_url']}" alt="cart-item">
                          </a>
                        ` : ''}
                        <div class="product-card__content">
                            <a href="${cartItem['permalink']}" class="cart-item__title title-h5">
                                <span>${cartItem['title']}</span>
                            </a>
                            <div class="cart-item__total">
                                ${cartItem['price']}
                            </div>
                        </div>
                        <div class="cart-item__quantity">
                            <span>x${cartItem['quantity']}</span>
                        </div>
                    </div>
                    `;
          });
        } else if (cartData['liked_text']) {
          newHtml = cartData['liked_text'];
        }
        if (cartData['cart'] && cartData['cart'].length || cartData['liked_text']) {
          thisMiniCart.classList.remove('cart-empty');
          $('.header-' + type).removeClass('cart-empty');
        } else {
          thisMiniCart.classList.add('cart-empty');
          $('.header-' + type).addClass('cart-empty');
        }
        if (miniCartList) {
          miniCartList.innerHTML = newHtml;
        }
        if (miniCartListTotal) {
          miniCartListTotal.innerHTML = cartData['cart_total'];
        }
      }
      $(document).ajaxSuccess(function (_, response, info) {
        let action = info['data'];
        response = response['responseJSON'];
        if (!action || !response) return;
        if (typeof action === 'string') {
          action = action.split('&').find(param => param.indexOf('action=') === 0);
        } else if (typeof action === 'object') {
          action = action['action'];
        } else {
          return;
        }
        response = response['data'];
        if (!action || !response) return;
        if (action.indexOf('action=') === 0) {
          action = action.replace('action=', '');
        }
        if (!action) return;
        if (action === 'add_to_cart' || action === 'delete_item' || action === 'change_quantity') {
          if (response['cart']) {
            updateCart(response, 'regular');
          }
        } else if (action === 'add_to_cart_wholesale' || action === 'wholesale_delete_item' || action === 'wholesale_change_quantity') {
          if (response['cart']) {
            updateCart(response, 'wholesale');
          }
        } else if (action === 'like_product') {
          updateCart(response, 'favorite');
        }
      });
      $(document).on('click', '[data-show-mini-cart]', function (e) {
        if (e.target.closest('.mini-cart') || e.target.closest('.header-message')) {
          return;
        }
        let thisBtn = this;
        let openCarts = Array.from(carts).filter(cart => cart.classList.contains('is-open'));
        if (!thisBtn.classList.contains('mini-cart-target')) {
          $(document).trigger('close-messages', [false]);
          if (openCarts.length) {
            close(function () {
              open(thisBtn);
            });
          } else {
            open(thisBtn);
          }
        } else {
          close();
        }
      });
      $(document).on('click', function (e) {
        if (e.target.closest('[data-show-mini-cart]') || e.target.getAttribute('data-show-mini-cart') !== null) {
          return;
        }
        close();
      });
      $(document).on('close-mini-cart', close);
    },
    '.form-row': function (rows) {
      rows.forEach(function (row, i) {
        row.style.position = 'relative';
        row.style.zIndex = rows.length - i;
      });
    },
    '.contact': function () {
      $(document).on('click', '.contact__btn .btn', function (e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
      });
    },
    '.orders__accordion': function (sections) {
      sections.forEach(function (section) {
        const accordions = section.querySelectorAll('.accordion');
        accordions.forEach(function (accordion) {
          const head = accordion.querySelector('.accordion__head');
          const btn = accordion.querySelector('.accordion__btn span');
          head.addEventListener('click', function (e) {
            const self = this.closest('.accordion');
            const content = self.querySelector('.accordion__body');
            let openText = self.querySelector('.info-open');
            let closedText = self.querySelector('.info-closed');
            self.classList.toggle('open');
            if (self.classList.contains('open')) {
              content.style.display = 'block';
              content.style.maxHeight = content.scrollHeight + 'px';
              btn.textContent = 'Згорнути';
              fadeOut(closedText, 300, function () {
                fadeIn(openText, 300, 'flex');
              });
            } else {
              content.style.maxHeight = null;
              btn.textContent = 'Розгорнути';
              fadeOut(openText, 300, function () {
                content.style.display = 'none';
                fadeIn(closedText, 300, 'flex');
              });
            }
          });
        });
      });
    },
    '.main-banner': function () {
      if ($(window).width() <= 768) return;
      $('.main-banner__item').hover(function () {
        $(this).css({
          'z-index': '2',
          'overflow': 'visible'
        });
        $(this).find('.title-link .btn').css('width', $(this).find('.title-link .btn>*:not(img)').width() + 60);
      }, function () {
        let $this = $(this);
        $this.find('.title-link .btn').removeAttr('style');
        setTimeout(function () {
          $this.removeAttr('style');
        }, 300);
      });
    },
    '.image-parallax': function (elements) {
      function getElementsPaddings(el) {
        let isAtTop = false;
        let lastEl = el.parentElement;
        let prevEl = el;
        let paddings = 0;
        while (!isAtTop) {
          if (lastEl.nodeName !== 'BODY') {
            if (getComputedStyle(prevEl)['position'] !== 'absolute') {
              paddings += +getComputedStyle(lastEl)['paddingTop'].split('px')[0];
            }
            prevEl = lastEl;
            lastEl = lastEl.parentElement;
          } else {
            isAtTop = true;
          }
        }
        console.log(paddings);
        return paddings;
      }
      setTimeout(function () {
        elements.forEach(function (el) {
          let sens = +el.getAttribute('data-sens');
          let inner = el.querySelector('.image-parallax__img');
          let elOfTop = $(el).offset().top - getElementsPaddings(el);
          let startFrom = 0;
          let addedHeight = Math.abs(window.innerHeight * sens);
          el.style.height = el.clientHeight + 'px';
          inner.style.height = inner.clientHeight + addedHeight + 'px';
          if (window.innerHeight * sens < 0) {
            startFrom = addedHeight;
            inner.style.transform = 'translateY(-' + addedHeight + 'px)';
          }
          function onScroll() {
            let top = el.getBoundingClientRect().top;
            let height = el.offsetHeight;
            let wHeight = window.innerHeight;
            if (top + height > 0 && top < wHeight) {
              inner.style.transform = 'translateY(' + ((top - elOfTop) * sens - startFrom) + 'px)';
            }
          }
          $(window).on('resize', function (e) {
            el.removeAttribute('style');
            inner.removeAttribute('style');
            elOfTop = $(el).offset().top - getElementsPaddings(el);
            startFrom = 0;
            addedHeight = Math.abs(window.innerHeight * sens);
            el.style.height = el.clientHeight + 'px';
            inner.style.height = inner.clientHeight + addedHeight + 'px';
            if (window.innerHeight * sens < 0) {
              startFrom = addedHeight;
              inner.style.transform = 'translateY(-' + addedHeight + 'px)';
            }
            onScroll();
          });
          $(window).on('scroll', onScroll);
        });
      }, 500);
    },
    '.product__options--colors input': function (elements) {
      function setRadioWidth(thisInput) {
        let animate = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : true;
        let thisBtn = thisInput.closest('.input--radio').find('.input--radio__btn');
        thisBtn.closest('form').find('[name="' + thisInput[0].name + '"]').closest('.input--radio').find('.input--radio__btn').removeAttr('style');
        if (!animate) {
          thisBtn.css({
            'transition': 'none',
            'width': thisBtn[0].scrollWidth
          });
          return;
        }
        thisBtn.css('width', thisBtn[0].scrollWidth);
      }
      setRadioWidth($('.product__options--colors input:checked'), false);
      $('.product__options--colors .input--radio').on('click', function () {
        setRadioWidth($(this).find('input'));
      });
    },
    '.product__variants': function (variants) {
      let inputsContainers = $(variants).find('>*');
      let btnsContainers = $(variants).find('.product__variants-btns');
      let inputs = $(variants).find('.input--radio>input');
      let inputDefault = $(variants).find('.input--radio>input:checked');
      btnsContainers.each(function () {
        let thisContainer = $(this).closest('li');
        if (thisContainer.hasClass('status-wait') || thisContainer.hasClass('status-not-available')) {
          $(this).find('.product__btn-buy').hide();
          $(this).find('.product__btn-message').css('display', 'inline-flex').hide().show();
        }
      });
      function selectType(input) {
        let thisContainer = input.closest('li');
        let btnsContainer = thisContainer.find('.product__variants-btns');
        inputsContainers.removeClass('is-selected');
        thisContainer.addClass('is-selected');
        btnsContainers.not(btnsContainer).fadeOut(300);
        btnsContainer.fadeIn(300);
        inputsContainers.not(thisContainer).find('.quantity').fadeOut(300, function () {
          inputsContainers.not(thisContainer).find('.product__status').fadeIn(300);
        });
        if (thisContainer.hasClass('status-available')) {
          thisContainer.find('.product__status').fadeOut(300, function () {
            thisContainer.find('.quantity').css('display', 'flex').hide().fadeIn(300);
          });
        }
      }
      if (inputDefault.is(':checked')) {
        selectType(inputDefault);
      }
      inputs.on('change', function () {
        if ($(this).is(':checked')) {
          selectType($(this));
        }
      });
    },
    '[data-product-id]': function () {
      $(document).on('click', '.product__btn-buy', function () {
        let thisBtn = $(this);
        let thisId = thisBtn.closest('[data-product-id]').attr('data-product-id');
        let thisContainer = thisBtn.closest('li');
        let variants = thisBtn.closest('.product__variants');
        let productContainer = thisBtn.closest('.product__container');
        if (!thisContainer.length) {
          thisContainer = thisBtn.closest('.product-card');
          thisContainer.addClass('is-loading');
        }
        if (!thisContainer.length) {
          return;
        }
        let isWholesale = thisContainer.hasClass('product-wholesale');
        let thisQuantity = thisContainer.find('.quantity__input').val();
        variants.addClass('is-loading');
        thisBtn.attr('disabled', 'disabled');
        $.ajax({
          url: '/wp-admin/admin-ajax.php',
          method: 'post',
          data: {
            action: isWholesale ? 'add_to_cart_wholesale' : 'add_to_cart',
            product_id: thisId,
            quantity: thisQuantity
          },
          success: function (response) {
            variants.removeClass('is-loading');
            thisContainer.removeClass('is-loading');
            thisBtn.removeAttr('disabled');
            let product;
            if (productContainer.length) {
              product = {
                img: productContainer.find('.product__gallery-main__item img').eq(0).attr('src'),
                title: productContainer.find('.product__title').eq(0).text().trim(),
                quantity: thisQuantity,
                total: thisContainer.find('.product-card__price--new').text()
              };
            } else {
              product = {
                img: thisContainer.find('.product-card__img img').attr('src'),
                title: thisContainer.find('.product-card__title').text().trim(),
                quantity: thisQuantity,
                total: thisContainer.find('.product-card__price--new').text()
              };
            }
            if (isWholesale) {
              $('.header-wholesale').removeClass('wholesale-empty');
            } else {
              $('.header-regular').removeClass('cart-empty');
            }
            headerMessage(isWholesale ? 'wholesale' : 'regular', product);
          },
          error: function (response) {
            $(variants).removeClass('is-loading');
          }
        });
      });
    },
    '.input ': function (inputs) {
      function inputUpload() {
        let thisInput = $(this);
        let fileTypes = false;
        if (thisInput[0].hasAttribute('accept') && thisInput.attr('accept')) {
          fileTypes = thisInput.attr('accept').replaceAll(' ', '').split(',');
        }
        let maxFileSize = parseInt(thisInput.attr('data-maxsize')) * 1024 * 1024;
        let isValid = false;
        let files = this.files;
        let thisContainer = thisInput.closest('.input');
        let resultBlock = thisContainer.find('.input__result');
        let fileName = '';
        let thisName = files[0].name.split('.');
        let thisExt = thisName[thisName.length - 1];
        thisName.pop();
        if (thisName.length > 1) {
          thisName = thisName.join('.');
        } else {
          thisName = thisName[0];
        }
        if (thisName.length > 22) thisName = thisName.substring(0, 22) + '... ';
        fileName = thisName + '.' + thisExt;
        if (fileTypes) {
          $.each(fileTypes, function (_, type) {
            if (type === '.' + thisExt) isValid = true;
          });
        } else {
          isValid = true;
        }
        if (!isValid) {
          resultBlock.html(validationErrors['allowed_ext'] + fileTypes.join(', '));
          thisInput.val('');
          thisContainer.removeClass('upload-success');
          thisContainer.addClass('is-error');
          return false;
        }
        if (files[0].size > maxFileSize) {
          isValid = false;
          resultBlock.html(validationErrors['max_size'] + maxFileSize / 1024 / 1024 + 'mb');
          thisInput.val('');
          thisContainer.removeClass('upload-success');
          thisContainer.addClass('is-error');
          return false;
        }
        thisContainer.addClass('upload-success').removeClass('is-error');
        resultBlock.text(fileName);
      }
      dropdown({
        containerClass: 'input--select',
        btnSelector: '.output_text',
        closeBtnClass: '',
        dropdownSelector: 'ul',
        effect: 'fade',
        timing: 200
      });
      function selectMobVariation(e) {
        let option = $(this);
        if (!e['originalEvent']) option = e;
        let text = option.text().trim();
        let value = option.attr('data-value');
        let container = option.closest('.input--select');
        let outText = container.find('.output_text');
        let outValue = container.find('.output_value');
        if (outText.length) {
          if (outText.is('input')) {
            outText.val(text).trigger('input');
          } else {
            outText.text(text).trigger('input');
          }
        }
        if (outValue.length) {
          if (outValue.is('input')) {
            outValue.val(value).trigger('input');
          } else {
            outValue.text(value).trigger('input');
          }
        }
        container.removeClass('is-error');
        option.addClass('is-selected');
        option.siblings().removeClass('is-selected');
      }
      $(document).on('click', '.input--select li', selectMobVariation);
      $(' .input--select .is-selected').each(function () {
        selectMobVariation($(this));
      });
      $(document).on('change', '.input.input--file input', inputUpload);
    },
    '.dropdown': function () {
      const btn = document.querySelector('.dropdown-click');
      const dropDown = document.querySelector('.dropdown-click ul');
      if (btn) {
        btn.addEventListener('click', function () {
          dropDown.classList.toggle('active');
        });
      }
      document.addEventListener('click', function (item) {
        if (!item.target.closest('.dropdown-click')) {
          if (dropDown) {
            dropDown.classList.remove('active');
          }
        }
      });
    },
    '.foropt-find__form': function () {
      validate('.foropt-find__form', {
        submitFunction: onSubmit
      });
    },
    '.register__form': function () {
      validate('.register__form', {
        submitFunction: onSubmit
      });
    },
    '.login__form': function () {
      validate('.login__form', {
        submitFunction: onSubmit
      });
    },
    '#contact-popup': function () {
      validate('#contact-popup form', {
        submitFunction: onSubmit
      });
    },
    '.search__form': function () {
      validate('.search__form', {
        submitFunction: onSubmit
      });
    },
    '.account__form': function () {
      let section = $('.account__form');
      let passwordInputs = section.find('input[type="password"]');
      passwordInputs.on('input', function (e) {
        let allEmpty = true;
        passwordInputs.not('[data-validation="old_password"]').each(function () {
          if ($(this).val().length) allEmpty = false;
        });
        if (allEmpty) passwordInputs.each(function () {
          if (this.isRequired()) {
            this.removeRequired();
            $(this).closest('.input').removeClass('is-error');
          }
        });else passwordInputs.each(function () {
          if (!this.isRequired()) this.setRequired();
        });
      });
      validate('.account__form', {
        submitFunction: onSubmit
      });
    },
    '.reset-pass-1': function () {
      validate('.reset-pass-1', {
        submitFunction: onSubmit
      });
    },
    '.reset-pass-2': function () {
      validate('.reset-pass-2', {
        submitFunction: onSubmit
      });
    },
    '.checkout.woocommerce-checkout': function () {
      let form = '.checkout.woocommerce-checkout';
      let $form = $(form);
      let wooErrors = $('.woocommerce-error>*');
      if (wooErrors.length) {
        wooErrors.each(function () {
          if (!$(this).attr('data-id')) return;
          let errorInput = form.find('#' + $(this).attr('data-id'));
          let errorText = $(this).text().trim();
          errorInput[0].setError(errorText);
        });
      }
      function setPaymentText(paymentInput) {
        let thisLabel = paymentInput.closest('.input').find('label').text();
        $form.find('.js-payment-method').text(thisLabel);
      }
      function setCouponError() {
        if (!document.querySelector('.checkout_coupon')) {
          return;
        }
        validate('.checkout_coupon', {
          submitFunction: function (form) {
            let thisBtn = form.querySelector('[type="submit"]');
            let couponCode = form.querySelector('[name="coupon_code"]');
            if (!couponCode.value) {
              setTimeout(function () {
                couponCode.setError(validationErrors.required);
              }, 300);
              return;
            } else {
              couponCode.removeError();
            }
            thisBtn.setAttribute('disabled', 'disabled');
            $.ajax({
              url: '/wp-admin/admin-ajax.php',
              method: 'post',
              data: {
                action: 'is_coupon_valid',
                coupon_code: couponCode.value
              },
              success: function (data) {
                if (typeof data['data']['errors'] !== 'undefined') {
                  if ($('.checkout__code-error').length) {
                    $('.checkout__code-error').html(data['data']['errors']['invalid_coupon']);
                  } else {
                    $('.checkout__code').append('<div class="checkout__code-error">' + data['data']['errors']['invalid_coupon'] + '</div>');
                  }
                  thisBtn.removeAttribute('disabled', 'disabled');
                } else {
                  $('.checkout__code-error').remove();
                  let currentLocale = '/';
                  if (getCookie('pll_language') != 'uk') {
                    currentLocale = '/' + getCookie('pll_language') + '/';
                  }
                  $.ajax({
                    url: currentLocale + '?wc-ajax=apply_coupon',
                    method: 'post',
                    data: {
                      security: wc_checkout_params.apply_coupon_nonce,
                      coupon_code: couponCode.value
                    },
                    success: function (data) {
                      jQuery(document.body).trigger('update_checkout');
                    },
                    error: function (data) {
                      ajaxError(form, data);
                    }
                  });
                }
              },
              error: function (data) {
                ajaxError(form, data);
              }
            });
          }
        });
      }
      $(document).on('click', '.woocommerce-form-coupon-toggle .show-coupon', function (e) {
        e.preventDefault();
        let thisBtn = $(this);
        let btnContainer = thisBtn.closest('.woocommerce-form-coupon-toggle');
        let isToggled = !btnContainer.hasClass('is-open');
        if (isToggled) {
          btnContainer.toggleClass('is-open');
        }
        if (!thisBtn.hasClass('is-clicked')) {
          thisBtn.text(thisBtn.attr('data-text-close'));
        } else {
          thisBtn.text(thisBtn.attr('data-text-open'));
        }
        thisBtn.toggleClass('is-clicked');
        setTimeout(function () {
          thisBtn.closest('.checkout__code').find('.checkout_coupon').slideToggle(300, function () {
            if (!isToggled) {
              btnContainer.toggleClass('is-open');
            }
          });
        }, 50);
      });
      $(document).on('change', 'input[name="payment_method"]', function () {
        setPaymentText($(this));
      });
      $(document).on('set-payment-text', function () {
        setCouponError();
        setPaymentText($('input[name="payment_method"]:checked'));
        if ($('[value*="local_pickup"]')[0].checked) {
          $('.wcus-checkout-field-local').show();
        } else {
          $('.wcus-checkout-field-local').hide();
        }
      });
      let waitForPayment = setInterval(function () {
        if ($('input[name="payment_method"]:checked').length) {
          clearInterval(waitForPayment);
          setPaymentText($('input[name="payment_method"]:checked'));
        }
      }, 50);
      setTimeout(function () {
        clearInterval(waitForPayment);
      }, 3000);
      $(document).on('click', '.checkout__btn button, .checkout__mobile-btn button', function (e) {
        e.preventDefault();
        let isValid = $form[0].valid(true);
        let thisBtn = this;
        if (isValid) {
          let formData = new FormData($form[0]);
          let currentLocale = '/';
          if (getCookie('pll_language') != 'uk') {
            currentLocale = '/' + getCookie('pll_language') + '/';
          }
          thisBtn.setAttribute('disabled', 'disabled');
          $.ajax({
            url: currentLocale + '?wc-ajax=checkout',
            method: 'post',
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            data: formData,
            success: function (data) {
              thisBtn.removeAttribute('disabled');
              if (typeof data['result'] !== 'undefined') {
                if (data['result'] === 'failure') {
                  if (data['messages']) {
                    let errorText = $(data['messages']).text().trim();
                    if (errorText.indexOf('електронну адресу') > -1 || errorText.indexOf('email') > -1) {
                      $form.find('[name="billing_email"]')[0].setError(errorText);
                    }
                    if ($(data['messages']).find('[data-id]').length) {
                      $(data['messages']).find('[data-id]').each(function () {
                        let errorEl = $(this);
                        let errorInput = $form.find('[name="' + errorEl.attr('data-id') + '"]');
                        if (errorInput.length) {
                          errorInput[0].setError(errorEl.text().trim());
                        }
                      });
                    }
                  }
                } else if (data['result'] === 'success') {
                  if (data['redirect']) {
                    document.location.href = data['redirect'];
                  }
                }
              }
            },
            error: function (data) {
              ajaxError(form, data);
            }
          });
        }
      });
      $(document).on('click', '.shop_table', function (e) {
        if (!e.originalEvent || !$('.shop_table').hasClass('is-expanded')) {
          return;
        }
        console.log($(window).height() - $('.shop_table').height());
        if ($(window).width() > 768 || e.originalEvent.clientY > $(window).height() - $('.shop_table').outerHeight()) {
          return;
        }
        $('.js-expand-mobile-order').first().click();
      });
      $(document).on('click', '.js-expand-mobile-order', function (e) {
        e.preventDefault();
        let shopTable = $('.shop_table');
        let checkoutMobile = $('.checkout__mobile');
        let checkoutReview = $('.woocommerce-checkout-review-order');
        if (shopTable.hasClass('is-expanded')) {
          let startHeight = shopTable[0].offsetHeight + 'px';
          shopTable[0].style.transitionDuration = '0s';
          shopTable[0].style.height = startHeight;
          shopTable.removeClass('is-expanded');
          checkoutReview.fadeOut(300, function () {
            checkoutMobile[0].style.display = 'block';
            shopTable[0].style.height = 'auto';
            let scrollHeight = shopTable[0].offsetHeight;
            checkoutMobile[0].style.display = 'none';
            shopTable[0].style.height = startHeight;
            shopTable[0].style.transitionDuration = '';
            shopTable[0].style.transitionDelay = '0s';
            setTimeout(function () {
              shopTable[0].style.height = scrollHeight + 'px';
              setTimeout(function () {
                checkoutMobile.fadeIn(300, function () {
                  setTimeout(function () {
                    shopTable[0].style.height = 'auto';
                    shopTable[0].style.transitionDelay = '';
                  }, 300);
                });
              }, 300);
            }, 10);
          });
        } else {
          let startHeight = shopTable[0].offsetHeight + 'px';
          shopTable[0].style.transitionDuration = '0s';
          shopTable[0].style.height = startHeight;
          shopTable.addClass('is-expanded');
          checkoutMobile.fadeOut(300, function () {
            checkoutReview[0].style.display = 'block';
            shopTable[0].style.height = 'auto';
            let scrollHeight = shopTable[0].offsetHeight;
            checkoutReview[0].style.display = 'none';
            shopTable[0].style.height = startHeight;
            shopTable[0].style.transitionDuration = '';
            setTimeout(function () {
              shopTable[0].style.height = scrollHeight + 'px';
              setTimeout(function () {
                checkoutReview.fadeIn(300, function () {
                  setTimeout(function () {
                    shopTable[0].style.height = 'auto';
                  }, 300);
                });
              }, 300);
            }, 10);
          });
        }
      });
      validate('.checkout.woocommerce-checkout', {
        submitFunction: function () {}
      });
      setCouponError();
      setTimeout(function () {
        let np_city = $('[name="wcus_np_billing_city"]')[0];
        let np_warehouse = $('[name="wcus_np_billing_warehouse"]')[0];
        let np_address = $('[name="wcus_np_billing_custom_address"]')[0];
        if (np_city) {
          $form[0].addInput(np_city);
          np_city.setRequired();
        }
        if (np_warehouse) {
          $form[0].addInput(np_warehouse);
          np_warehouse.setRequired();
        }
        if (np_address) {
          $form[0].addInput(np_address);
          np_address.setRequired();
        }
      }, 100);
    },
    '.product-list': function () {
      let productList = $('.product-list');
      let productAside = $('.product-list__aside');
      let productSliders = $('.product-list__filter-slider');
      let updateTimeout = 0;
      function selectFilter() {
        let filterCheckboxes = $('.product-list__filters [type="checkbox"]:checked, .product-list__filter-output');
        let selectedOutput = $('.product-list__selected-inner');
        selectedOutput.html('');
        selectedOutput.parent().removeClass('is-visible');
        filterCheckboxes.each(function () {
          let thisInput = $(this);
          let thisContainer = thisInput.closest('.input');
          let sliderContainer = thisInput.closest('.product-list__filter-output');
          if (sliderContainer.length) {
            let sliderInputs = sliderContainer.find('input[type="hidden"][name]');
            let allDefault = true;
            sliderInputs.each(function () {
              let numValue = parseInt(this.value);
              if (!isNaN(numValue) ? numValue !== parseInt(this.dataset.default) : this.value !== this.dataset.default) {
                allDefault = false;
              }
            });
            if (allDefault) {
              return;
            } else {
              thisContainer = sliderContainer;
            }
          }
          let btnTemplate = $(`<span class="btn btn--gray btn--smallest js-selected-btn" data-name="${thisInput.attr('name') || thisInput.find('input').eq(0).attr('name')}" data-value="${thisInput.attr('value') || thisInput.find('input').eq(0).attr('value')}">${thisContainer.text().trim()} <svg width="8" height="9" viewBox="0 0 8 9" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1.5L7 7.5M7 1.5L1 7.5" stroke="black" stroke-width="2"/></svg></span>`);
          selectedOutput.append(btnTemplate);
          selectedOutput.parent().addClass('is-visible');
        });
      }
      function removeFilter() {
        let thisBtn = $(this).closest('.js-selected-btn');
        let filterName = thisBtn.attr('data-name');
        let filterValue = thisBtn.attr('data-value');
        let selectedOutput = $('.product-list__selected-inner');
        let thisFilter = $(`.product-list__filters [name="${filterName}"]`);
        if (thisFilter.length > 1) {
          thisFilter = $(`.product-list__filters [name="${filterName}"][value="${filterValue}"]`);
        }
        let isSlider = thisFilter[0].closest('.product-list__filter-output');
        thisBtn.remove();
        if (isSlider) {
          let sliderInputs = $(isSlider).find('input[type="hidden"][name]');
          sliderInputs.each(function () {
            this.setSliderVal(this.dataset.default);
          });
        } else {
          thisFilter.prop('checked', false);
        }
        if (!selectedOutput.find('>*').length) {
          selectedOutput.parent().removeClass('is-visible');
        }
        productList.trigger('update_filters', [0]);
      }
      function removeFilters() {
        let filterCheckboxes = $('.product-list__filters [type="checkbox"]:checked');
        let selectedOutput = $('.product-list__selected-inner');
        let productSliders = $('.product-list__filter-output');
        selectedOutput.html('');
        selectedOutput.parent().removeClass('is-visible');
        filterCheckboxes.each(function () {
          let thisInput = $(this);
          thisInput.prop('checked', false);
        });
        productSliders.each(function () {
          let thisSlider = $(this);
          let sliderInputs = thisSlider.find('input[type="hidden"][name]');
          sliderInputs.each(function () {
            this.setSliderVal(this.dataset.default);
          });
        });
        productList.trigger('update_filters', [0]);
      }
      function ajaxFilters() {
        let filtersForm = $('.product-list__filters');
        let sort = $('.product-list__sort [name="orderby"]');
        let formData = filtersForm.serialize();
        let slidersInputs = $('.product-list__filter-output');
        if (sort.val()) {
          formData += '&orderby=' + sort.val();
        }
        formData = formData.split('&');
        slidersInputs.each(function () {
          let thisInputs = $(this).find('input[type="hidden"][name]');
          let allDefault = true;
          thisInputs.each(function () {
            let numValue = parseInt(this.value);
            if (!isNaN(numValue) ? numValue !== parseInt(this.dataset.default) : this.value !== this.dataset.default) {
              allDefault = false;
            }
          });
          if (!allDefault) {
            return;
          }
          thisInputs.each(function () {
            let thisQueryIndex = -1;
            let thisQuery = formData.find((item, ind) => {
              thisQueryIndex = ind;
              return item.indexOf(encodeURIComponent(this.name) + '=') === 0;
            });
            if (thisQuery) {
              thisQuery = thisQuery.split('=');
              let numValue = parseInt(thisQuery[1]);
              if (!isNaN(numValue) ? numValue === parseInt(this.dataset.default) : thisQuery[1] === encodeURIComponent(this.dataset.default)) {
                formData.splice(thisQueryIndex, 1);
              }
            }
          });
        });
        formData = formData.join('&');
        let sortedQuery = {};
        let newQuery = [];
        let urlParams = new URLSearchParams(formData);
        let newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?';
        urlParams.forEach(function (queryValue, queryName) {
          queryName = queryName.replaceAll('[', '').replaceAll(']', '');
          if (sortedQuery[queryName]) {
            sortedQuery[queryName].push(queryValue);
          } else {
            sortedQuery[queryName] = [queryValue];
          }
        });
        Object.keys(sortedQuery).forEach(queryName => {
          if (sortedQuery[queryName].length && queryName !== 'action') {
            newQuery.push(queryName + '=' + sortedQuery[queryName].join(','));
          }
        });
        newUrl += newQuery.join('&');
        window.history.pushState({
          path: newUrl
        }, '', newUrl);
        selectFilter();
        $.ajax({
          url: '/wp-admin/admin-ajax.php',
          method: 'get',
          data: formData,
          success: function (data) {
            if (!data['success'] || !data['data']) {
              return;
            }
            if (data['data']['wrapper']) {
              $('.product-list__wrapper').html(data['data']['wrapper']);
            }
            if (data['data']['count_posts']) {
              $('.product-list__count').html(data['data']['count_posts']);
            }
          }
        });
      }
      function toggleMobFilters() {
        productAside.slideToggle(300, function () {
          productAside.toggleClass('is-visible');
        });
      }
      accordion({
        globalContainer: '',
        containerSelector: '.product-list__filter',
        btnSelector: '.product-list__filter-name',
        dropdownSelector: '.product-list__filter-content',
        timing: 3000
      });
      productSliders.each(function () {
        let thisSlider = $(this);
        let container = $(this).closest('.product-list__filter');
        let minEl = container.find('.js-slider-min');
        let maxEl = container.find('.js-slider-max');
        let valuesMinMax = [thisSlider.data('min') || 1, thisSlider.data('max') || 100];
        let values = [thisSlider.data('value-min') || valuesMinMax[0], thisSlider.data('value-max') || valuesMinMax[1]];
        let filterInputs = thisSlider.closest('.product-list__filter').find('input[type="hidden"][name]');
        function setValue(type, value) {
          let thisEl = type === 0 ? minEl : maxEl;
          value = '' + value;
          value = value.split('.')[0];
          thisEl.each(function () {
            if ($(this).is('input')) {
              $(this).val(value);
            } else {
              $(this).text(value);
            }
          });
        }
        setValue(0, values[0]);
        setValue(1, values[1]);
        thisSlider.slider({
          range: true,
          values: values,
          step: thisSlider.data('step') ?? 1,
          min: valuesMinMax[0],
          max: valuesMinMax[1],
          slide: function (_, ui) {
            setValue(0, ui.values[0]);
            setValue(1, ui.values[1]);
            if (window.innerWidth > 768) {
              productList.trigger('update_filters');
            }
          }
        });
        filterInputs.each(function (index) {
          this.setSliderVal = function (value) {
            setValue(index, value);
            thisSlider.slider("values", index, value);
          };
        });
        thisSlider[0].setSliderVal = function (min, max) {
          setValue(0, min);
          setValue(1, max);
          thisSlider.slider("values", 0, min);
          thisSlider.slider("values", 1, max);
        };
      });
      productList.on('update_filters', function (e) {
        let timeout = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 1000;
        clearTimeout(updateTimeout);
        updateTimeout = setTimeout(function () {
          ajaxFilters();
        }, timeout);
      });
      $(document).on('submit', '.product-list__filters', function (e) {
        e.preventDefault();
        productList.trigger('update_filters', [0]);
        if ($('.product-list__aside').hasClass('is-visible')) {
          toggleMobFilters();
        }
      });
      $(document).on('input', '.product-list__sort [name="orderby"]', function () {
        productList.trigger('update_filters', [0]);
      });
      $(document).on('change', '.product-list__filter:not(.is-empty) input', function () {
        if (window.innerWidth > 768) {
          productList.trigger('update_filters');
        }
      });
      $(document).on('click', '.product-list__toggle', toggleMobFilters);
      $(document).on('click', '.js-remove-filters', removeFilters);
      $(document).on('click', '.js-selected-btn svg', removeFilter);
      $(document).on('click', function (e) {
        if ($('.product-list__aside').hasClass('is-visible')) {
          if (!e.target.closest('.product-list__aside')) {
            e.preventDefault();
          }
          setTimeout(function () {
            if (!e.target.closest('.product-list__aside') && !e.target.closest('.product-list__toggle') && !e.target.classList.contains('product-list__toggle')) {
              toggleMobFilters();
            }
          }, 50);
        }
      });
      selectFilter();
    }
  };
  Object.keys(methods).forEach(selector => {
    if (document.querySelector(selector)) methods[selector](document.querySelectorAll(selector));
  });
  if (document.location['hash'] && document.location.hash === "#reset_password") {
    // document.location['hash'] = ''
    setTimeout(function () {
      Fancybox.show([{
        src: '#reset-pass-2',
        type: 'inline',
        placeFocusBack: false,
        trapFocus: false,
        autoFocus: false
      }], {
        dragToClose: false
      });
    }, 500);
  }
  $(document).on('click', 'a.btn.disabled', function (e) {
    e.preventDefault();
  });
}
document.addEventListener('DOMContentLoaded', function () {
  blocks();
  anchors();
  Fancybox.bind('[data-fancybox]', {
    dragToClose: false
  });
});

// Fancybox.show([{
//     src: '#modal_error',
//     type: 'inline',
//     placeFocusBack: false,
//     trapFocus: false,
//     autoFocus: false,
//   }], {
//     dragToClose: false,
//     on: {
//       "destroy": (event, fancybox, slide) => {
//         clearTimeout(closeTimeout)

//         if(activePopup){
//           openPopup(false, activePopup)
//         }
//       },
//     }
// });
/******/ })()
;
//# sourceMappingURL=main.js.map