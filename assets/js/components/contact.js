(function () {
  const app = window.SK = window.SK || {};
  const dom = app.dom;
  if (!dom) return;
  dom.onReady(() => {
    const params = new URLSearchParams(window.location.search);
    const service = params.get('service');
    const select = document.getElementById('sk-service');
    if (service && select) {
      const option = select.querySelector(`option[value="${CSS.escape(service)}"]`);
      if (option) {
        select.value = service;
        select.style.transition = 'box-shadow .4s';
        select.style.boxShadow = '0 0 0 2px rgba(184,98,63,.45)';
        window.setTimeout(() => { select.style.boxShadow = ''; }, 2200);
      }
      history.replaceState(null, '', `${window.location.pathname}${window.location.hash}`);
    }
    const form = dom.qs('.sk-contact-fallback-form');
    if (!form) return;
    const fieldById = (id) => document.getElementById(id);
    if (!fieldById('sk-email') || !fieldById('sk-fname')) return;
    const requiredFields = ['sk-fname', 'sk-email', 'sk-service', 'sk-message'].map(fieldById).filter(Boolean);
    requiredFields.forEach((field) => { field.required = true; });
    const setFeedback = (message, type) => {
      let feedback = form.querySelector('.sk-form-feedback');
      if (!feedback) {
        feedback = document.createElement('div');
        feedback.className = 'sk-form-feedback';
        form.appendChild(feedback);
      }
      feedback.textContent = message;
      feedback.className = `sk-form-feedback sk-form-feedback--${type}`;
      window.setTimeout(() => {
        feedback.textContent = '';
        feedback.className = 'sk-form-feedback';
      }, 7000);
    };
    const showError = (field, message) => {
      field.classList.add('sk-invalid');
      field.classList.remove('sk-valid');
      let error = field.parentElement.querySelector('.sk-field-error');
      if (!error) {
        error = document.createElement('span');
        error.className = 'sk-field-error';
        field.parentElement.appendChild(error);
      }
      error.textContent = message;
    };
    const clearError = (field) => {
      field.classList.remove('sk-invalid');
      field.classList.add('sk-valid');
      const error = field.parentElement.querySelector('.sk-field-error');
      if (error) error.textContent = '';
    };
    const validate = (field) => {
      const value = (field.value || '').trim();
      if (field.required && !value) {
        showError(field, 'This field is required.');
        return false;
      }
      if (field.type === 'email' && value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
        showError(field, 'Please enter a valid email address.');
        return false;
      }
      if (field.id === 'sk-message' && value.length < 10) {
        showError(field, 'Please write at least 10 characters.');
        return false;
      }
      clearError(field);
      return true;
    };
    dom.qsa('input, textarea, select', form).forEach((field) => {
      field.addEventListener('blur', () => validate(field));
      field.addEventListener('input', () => {
        if (field.classList.contains('sk-invalid')) validate(field);
      });
    });
    const submit = form.querySelector('button[type="submit"]');
    form.addEventListener('submit', (event) => {
      event.preventDefault();
      const fields = dom.qsa('input:not([name="website"]), textarea, select', form);
      if (!fields.every(validate)) {
        const first = form.querySelector('.sk-invalid');
        if (first) first.focus();
        return;
      }
      if (!submit) return;
      const originalLabel = submit.textContent;
      submit.disabled = true;
      submit.textContent = 'Sending...';
      const payload = new FormData();
      payload.append('action', 'sk_contact_submit');
      payload.append('nonce', (window.skAppData && window.skAppData.nonce) || '');
      payload.append('fname', fieldById('sk-fname') ? fieldById('sk-fname').value : '');
      payload.append('lname', fieldById('sk-lname') ? fieldById('sk-lname').value : '');
      payload.append('email', fieldById('sk-email') ? fieldById('sk-email').value : '');
      payload.append('service', fieldById('sk-service') ? fieldById('sk-service').value : '');
      payload.append('message', fieldById('sk-message') ? fieldById('sk-message').value : '');
      payload.append('website', fieldById('sk-hp') ? fieldById('sk-hp').value : '');
      fetch((window.skAppData && window.skAppData.ajaxurl) || '/wp-admin/admin-ajax.php', { method: 'POST', body: payload })
        .then((response) => response.json())
        .then((result) => {
          submit.disabled = false;
          submit.textContent = originalLabel;
          if (result && result.success) {
            form.reset();
            dom.qsa('.sk-valid, .sk-invalid', form).forEach((field) => {
              field.classList.remove('sk-valid', 'sk-invalid');
            });
            setFeedback((result.data && result.data.msg) || 'Message sent.', 'success');
            return;
          }
          setFeedback((result && result.data && result.data.msg) || 'Your message could not be sent right now.', 'error');
        })
        .catch(() => {
          submit.disabled = false;
          submit.textContent = originalLabel;
          setFeedback('Message not sent. Please try again shortly.', 'error');
        });
    });
  });
})();
