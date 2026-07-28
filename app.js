/* ==========================================================================
   Localization Dictionaries
   ========================================================================== */

const TRANSLATIONS = {
    sr_RS: {
        page_title: "Портал за електронску идентификацију",
        header_title: "Портал за електронску идентификацију",
        nav_egr: "еГрађанин", nav_cloud: "Потпис у клауду", nav_help: "Помоћ",
        nav_contact: "Контакт", nav_euprava: "Портал еУправа", nav_back: "Назад",
        breadcrumbs_heading: "eVisa Портал",
        tab_up_title: "Корисничко име и лозинка",
        tab_sc_title: "Квалификовани електронски сертификат",
        tab_cid_title: "Мобилна апликација",
        up_desc: "Број е-визе и број пасоша је пријава основног нивоа поузданости.",
        sc_desc: "Пријава квалификованим електронским сертификатом је пријава високог нивоа поузданости.",
        cid_desc: "Пријава мобилним телефоном обавља се помоћу апликације ConsentID и представља пријаву високог нивоа поузданости.",
        learn_more: "Сазнајте више.",
        label_username: "Е-виза број:", username_desc: "(Број е-визе добијен у мејлу одобрења)",
        placeholder_evisa: "Број е-визе", placeholder_passport: "Број пасоша",
        label_password: "Број пасоша:",
        btn_signin: "Пријавите се", btn_enter: "Уђите", forgot_password: "Заборављена лозинка",
        no_account: "Немате налог на eid.gov.rs?", register_here: "Региструјте се овде.",
        sc_instruction: "Убаците квалификовани електронски сертификат и кликните на Пријавите се.",
        cid_activate_guide: "Како да активирате мобилну апликацију ConsentID?",
        cookie_text: "Користимо колачиће да бисмо осигурали да добијете најбоље могуће искуство.",
        cookie_policy_link: "Политика колачића", btn_cookie_accept: "Разумем",
        timeout_title: "Сесија ће ускоро истећи!",
        timeout_desc_1: "Нисте били активни дуже време. Аутоматско преусмеравање за",
        btn_dismiss: "Затвори", btn_start_over: "Покрени поново",
        error_invalid_evisa: "Молимо унесите исправан број е-визе.",
        error_empty_password: "Молимо унесите ваш број пасоша.",
        panel_font_title: "Величина фонта", panel_font_subtitle: "Изаберите стил приказа слова",
        panel_theme_title: "Изаберите тему", panel_theme_subtitle: "Изаберите тему за приказ страница",
        font_small: "Умањена слова", font_normal: "Нормална величина", font_large: "Велика слова",
        theme_default: "Основна тема", theme_bw: "Црно/бела тема", theme_inverse: "Инверзна тема",
        zoom_helper_text: "Употребите <strong>CTRL+</strong> за повећање, <strong>CTRL-</strong> за смањивање",
        footer_to_top: "Врх стране", footer_privacy: "Изјава о приватности", footer_terms: "Услови коришћења",
        footer_license: "Веб презентација је лиценцирана под условима лиценце Creative Commons Ауторство-Некомерцијално-Без прерада 3.0 Србија. Веб пројекат ite.gov.rs",
        auth_success_title: "Успешна пријава!", auth_success_desc: "Ваш документ је пронађен. Можете га преузети испод.",
        btn_back_to_login: "Назад на пријаву",
        cert_scanning: "Скенирање квалификованог сертификата у току...",
        cert_scan_success: "Сертификат је успешно препознат!",
        auth_error_title: "Грешка при пријави",
        btn_download_pdf: "📄 Преузми е-Визу (PDF)",
        searching: "Претраживање..."
    },
    lat_RS: {
        page_title: "Portal za elektronsku identifikaciju",
        header_title: "Portal za elektronsku identifikaciju",
        nav_egr: "eGrađanin", nav_cloud: "Potpis u klaudu", nav_help: "Pomoć",
        nav_contact: "Kontakt", nav_euprava: "Portal eUprava", nav_back: "Nazad",
        breadcrumbs_heading: "eVisa Portal",
        tab_up_title: "Korisničko ime i lozinka",
        tab_sc_title: "Kvalifikovani elektronski sertifikat",
        tab_cid_title: "Mobilna aplikacija",
        up_desc: "Broj e-vize i broj pasoša je prijava osnovnog nivoa pouzdanosti.",
        sc_desc: "Prijava kvalifikovanim elektronskim sertifikatom je prijava visokog nivoa pouzdanosti.",
        cid_desc: "Prijava mobilnim telefonom obavlja se pomoću aplikacije ConsentID i predstavlja prijavu visokog nivoa pouzdanosti.",
        learn_more: "Saznajte više.",
        label_username: "E-viza broj:", username_desc: "(Broj e-vize dobijen u mejlu odobrenja)",
        placeholder_evisa: "Broj e-vize", placeholder_passport: "Broj pasoša",
        label_password: "Broj pasoša:",
        btn_signin: "Prijavite se", btn_enter: "Uđite", forgot_password: "Zaboravljena lozinka",
        no_account: "Nemate nalog na eid.gov.rs?", register_here: "Registrujte se ovde.",
        sc_instruction: "Ubacite kvalifikovani elektronski sertifikat i kliknite na Prijavite se.",
        cid_activate_guide: "Kako da aktivirate mobilnu aplikaciju ConsentID?",
        cookie_text: "Koristimo kolačiće da bismo osigurali da dobijete najbolje moguće iskustvo.",
        cookie_policy_link: "Politika kolačića", btn_cookie_accept: "Razumem",
        timeout_title: "Sesija će uskoro isteći!",
        timeout_desc_1: "Niste bili aktivni duže vreme. Automatsko preusmeravanje za",
        btn_dismiss: "Zatvori", btn_start_over: "Pokreni ponovo",
        error_invalid_evisa: "Molimo unesite ispravan broj e-vize.",
        error_empty_password: "Molimo unesite vaš broj pasoša.",
        panel_font_title: "Veličina fonta", panel_font_subtitle: "Izaberite stil prikaza slova",
        panel_theme_title: "Izaberite temu", panel_theme_subtitle: "Izaberite temu za prikaz stranica",
        font_small: "Umanjena slova", font_normal: "Normalna veličina", font_large: "Velika slova",
        theme_default: "Osnovna tema", theme_bw: "Crno/bela tema", theme_inverse: "Inverzna tema",
        zoom_helper_text: "Upotrebite <strong>CTRL+</strong> za povećanje, <strong>CTRL-</strong> za smanjivanje",
        footer_to_top: "Vrh strane", footer_privacy: "Izjava o privatnosti", footer_terms: "Uslovi korišćenja",
        footer_license: "Veb prezentacija je licencirana pod uslovima licence Creative Commons. Veb projekat ite.gov.rs",
        auth_success_title: "Uspešna prijava!", auth_success_desc: "Vaš dokument je pronađen. Možete ga preuzeti ispod.",
        btn_back_to_login: "Nazad na prijavu",
        cert_scanning: "Skeniranje kvalifikovanog sertifikata u toku...",
        cert_scan_success: "Sertifikat je uspešno prepoznat!",
        auth_error_title: "Greška pri prijavi",
        btn_download_pdf: "📄 Preuzmi e-Vizu (PDF)",
        searching: "Pretraživanje..."
    },
    en_US: {
        page_title: "Electronic Identification Portal",
        header_title: "Electronic Identification Portal",
        nav_egr: "eCitizen", nav_cloud: "Cloud Signature", nav_help: "Help",
        nav_contact: "Contact", nav_euprava: "eGovernment Portal", nav_back: "Back",
        breadcrumbs_heading: "eVisa Portal",
        tab_up_title: "Username and password",
        tab_sc_title: "Qualified electronic certificate",
        tab_cid_title: "Mobile application",
        up_desc: "eVisa number and passport number is a basic level of assurance sign-in.",
        sc_desc: "Sign-in with a qualified electronic certificate is a high level of assurance sign-in.",
        cid_desc: "Mobile phone sign-in is performed using the ConsentID application.",
        learn_more: "Learn more.",
        label_username: "eVisa Number:", username_desc: "(eVisa number received in the approval email)",
        placeholder_evisa: "eVisa number", placeholder_passport: "Passport number",
        label_password: "Passport Number:",
        btn_signin: "Sign in", btn_enter: "Enter", forgot_password: "Forgotten password",
        no_account: "Don't have an account on eid.gov.rs?", register_here: "Register here.",
        sc_instruction: "Insert your qualified electronic certificate and click Sign in.",
        cid_activate_guide: "How to activate the ConsentID mobile application?",
        cookie_text: "We use cookies to ensure that you get the best possible experience.",
        cookie_policy_link: "Cookie Policy", btn_cookie_accept: "I understand",
        timeout_title: "eVisa instance is about to timeout!",
        timeout_desc_1: "You have been idle for too long. Auto redirect in",
        btn_dismiss: "Dismiss", btn_start_over: "Start over",
        error_invalid_evisa: "Please enter a valid eVisa number.",
        error_empty_password: "Please enter your passport number.",
        panel_font_title: "Font Size", panel_font_subtitle: "Select letter display style",
        panel_theme_title: "Select Theme", panel_theme_subtitle: "Select theme for page display",
        font_small: "Small text", font_normal: "Normal size", font_large: "Large text",
        theme_default: "Basic theme", theme_bw: "Black & White theme", theme_inverse: "Inverse theme",
        zoom_helper_text: "Use <strong>CTRL+</strong> to zoom in, <strong>CTRL-</strong> to zoom out",
        footer_to_top: "Top of Page", footer_privacy: "Privacy Statement", footer_terms: "Terms of Use",
        footer_license: "Web presentation licensed under Creative Commons Attribution-NonCommercial-NoDerivs 3.0 Serbia.",
        auth_success_title: "Sign-in Successful!", auth_success_desc: "Your document was found. You can download it below.",
        btn_back_to_login: "Back to login",
        cert_scanning: "Scanning qualified certificate in progress...",
        cert_scan_success: "Certificate recognized successfully!",
        auth_error_title: "Sign-in Error",
        btn_download_pdf: "📄 Download e-Visa (PDF)",
        searching: "Searching..."
    }
};

/* ==========================================================================
   State
   ========================================================================== */
let currentLang      = localStorage.getItem('eid_lang')       || 'sr_RS';
let currentTheme     = localStorage.getItem('eid_theme')      || 'default';
let currentFontSize  = localStorage.getItem('eid_font_size')  || 'normal';

const IDLE_LIMIT_MS      = 15000;
const MODAL_COUNTDOWN_SEC = 30;
let idleTimer, warningCountdownInterval, timeRemaining = MODAL_COUNTDOWN_SEC;

/* ==========================================================================
   DOM Selectors
   ========================================================================== */
const langSelectorBtn  = document.getElementById('langSelectorBtn');
const selectedLangText = document.getElementById('selectedLangText');
const langMenu         = document.getElementById('langMenu');
const langItems        = document.querySelectorAll('.lang-item');
const tabLinks         = document.querySelectorAll('.tab-link');
const tabPanels        = document.querySelectorAll('.tab-panel');
const formUP           = document.getElementById('formUP');
const formCID          = document.getElementById('formCID');
const signinCertBtn    = document.getElementById('signinCertBtn');
const certScannerText  = document.getElementById('certScannerText');
const authCard         = document.querySelector('.auth-card');
const authResultCard   = document.getElementById('authResultCard');
const resultIconSuccess= document.getElementById('resultIconSuccess');
const resultIconError  = document.getElementById('resultIconError');
const resultTitle      = document.getElementById('resultTitle');
const resultDesc       = document.getElementById('resultDesc');
const authResultCloseBtn = document.getElementById('authResultCloseBtn');
const cookieConsentBanner= document.getElementById('cookieConsentBanner');
const cookieAcceptBtn  = document.getElementById('cookieAcceptBtn');
const idleModal        = document.getElementById('idleModal');
const idleDismissBtn   = document.getElementById('idleDismissBtn');
const idleActionBtn    = document.getElementById('idleActionBtn');
const idleCountdownText= document.getElementById('idleCountdownText');
const passwordInput    = document.getElementById('password1');
const toTopBtn         = document.getElementById('toTopBtn');

/* ==========================================================================
   Translation
   ========================================================================== */
function translatePage(lang) {
    const dict = TRANSLATIONS[lang] || TRANSLATIONS.sr_RS;
    document.querySelectorAll('[data-translate]').forEach(el => {
        const key = el.getAttribute('data-translate');
        if (!dict[key]) return;
        if (el.tagName === 'INPUT' && el.hasAttribute('placeholder')) {
            el.setAttribute('placeholder', dict[key]);
        } else if (el.querySelector('strong')) {
            el.innerHTML = dict[key];
        } else {
            el.textContent = dict[key];
        }
    });
    document.title = dict.page_title;
    let displayLang = "Ћирилица";
    if (lang === 'lat_RS') displayLang = "Latinica";
    if (lang === 'en_US')  displayLang = "English";
    selectedLangText.textContent = displayLang;
    localStorage.setItem('eid_lang', lang);
    currentLang = lang;
    langItems.forEach(item => {
        item.classList.toggle('active', item.getAttribute('data-lang') === lang);
    });
}

/* ==========================================================================
   Theme & Font
   ========================================================================== */
function setTheme(theme) {
    document.body.classList.remove('theme-default','theme-bw','theme-inverse');
    document.body.classList.add(`theme-${theme}`);
    localStorage.setItem('eid_theme', theme);
    currentTheme = theme;
}
function setFontSize(size) {
    document.body.classList.remove('font-small','font-normal','font-large');
    document.body.classList.add(`font-${size}`);
    localStorage.setItem('eid_font_size', size);
    currentFontSize = size;
}

/* ==========================================================================
   Tab Controller
   ========================================================================== */
function switchTab(targetTabId) {
    tabLinks.forEach(t => { t.classList.remove('active'); t.setAttribute('aria-selected','false'); });
    tabPanels.forEach(p => p.classList.remove('active'));
    const targetLink = document.getElementById(targetTabId);
    targetLink.classList.add('active');
    targetLink.setAttribute('aria-selected','true');
    document.getElementById(targetLink.getAttribute('aria-controls')).classList.add('active');
}

/* ==========================================================================
   Idle System
   ========================================================================== */
function resetIdleTimer() {
    clearTimeout(idleTimer);
    if (!idleModal.open) idleTimer = setTimeout(showIdleModal, IDLE_LIMIT_MS);
}
function showIdleModal() {
    idleModal.showModal();
    timeRemaining = MODAL_COUNTDOWN_SEC;
    updateModalTimerDisplay();
    clearInterval(warningCountdownInterval);
    warningCountdownInterval = setInterval(() => {
        timeRemaining--;
        updateModalTimerDisplay();
        if (timeRemaining <= 0) {
            clearInterval(warningCountdownInterval);
            idleModal.close();
            triggerMockTimeoutRedirect();
        }
    }, 1000);
}
function updateModalTimerDisplay() {
    const m = Math.floor(timeRemaining / 60);
    const s = timeRemaining % 60;
    idleCountdownText.textContent = `${m}:${s < 10 ? '0' : ''}${s}`;
}
function dismissIdleModal() { idleModal.close(); clearInterval(warningCountdownInterval); resetIdleTimer(); }
function startOverSession()  { idleModal.close(); clearInterval(warningCountdownInterval); resetLoginFormStates(); resetIdleTimer(); }
function triggerMockTimeoutRedirect() {
    authCard.classList.add('hidden');
    authResultCard.classList.remove('hidden');
    resultIconSuccess.classList.add('hidden');
    resultIconError.classList.remove('hidden');
    resultTitle.textContent = currentLang === 'en_US' ? 'Session Timeout!' : 'Истекла сесија!';
    resultDesc.textContent  = currentLang === 'en_US'
        ? 'For security reasons, your login session expired due to inactivity.'
        : 'Из безбедносних разлога, ваша сесија за пријаву је истекла због неактивности.';
    // Remove download button if present
    const dlBtn = document.getElementById('downloadPdfBtn');
    if (dlBtn) dlBtn.remove();
}
function setupActivityListeners() {
    ['mousemove','keypress','mousedown','touchstart','scroll'].forEach(ev => {
        window.addEventListener(ev, resetIdleTimer, true);
    });
    resetIdleTimer();
}

/* ==========================================================================
   Form Helpers
   ========================================================================== */
function validateEvisa(v) { return v.trim().length >= 5; }
function validateEmail(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }

function resetLoginFormStates() {
    formUP.reset();
    formCID.reset();
    document.querySelectorAll('.form-group').forEach(g => g.classList.remove('has-error'));
    authCard.classList.remove('hidden');
    authResultCard.classList.add('hidden');
    const dlBtn = document.getElementById('downloadPdfBtn');
    if (dlBtn) dlBtn.remove();
    const dict = TRANSLATIONS[currentLang];
    certScannerText.textContent = dict.sc_instruction;
    document.querySelector('.smartcard-svg').style.color = '';
}

function setButtonLoading(btn, loading, dict) {
    const spinner = btn.querySelector('.loading-spinner');
    const text    = btn.querySelector('.btn-text');
    btn.disabled  = loading;
    if (loading) {
        spinner.classList.remove('hidden');
        if (text) text.textContent = dict.searching || '...';
    } else {
        spinner.classList.add('hidden');
        if (text) {
            const key = text.getAttribute('data-translate') || 'btn_signin';
            text.textContent = dict[key] || dict.btn_signin;
        }
    }
}

/* ==========================================================================
   ★ CORE: Lookup record via admin.php, then redirect to Visa Status page
   ========================================================================== */
async function lookupAndShowPDF(evisaNumber, passportNumber, submitBtn) {
    const dict = TRANSLATIONS[currentLang];
    setButtonLoading(submitBtn, true, dict);

    let found = false;
    try {
        const fd = new FormData();
        fd.append('action', 'lookup');
        fd.append('evisa_number', evisaNumber.trim());
        fd.append('passport_number', passportNumber.trim());

        const res  = await fetch('admin.php', { method: 'POST', body: fd });
        const data = await res.json();
        found = !!data.success;
    } catch (e) {
        console.error('Lookup request failed:', e);
    }

    setButtonLoading(submitBtn, false, dict);

    if (found) {
        // ── SUCCESS: redirect to the Visa Status page ──
        const params = new URLSearchParams({
            evisa: evisaNumber.trim(),
            passport: passportNumber.trim()
        });
        window.location.href = 'visa-status.php?' + params.toString();
    } else {
        // ── ERROR: show inline error card ──
        authCard.classList.add('hidden');
        authResultCard.classList.remove('hidden');
        resultIconSuccess.classList.add('hidden');
        resultIconError.classList.remove('hidden');

        resultTitle.textContent = dict.auth_error_title || 'Greška';
        resultDesc.textContent  = currentLang === 'en_US'
            ? 'No document found for entered details. Please check your eVisa number and passport number.'
            : currentLang === 'lat_RS'
            ? 'Nije pronađen dokument za unete podatke. Proverite broj e-vize i broj pasoša.'
            : 'Није пронађен документ за унете податке. Проверите број е-визе и број пасоша.';
    }
}

/* ==========================================================================
   Cookie Banner
   ========================================================================== */
function checkCookieConsent() {
    if (!localStorage.getItem('eid_cookies_accepted')) {
        setTimeout(() => cookieConsentBanner.classList.add('show'), 1000);
    }
}
function acceptCookies() {
    cookieConsentBanner.classList.remove('show');
    localStorage.setItem('eid_cookies_accepted', 'true');
}

/* ==========================================================================
   Init
   ========================================================================== */
document.addEventListener('DOMContentLoaded', () => {

    translatePage(currentLang);
    setTheme(currentTheme);
    setFontSize(currentFontSize);
    checkCookieConsent();
    setupActivityListeners();

    // Lang dropdown
    langSelectorBtn && langSelectorBtn.addEventListener('click', e => {
        e.stopPropagation();
        langSelectorBtn.parentElement.classList.toggle('open');
    });
    langItems.forEach(item => {
        item.addEventListener('click', () => {
            translatePage(item.getAttribute('data-lang'));
            langSelectorBtn && langSelectorBtn.parentElement.classList.remove('open');
        });
    });
    window.addEventListener('click', () => {
        langSelectorBtn && langSelectorBtn.parentElement.classList.remove('open');
    });

    // Tabs
    tabLinks.forEach(link => link.addEventListener('click', () => switchTab(link.id)));

    // Cookie
    cookieAcceptBtn.addEventListener('click', acceptCookies);

    // Idle modal
    idleDismissBtn.addEventListener('click', dismissIdleModal);
    idleActionBtn.addEventListener('click', startOverSession);

    // To top
    toTopBtn.addEventListener('click', e => { e.preventDefault(); window.scrollTo({ top: 0, behavior: 'smooth' }); });

    // ── FORM 1: eVisa number + Passport → lookup PDF ──────────────
    formUP.addEventListener('submit', async (e) => {
        e.preventDefault();
        const evisaInput   = document.getElementById('username1');
        const passInput    = document.getElementById('password1');
        let isValid = true;

        if (!validateEvisa(evisaInput.value)) {
            evisaInput.closest('.form-group').classList.add('has-error');
            isValid = false;
        } else {
            evisaInput.closest('.form-group').classList.remove('has-error');
        }
        if (passInput.value.trim() === '') {
            passInput.closest('.form-group').classList.add('has-error');
            isValid = false;
        } else {
            passInput.closest('.form-group').classList.remove('has-error');
        }

        if (isValid) {
            const submitBtn = formUP.querySelector('button[type="submit"]');
            await lookupAndShowPDF(evisaInput.value.trim(), passInput.value.trim(), submitBtn);
        }
    });

    // ── FORM 2: Certificate (keep as mock) ─────────────────────────
    signinCertBtn.addEventListener('click', () => {
        const dict = TRANSLATIONS[currentLang];
        certScannerText.textContent = dict.cert_scanning;
        document.querySelector('.smartcard-svg').style.color = 'var(--color-gold)';
        signinCertBtn.disabled = true;
        const spinner = signinCertBtn.querySelector('.loading-spinner');
        spinner.classList.remove('hidden');
        setTimeout(() => {
            spinner.classList.add('hidden');
            signinCertBtn.disabled = false;
            certScannerText.textContent = dict.cert_scan_success;
            document.querySelector('.smartcard-svg').style.color = 'var(--success-color)';
            setTimeout(() => {
                authCard.classList.add('hidden');
                authResultCard.classList.remove('hidden');
                resultIconSuccess.classList.remove('hidden');
                resultIconError.classList.add('hidden');
                resultTitle.textContent = dict.auth_success_title;
                resultDesc.textContent  = dict.auth_success_desc;
            }, 1000);
        }, 1500);
    });

    // ── FORM 3: Mobile app (keep as mock) ──────────────────────────
    formCID.addEventListener('submit', (e) => {
        e.preventDefault();
        const emailInput = document.getElementById('usernameCid');
        if (!validateEmail(emailInput.value)) {
            emailInput.closest('.form-group').classList.add('has-error');
            return;
        }
        emailInput.closest('.form-group').classList.remove('has-error');
        const submitBtn = formCID.querySelector('button[type="submit"]');
        const dict = TRANSLATIONS[currentLang];
        setButtonLoading(submitBtn, true, dict);
        setTimeout(() => {
            setButtonLoading(submitBtn, false, dict);
            authCard.classList.add('hidden');
            authResultCard.classList.remove('hidden');
            resultIconSuccess.classList.remove('hidden');
            resultIconError.classList.add('hidden');
            resultTitle.textContent = dict.auth_success_title;
            resultDesc.textContent  = dict.auth_success_desc;
        }, 1500);
    });

    // Back to login
    authResultCloseBtn.addEventListener('click', resetLoginFormStates);
});
