document.addEventListener('DOMContentLoaded', () => {
    // DOM Elements
    const timerDisplay = document.getElementById('timer-display');
    const startBtn = document.getElementById('start-btn');
    const resetBtn = document.getElementById('reset-btn');
    const modeBtns = document.querySelectorAll('.mode-btn');
    const themeToggle = document.getElementById('theme-toggle');
    const langSelect = document.getElementById('lang-select');

    const taskInput = document.getElementById('task-input');
    const addTaskBtn = document.getElementById('add-task-btn');
    const taskList = document.getElementById('task-list');

    const sessionsCount = document.getElementById('sessions-count');
    const completedTasksCount = document.getElementById('completed-tasks-count');
    const alertSound = document.getElementById('alert-sound');

    // State
    let timeLeft = 25 * 60;
    let timerId = null;
    let currentMode = 'work';
    let tasks = JSON.parse(localStorage.getItem('pomodoro_tasks')) || [];
    let stats = JSON.parse(localStorage.getItem('pomodoro_stats')) || { sessions: 0 };

    const durations = {
        work: 25 * 60,
        short: 5 * 60,
        long: 15 * 60
    };

    // --- Initialization ---
    function init() {
        renderTasks();
        updateStatsDisplay();
        switchMode('work');
        setupEventListeners();
    }

    // --- Timer Logic ---
    function updateDisplay() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        timerDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        document.title = `${timerDisplay.textContent} - Focus Flow`;
    }

    function startTimer() {
        if (timerId) {
            clearInterval(timerId);
            timerId = null;
            startBtn.textContent = document.documentElement.lang === 'en' ? 'Start' : '...'; // fallback
            // Better: use data attributes for translations in script.js or just refresh text from DOM if possible
            // But let's keep it simple for now and just toggle state
            return;
        }

        timerId = setInterval(() => {
            document.body.classList.add('running');
            timeLeft--;
            updateDisplay();

            if (timeLeft <= 0) {
                clearInterval(timerId);
                timerId = null;
                document.body.classList.remove('running');
                startBtn.textContent = startBtn.dataset.start;
                handleTimerComplete();
            }
        }, 1000);
    }

    function handleTimerComplete() {
        document.body.classList.remove('running');
        alertSound.play();
        if (currentMode === 'work') {
            stats.sessions++;
            saveStats();
            updateStatsDisplay();
        }

        // Auto switch break? No, let user decide or just alert
        alert('Time is up!');
        resetTimer();
    }

    function resetTimer() {
        clearInterval(timerId);
        timerId = null;
        document.body.classList.remove('running');
        startBtn.textContent = startBtn.dataset.start;
        timeLeft = durations[currentMode];
        updateDisplay();
    }

    function switchMode(mode) {
        currentMode = mode;
        timeLeft = durations[mode];

        modeBtns.forEach(btn => {
            btn.classList.toggle('active', btn.dataset.mode === mode);
        });

        updateDisplay();
    }

    // --- Task Logic ---
    function addTask() {
        const text = taskInput.value.trim();
        if (!text) return;

        const task = {
            id: Date.now(),
            text,
            completed: false
        };

        tasks.push(task);
        taskInput.value = '';
        saveTasks();
        renderTasks();
    }

    function toggleTask(id) {
        tasks = tasks.map(task => {
            if (task.id === id) return { ...task, completed: !task.completed };
            return task;
        });
        saveTasks();
        renderTasks();
        updateStatsDisplay();
    }

    function deleteTask(id) {
        tasks = tasks.filter(task => task.id !== id);
        saveTasks();
        renderTasks();
        updateStatsDisplay();
    }

    function renderTasks() {
        taskList.innerHTML = '';
        tasks.forEach(task => {
            const li = document.createElement('li');
            li.className = `task-item ${task.completed ? 'completed' : ''}`;
            li.innerHTML = `
                <div class="task-checkbox ${task.completed ? 'checked' : ''}" onclick="toggleTask(${task.id})">
                    ${task.completed ? '✓' : ''}
                </div>
                <span class="task-text">${task.text}</span>
                <span class="task-delete" onclick="deleteTask(${task.id})">✕</span>
            `;
            taskList.appendChild(li);
        });
    }

    // Expose to window for inline onclicks
    window.toggleTask = toggleTask;
    window.deleteTask = deleteTask;

    function saveTasks() {
        localStorage.setItem('pomodoro_tasks', JSON.stringify(tasks));
    }

    function saveStats() {
        localStorage.setItem('pomodoro_stats', JSON.stringify(stats));
    }

    function updateStatsDisplay() {
        sessionsCount.textContent = stats.sessions;
        completedTasksCount.textContent = tasks.filter(t => t.completed).length;
    }

    // --- Event Listeners ---
    function setupEventListeners() {
        startBtn.addEventListener('click', () => {
            if (timerId) {
                clearInterval(timerId);
                timerId = null;
                document.body.classList.remove('running');
                startBtn.textContent = startBtn.dataset.start;
            } else {
                startTimer();
                startBtn.textContent = startBtn.dataset.pause;
            }
        });

        resetBtn.addEventListener('click', resetTimer);

        modeBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                if (timerId) {
                    if (confirm('Timer is running. Switch mode and reset?')) {
                        switchMode(btn.dataset.mode);
                        clearInterval(timerId);
                        timerId = null;
                        startBtn.textContent = 'Start';
                    }
                } else {
                    switchMode(btn.dataset.mode);
                }
            });
        });

        themeToggle.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', newTheme);

            // Save to session via API
            const params = new URLSearchParams();
            params.append('action', 'save_settings');
            params.append('theme', newTheme);
            fetch('api.php', { method: 'POST', body: params });

            // Update icon
            updateThemeIcon(newTheme);
        });

        langSelect.addEventListener('change', () => {
            window.location.search = `?lang=${langSelect.value}`;
        });

        addTaskBtn.addEventListener('click', addTask);
        taskInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') addTask();
        });
    }

    function updateThemeIcon(theme) {
        if (theme === 'dark') {
            themeToggle.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>';
        } else {
            themeToggle.innerHTML = '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>';
        }
    }

    init();
});
