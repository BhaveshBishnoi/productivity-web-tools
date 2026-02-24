document.addEventListener('DOMContentLoaded', () => {
    // DOM Elements
    const editor = document.getElementById('editor');
    const fontFamilySelect = document.getElementById('font-family');
    const fontSizeSelect = document.getElementById('font-size');
    const themeToggle = document.getElementById('theme-toggle');
    const langSelect = document.getElementById('lang-select');

    // Buttons
    const btnNew = document.getElementById('btn-new');
    const btnSave = document.getElementById('btn-save');
    const btnOpen = document.getElementById('btn-open');
    const fileInput = document.getElementById('file-input');

    // Status Elements
    const saveStatus = document.getElementById('save-status');
    const wordCount = document.getElementById('word-count');
    const charCount = document.getElementById('char-count');

    let debounceTimer;

    // View Controls
    const btnNarrowMode = document.getElementById('btn-view-mode'); // Renaming ID in index.php next
    const btnFocus = document.getElementById('btn-focus');

    // --- Initialization ---

    function init() {
        loadSession();
        loadViewPreferences();
        updateStats(); // Initial stats
        setupEventListeners();
    }

    // --- View Preferences (LocalStorage) ---
    function loadViewPreferences() {
        const isNarrowMode = localStorage.getItem('notepad_narrow_mode') === 'true';
        const isFocusMode = localStorage.getItem('notepad_focus_mode') === 'true';

        // Default is Full Width (no class needed).
        // If Narrow Mode is active, add class
        if (isNarrowMode) {
            document.body.classList.add('narrow-mode');
            if (btnNarrowMode) btnNarrowMode.classList.add('active');
        }

        if (isFocusMode) {
            document.body.classList.add('focus-mode');
            if (btnFocus) btnFocus.classList.add('active');
        }
    }

    function toggleNarrowMode() {
        document.body.classList.toggle('narrow-mode');
        const isActive = document.body.classList.contains('narrow-mode');
        if (btnNarrowMode) btnNarrowMode.classList.toggle('active', isActive);
        localStorage.setItem('notepad_narrow_mode', isActive);

        // Optional: Update icon? keeping simple for now
    }

    function toggleFocusMode() {
        document.body.classList.toggle('focus-mode');
        const isActive = document.body.classList.contains('focus-mode');
        btnFocus.classList.toggle('active', isActive);
        localStorage.setItem('notepad_focus_mode', isActive);
    }

    // --- Session Management ---

    function loadSession() {
        fetch('api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'action=load'
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    if (data.content) {
                        editor.value = data.content;
                        updateStats();
                    }

                    if (data.font_family) {
                        fontFamilySelect.value = data.font_family;
                        editor.style.fontFamily = data.font_family;
                    }

                    if (data.font_size) {
                        fontSizeSelect.value = data.font_size;
                        editor.style.fontSize = data.font_size;
                    }

                    if (data.theme) {
                        document.documentElement.setAttribute('data-theme', data.theme);
                        updateThemeIcon(data.theme);
                    }
                }
            })
            .catch(err => console.error('Error loading session:', err));
    }

    function saveState() {
        const content = editor.value;
        const font_family = fontFamilySelect.value;
        const font_size = fontSizeSelect.value;
        const theme = document.documentElement.getAttribute('data-theme');

        const params = new URLSearchParams();
        params.append('action', 'save');
        params.append('content', content);
        params.append('font_family', font_family);
        params.append('font_size', font_size);
        params.append('theme', theme);

        showStatus(true); // Show "Saving..." or just active state

        fetch('api.php', {
            method: 'POST',
            body: params
        })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    showStatus(false);
                }
            })
            .catch(err => {
                console.error('Save error:', err);
                // Optionally show error state
            });
    }

    // --- Stats Logic ---

    function updateStats() {
        const text = editor.value;
        const chars = text.length;
        // Basic word count splitting by whitespace
        const words = text.trim() === '' ? 0 : text.trim().split(/\s+/).length;

        wordCount.textContent = `${words} Words`;
        charCount.textContent = `${chars} Chars`;
    }

    function showStatus(isSaving) {
        if (isSaving) {
            saveStatus.textContent = saveStatus.getAttribute('data-saving');
            saveStatus.classList.remove('hidden');
            saveStatus.style.opacity = '1';
        } else {
            saveStatus.textContent = saveStatus.getAttribute('data-saved');
            // Flash "Saved" then hide
            setTimeout(() => {
                saveStatus.style.opacity = '0';
                setTimeout(() => {
                    saveStatus.classList.add('hidden');
                }, 300);
            }, 1000);
        }
    }

    // --- Event Listeners ---

    function setupEventListeners() {
        // Editor Typing
        editor.addEventListener('input', () => {
            updateStats();
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(saveState, 1000);
        });

        // Font Controls
        fontFamilySelect.addEventListener('change', () => {
            editor.style.fontFamily = fontFamilySelect.value;
            saveState();
        });

        fontSizeSelect.addEventListener('change', () => {
            editor.style.fontSize = fontSizeSelect.value;
            saveState();
        });

        // Theme Toggle
        themeToggle.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            document.documentElement.setAttribute('data-theme', newTheme);
            updateThemeIcon(newTheme);
            saveState();
        });

        // Language
        langSelect.addEventListener('change', () => {
            saveState();
            setTimeout(() => {
                window.location.search = `?lang=${langSelect.value}`;
            }, 200);
        });

        // Buttons
        btnNew.addEventListener('click', () => {
            if (confirm('Create new note? Current content will be cleared.')) {
                editor.value = '';
                updateStats();
                saveState();
            }
        });

        // View Toggles
        if (btnNarrowMode) btnNarrowMode.addEventListener('click', toggleNarrowMode);
        if (btnFocus) btnFocus.addEventListener('click', toggleFocusMode);

        btnSave.addEventListener('click', () => {
            saveState();
            downloadFile();
        });

        btnOpen.addEventListener('click', () => {
            fileInput.click();
        });

        fileInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                editor.value = e.target.result;
                updateStats();
                saveState();
                // Reset input so same file can be selected again
                fileInput.value = '';
            };
            reader.readAsText(file);
        });
    }

    // --- Helpers ---

    function updateThemeIcon(theme) {
        const svg = themeToggle.querySelector('svg');
        if (theme === 'dark') {
            // Moon
            svg.innerHTML = '<path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>';
            svg.classList.remove('sun');
            svg.classList.add('moon');
        } else {
            // Sun
            svg.innerHTML = '<circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>';
            svg.classList.remove('moon');
            svg.classList.add('sun');
        }
    }

    function downloadFile() {
        const blob = new Blob([editor.value], { type: 'text/plain' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `Note_${new Date().toISOString().slice(0, 10)}.txt`;
        a.click();
        URL.revokeObjectURL(url);
    }

    // Run
    init();
});
