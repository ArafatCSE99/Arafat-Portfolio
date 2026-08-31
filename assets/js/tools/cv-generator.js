document.addEventListener('DOMContentLoaded', function () {
  var form = document.getElementById('cvName');
  if (!form) return; // not on this page

  var STORAGE_KEY = 'cvGeneratorState_v1';

  var ICONS = {
    mail: '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m3 6 9 7 9-7"/>',
    phone: '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.5 2.1L8 10a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.9.7a2 2 0 0 1 1.7 2Z"/>',
    pin: '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>'
  };
  function svgIcon(name) {
    return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">' + ICONS[name] + '</svg>';
  }
  function esc(s) {
    return String(s == null ? '' : s).replace(/[&<>"']/g, function (ch) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[ch];
    });
  }
  function dateRange(start, end) {
    start = (start || '').trim();
    end = (end || '').trim();
    if (!start && !end) return '';
    if (!end) return esc(start) + ' - Present';
    if (!start) return esc(end);
    return esc(start) + ' - ' + esc(end);
  }

  var DEFAULT_STATE = {
    photo: null,
    name: 'John Doe',
    title: 'Senior Product Designer',
    email: 'john.doe@email.com',
    phone: '+1 555-123-4567',
    address: 'New York, USA',
    summary: 'Results-driven professional with 5+ years of experience delivering high-impact products. Skilled at turning complex requirements into clean, user-friendly solutions and collaborating closely with cross-functional teams to ship on time.',
    accent: { key: 'green', c1: '#16a34a', c2: '#22c55e' },
    experience: [
      { position: 'Senior Product Designer', company: 'Acme Corporation', start: '2022', end: '', description: "Led end-to-end design for the company's flagship product, improving user retention by 28% through research-driven redesigns." }
    ],
    education: [
      { degree: 'B.Sc. in Computer Science', institution: 'State University', start: '2016', end: '2020', description: '' }
    ],
    skills: [
      { name: 'UI/UX Design', level: 90 },
      { name: 'Figma', level: 85 },
      { name: 'HTML & CSS', level: 80 }
    ],
    certificates: []
  };

  var state;
  (function loadState() {
    var saved = null;
    try { saved = JSON.parse(localStorage.getItem(STORAGE_KEY) || 'null'); } catch (e) { saved = null; }
    state = saved && typeof saved === 'object' ? saved : JSON.parse(JSON.stringify(DEFAULT_STATE));
    if (!Array.isArray(state.experience)) state.experience = [];
    if (!Array.isArray(state.education)) state.education = [];
    if (!Array.isArray(state.skills)) state.skills = [];
    if (!Array.isArray(state.certificates)) state.certificates = [];
    if (!state.accent || !state.accent.c1) state.accent = DEFAULT_STATE.accent;
  })();

  function saveState() {
    try { localStorage.setItem(STORAGE_KEY, JSON.stringify(state)); } catch (e) { /* quota / private mode - ignore */ }
  }

  /* ---------- Preview skeleton (built once so the sidebar's gradient
     animation keeps running smoothly instead of restarting every keystroke) ---------- */
  var cvPreview = document.getElementById('cvPreview');
  cvPreview.innerHTML =
    '<aside class="cv-side">' +
      '<img id="cvDocPhoto" class="cv-photo" style="display:none;" alt="">' +
      '<div class="cv-doc-name" id="cvDocName"></div>' +
      '<div class="cv-doc-title" id="cvDocTitle"></div>' +
      '<div class="cv-doc-contact" id="cvDocContact"></div>' +
      '<div class="cv-side-section" id="cvSideSkills" style="display:none;"><h4>Skills</h4><div id="cvSkillsBody"></div></div>' +
      '<div class="cv-side-section" id="cvSideCerts" style="display:none;"><h4>Certificates</h4><div id="cvCertsBody"></div></div>' +
    '</aside>' +
    '<main class="cv-main" id="cvMain"></main>';

  var cvDocPhoto = document.getElementById('cvDocPhoto');
  var cvDocName = document.getElementById('cvDocName');
  var cvDocTitle = document.getElementById('cvDocTitle');
  var cvDocContact = document.getElementById('cvDocContact');
  var cvSideSkills = document.getElementById('cvSideSkills');
  var cvSkillsBody = document.getElementById('cvSkillsBody');
  var cvSideCerts = document.getElementById('cvSideCerts');
  var cvCertsBody = document.getElementById('cvCertsBody');
  var cvMain = document.getElementById('cvMain');

  function renderPreview() {
    cvDocName.textContent = state.name || 'Your Name';
    cvDocTitle.textContent = state.title || 'Your Job Title';

    if (state.photo) {
      cvDocPhoto.src = state.photo;
      cvDocPhoto.style.display = '';
    } else {
      cvDocPhoto.style.display = 'none';
      cvDocPhoto.removeAttribute('src');
    }

    var contactHtml = '';
    if (state.email) contactHtml += '<span>' + svgIcon('mail') + esc(state.email) + '</span>';
    if (state.phone) contactHtml += '<span>' + svgIcon('phone') + esc(state.phone) + '</span>';
    if (state.address) contactHtml += '<span>' + svgIcon('pin') + esc(state.address) + '</span>';
    cvDocContact.innerHTML = contactHtml;

    var skillItems = state.skills.filter(function (s) { return (s.name || '').trim(); });
    if (skillItems.length) {
      var skillsHtml = '';
      skillItems.forEach(function (s) {
        var lvl = Math.max(0, Math.min(100, parseInt(s.level, 10) || 0));
        skillsHtml += '<div class="cv-skill-item"><div class="cv-skill-name"><span>' + esc(s.name) + '</span><b>' + lvl + '%</b></div><div class="cv-skill-track"><div class="cv-skill-fill" style="width:' + lvl + '%;"></div></div></div>';
      });
      cvSkillsBody.innerHTML = skillsHtml;
      cvSideSkills.style.display = '';
    } else {
      cvSideSkills.style.display = 'none';
    }

    var certItems = state.certificates.filter(function (c) { return (c.title || '').trim(); });
    if (certItems.length) {
      var certsHtml = '';
      certItems.forEach(function (c) {
        var meta = [c.issuer, c.year].filter(function (v) { return (v || '').trim(); }).join(' · ');
        certsHtml += '<div class="cv-cert-item"><span class="cv-cert-title">' + esc(c.title) + '</span>' + (meta ? '<span class="cv-cert-meta">' + esc(meta) + '</span>' : '') + '</div>';
      });
      cvCertsBody.innerHTML = certsHtml;
      cvSideCerts.style.display = '';
    } else {
      cvSideCerts.style.display = 'none';
    }

    var html = '';

    if ((state.summary || '').trim()) {
      html += '<div class="cv-section"><h4>Profile</h4><p class="cv-summary">' + esc(state.summary).replace(/\n/g, '<br>') + '</p></div>';
    }

    var expItems = state.experience.filter(function (x) { return (x.position || '').trim() || (x.company || '').trim(); });
    if (expItems.length) {
      html += '<div class="cv-section"><h4>Experience</h4>';
      expItems.forEach(function (x) {
        html += '<div class="cv-entry"><div class="cv-entry-top"><span class="cv-entry-title">' + esc(x.position || 'Position') + '</span><span class="cv-entry-date">' + dateRange(x.start, x.end) + '</span></div>' +
          (x.company ? '<div class="cv-entry-sub">' + esc(x.company) + '</div>' : '') +
          (x.description ? '<div class="cv-entry-desc">' + esc(x.description).replace(/\n/g, '<br>') + '</div>' : '') +
          '</div>';
      });
      html += '</div>';
    }

    var eduItems = state.education.filter(function (x) { return (x.degree || '').trim() || (x.institution || '').trim(); });
    if (eduItems.length) {
      html += '<div class="cv-section"><h4>Education</h4>';
      eduItems.forEach(function (x) {
        html += '<div class="cv-entry"><div class="cv-entry-top"><span class="cv-entry-title">' + esc(x.degree || 'Degree') + '</span><span class="cv-entry-date">' + dateRange(x.start, x.end) + '</span></div>' +
          (x.institution ? '<div class="cv-entry-sub">' + esc(x.institution) + '</div>' : '') +
          (x.description ? '<div class="cv-entry-desc">' + esc(x.description).replace(/\n/g, '<br>') + '</div>' : '') +
          '</div>';
      });
      html += '</div>';
    }

    if (!html) {
      html = '<p class="cv-doc-empty">Start filling in the form on the left to see your resume come together here, live.</p>';
    }

    cvMain.classList.remove('cvb-flash');
    void cvMain.offsetWidth; // force reflow so the animation can restart
    cvMain.innerHTML = html;
    cvMain.classList.add('cvb-flash');
  }

  /* ---------- Repeatable form rows (experience / education / skills / certificates) ---------- */
  function createRow(container, tpl, obj, arr) {
    var node = tpl.content.firstElementChild.cloneNode(true);
    container.appendChild(node);

    node.querySelectorAll('[data-field]').forEach(function (inp) {
      var field = inp.dataset.field;
      var pctEl = node.querySelector('[data-role="pct"]');
      if (inp.type === 'range') {
        inp.value = obj[field] != null ? obj[field] : 80;
        if (pctEl) pctEl.textContent = inp.value + '%';
        inp.addEventListener('input', function () {
          obj[field] = parseInt(inp.value, 10);
          if (pctEl) pctEl.textContent = inp.value + '%';
          renderPreview();
          saveState();
        });
      } else {
        inp.value = obj[field] || '';
        inp.addEventListener('input', function () {
          obj[field] = inp.value;
          renderPreview();
          saveState();
        });
      }
    });

    node.querySelector('[data-action="remove"]').addEventListener('click', function () {
      node.classList.add('cvb-entry-out');
      setTimeout(function () { node.remove(); }, 220);
      var idx = arr.indexOf(obj);
      if (idx > -1) arr.splice(idx, 1);
      renderPreview();
      saveState();
    });

    return node;
  }

  var expList = document.getElementById('expList');
  var eduList = document.getElementById('eduList');
  var skillList = document.getElementById('skillList');
  var certList = document.getElementById('certList');
  var tplExp = document.getElementById('tpl-exp-row');
  var tplEdu = document.getElementById('tpl-edu-row');
  var tplSkill = document.getElementById('tpl-skill-row');
  var tplCert = document.getElementById('tpl-cert-row');

  state.experience.forEach(function (obj) { createRow(expList, tplExp, obj, state.experience); });
  state.education.forEach(function (obj) { createRow(eduList, tplEdu, obj, state.education); });
  state.skills.forEach(function (obj) { createRow(skillList, tplSkill, obj, state.skills); });
  state.certificates.forEach(function (obj) { createRow(certList, tplCert, obj, state.certificates); });

  document.getElementById('addExpBtn').addEventListener('click', function () {
    var obj = { position: '', company: '', start: '', end: '', description: '' };
    state.experience.push(obj);
    createRow(expList, tplExp, obj, state.experience);
    renderPreview();
    saveState();
  });
  document.getElementById('addEduBtn').addEventListener('click', function () {
    var obj = { degree: '', institution: '', start: '', end: '', description: '' };
    state.education.push(obj);
    createRow(eduList, tplEdu, obj, state.education);
    renderPreview();
    saveState();
  });
  document.getElementById('addSkillBtn').addEventListener('click', function () {
    var obj = { name: '', level: 75 };
    state.skills.push(obj);
    createRow(skillList, tplSkill, obj, state.skills);
    renderPreview();
    saveState();
  });
  document.getElementById('addCertBtn').addEventListener('click', function () {
    var obj = { title: '', issuer: '', year: '' };
    state.certificates.push(obj);
    createRow(certList, tplCert, obj, state.certificates);
    renderPreview();
    saveState();
  });

  /* ---------- Personal info fields ---------- */
  function bindText(id, field) {
    var el = document.getElementById(id);
    el.value = state[field] || '';
    el.addEventListener('input', function () {
      state[field] = el.value;
      renderPreview();
      saveState();
    });
  }
  bindText('cvName', 'name');
  bindText('cvTitle', 'title');
  bindText('cvEmail', 'email');
  bindText('cvPhone', 'phone');
  bindText('cvAddress', 'address');
  bindText('cvSummary', 'summary');

  /* ---------- Photo upload ---------- */
  var cvPhotoImg = document.getElementById('cvPhotoImg');
  var cvPhotoPlaceholder = document.getElementById('cvPhotoPlaceholder');
  var cvPhotoRemoveBtn = document.getElementById('cvPhotoRemoveBtn');
  var cvPhotoInput = document.getElementById('cvPhotoInput');

  function updateFormPhoto() {
    if (state.photo) {
      cvPhotoImg.src = state.photo;
      cvPhotoImg.style.display = '';
      cvPhotoPlaceholder.style.display = 'none';
      cvPhotoRemoveBtn.style.display = '';
    } else {
      cvPhotoImg.style.display = 'none';
      cvPhotoImg.removeAttribute('src');
      cvPhotoPlaceholder.style.display = '';
      cvPhotoRemoveBtn.style.display = 'none';
    }
  }
  updateFormPhoto();

  cvPhotoInput.addEventListener('change', function () {
    var file = cvPhotoInput.files && cvPhotoInput.files[0];
    if (!file) return;
    if (!/^image\//.test(file.type)) { alert('Please choose an image file.'); return; }
    if (file.size > 3 * 1024 * 1024) { alert('Please choose an image smaller than 3MB.'); return; }
    var reader = new FileReader();
    reader.onload = function () {
      state.photo = reader.result;
      updateFormPhoto();
      renderPreview();
      saveState();
    };
    reader.readAsDataURL(file);
  });
  cvPhotoRemoveBtn.addEventListener('click', function () {
    state.photo = null;
    cvPhotoInput.value = '';
    updateFormPhoto();
    renderPreview();
    saveState();
  });

  /* ---------- Accent color ---------- */
  var swatches = document.querySelectorAll('.cvb-swatch');
  function applyAccent() {
    cvPreview.style.setProperty('--cv-accent', state.accent.c1);
    cvPreview.style.setProperty('--cv-accent-2', state.accent.c2);
    swatches.forEach(function (btn) { btn.classList.toggle('active', btn.dataset.key === state.accent.key); });
  }
  swatches.forEach(function (btn) {
    btn.addEventListener('click', function () {
      state.accent = { key: btn.dataset.key, c1: btn.dataset.c1, c2: btn.dataset.c2 };
      applyAccent();
      saveState();
    });
  });
  applyAccent();

  /* ---------- Toolbar actions ---------- */
  document.getElementById('cvDownloadBtn').addEventListener('click', function () {
    window.print();
  });
  document.getElementById('cvResetBtn').addEventListener('click', function () {
    if (!confirm('Reset the form and clear your saved draft? This cannot be undone.')) return;
    try { localStorage.removeItem(STORAGE_KEY); } catch (e) { /* ignore */ }
    location.reload();
  });

  renderPreview();
});
