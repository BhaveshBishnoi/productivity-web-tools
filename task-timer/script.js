document.addEventListener('DOMContentLoaded', () => {
    // State Management
    let tasks = JSON.parse(localStorage.getItem('tasks')) || [];
    let activeTimerId = null;
    let focusedTaskId = null; // Track which task is being viewed in focus mode
    let timerInterval = null;
    let isFocusMode = false;

    // DOM Elements
    const taskNameInput = document.getElementById('task-name');
    const taskDescInput = document.getElementById('task-desc');
    const taskDurationInput = document.getElementById('task-duration');
    const addTaskBtn = document.getElementById('add-task-btn');
    const tasksList = document.getElementById('tasks-list');
    const taskCount = document.getElementById('task-count');
    const themeToggle = document.getElementById('theme-toggle');
    const currentTimerDisplay = document.getElementById('current-time');

    // Focus Elements
    const focusOverlay = document.getElementById('focus-overlay');
    const exitFocusBtn = document.getElementById('exit-focus-btn');
    const focusTaskName = document.getElementById('focus-task-name');
    const focusTaskDesc = document.getElementById('focus-task-desc');
    const focusTimerDisplay = document.getElementById('focus-timer-display');
    const focusToggleBtn = document.getElementById('focus-toggle-btn');

    // Toast Elements
    const toast = document.getElementById('notification-toast');
    const toastTitle = document.getElementById('toast-title');
    const toastMsg = document.getElementById('toast-msg');
    const closeToastBtn = document.getElementById('close-toast');
    const beep = document.getElementById('timer-beep');

    // Initialize
    loadState();
    renderTasks();
    updateClock();
    setInterval(updateClock, 1000);

    // Event Listeners
    if (addTaskBtn) addTaskBtn.addEventListener('click', addTask);
    if (taskNameInput) taskNameInput.addEventListener('keypress', (e) => e.key === 'Enter' && addTask());

    themeToggle.addEventListener('click', () => {
        const currentTheme = document.documentElement.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon(newTheme);
    });

    closeToastBtn.addEventListener('click', hideToast);
    exitFocusBtn.addEventListener('click', exitFocusMode);

    focusToggleBtn.addEventListener('click', () => {
        if (focusedTaskId) {
            startTaskTimer(focusedTaskId);
        }
    });

    // --- Core Functions ---

    function loadState() {
        // If there was an active timer saved, we could resume it here
        // For simplicity, we just load the tasks. 
        // Timers are stopped on reload in this version.
    }

    function addTask() {
        const name = taskNameInput.value.trim();
        const desc = taskDescInput.value.trim();
        const duration = parseInt(taskDurationInput.value);

        if (!name) return;
        if (isNaN(duration) || duration <= 0) {
            alert('Please enter a valid duration in minutes.');
            return;
        }

        const newTask = {
            id: Date.now().toString(),
            name: name,
            desc: desc,
            duration: duration,
            remaining: duration * 60,
            originalDuration: duration,
            completed: false,
            createdAt: new Date().toISOString()
        };

        tasks.unshift(newTask);
        saveTasks();
        renderTasks();

        // Reset Inputs
        taskNameInput.value = '';
        taskDescInput.value = '';
        taskDurationInput.value = '';
        taskNameInput.focus();
    }

    function toggleTask(id) {
        tasks = tasks.map(task => {
            if (task.id === id) {
                return { ...task, completed: !task.completed };
            }
            return task;
        });
        saveTasks();
        renderTasks();
    }

    function deleteTask(id) {
        if (activeTimerId === id) {
            stopTimer();
        }
        tasks = tasks.filter(task => task.id !== id);
        saveTasks();
        renderTasks();
        if (isFocusMode && focusedTaskId === id) exitFocusMode();
    }

    function startTaskTimer(id) {
        // If clicking the same task that is already running -> Stop it
        if (activeTimerId === id) {
            stopTimer();
            return;
        }

        // Stop any currently running timer before starting a new one
        if (activeTimerId) {
            stopTimer();
        }

        activeTimerId = id;
        focusedTaskId = id; // Ensure focusedTaskId is synced if we start from list

        const task = tasks.find(t => t.id === id);
        if (!task || task.completed) {
            activeTimerId = null;
            return;
        }

        timerInterval = setInterval(() => {
            const taskIdx = tasks.findIndex(t => t.id === id);
            if (taskIdx === -1) {
                stopTimer();
                return;
            }

            if (tasks[taskIdx].remaining > 0) {
                tasks[taskIdx].remaining--;
                updateDisplays(id);
            } else {
                completeTimer(id);
            }
        }, 1000);

        if (!isFocusMode) {
            enterFocusMode(id);
        } else {
            updateFocusModeUI();
        }
        renderTasks();
    }

    function stopTimer() {
        clearInterval(timerInterval);
        timerInterval = null;
        activeTimerId = null;
        renderTasks();
        saveTasks();
        updateFocusModeUI();
    }

    function completeTimer(id) {
        clearInterval(timerInterval);
        timerInterval = null;
        const task = tasks.find(t => t.id === id);

        showToast('Timer Finished!', `Time's up for: ${task.name}`);
        playBeep();

        activeTimerId = null;
        task.completed = true;
        task.remaining = 0;

        updateFocusModeUI();
        renderTasks();
        saveTasks();
    }

    function enterFocusMode(id) {
        const task = tasks.find(t => t.id === id);
        if (!task) return;

        focusedTaskId = id;
        isFocusMode = true;

        focusTaskName.textContent = task.name;
        focusTaskDesc.textContent = task.desc || '';
        focusOverlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        updateDisplays(id);
        updateFocusModeUI();
    }

    function exitFocusMode() {
        isFocusMode = false;
        focusedTaskId = null;
        focusOverlay.classList.add('hidden');
        document.body.style.overflow = 'auto';
        renderTasks();
    }

    function updateFocusModeUI() {
        if (!isFocusMode) return;

        if (activeTimerId === focusedTaskId && activeTimerId !== null) {
            focusToggleBtn.innerHTML = '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="4" width="4" height="16"></rect><rect x="14" y="4" width="4" height="16"></rect></svg>';
        } else {
            focusToggleBtn.innerHTML = '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>';
        }
    }

    // --- UI Helpers ---

    function renderTasks() {
        if (!tasksList) return;

        if (tasks.length === 0) {
            tasksList.innerHTML = `
                <div class="empty-state">
                    <p>No tasks yet. Start by adding one above!</p>
                </div>
            `;
            taskCount.textContent = '0';
            return;
        }

        taskCount.textContent = tasks.length;
        tasksList.innerHTML = '';

        tasks.forEach(task => {
            const isActive = activeTimerId === task.id;
            const taskEl = document.createElement('div');
            taskEl.className = `task-item glass ${task.completed ? 'task-completed' : ''} ${isActive ? 'timer-active' : ''}`;

            taskEl.innerHTML = `
                <div class="task-main">
                    <div class="task-checkbox-wrapper" onclick="event.stopPropagation(); window.toggleTask('${task.id}')">
                        <div class="custom-checkbox">
                            ${task.completed ? '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="4"><polyline points="20 6 9 17 4 12"></polyline></svg>' : ''}
                        </div>
                    </div>
                    <div class="task-info" onclick="window.enterFocusMode('${task.id}')" style="cursor:pointer">
                        <div class="task-title">${escapeHtml(task.name)}</div>
                        <div class="task-meta">
                            <span>${task.originalDuration} min</span>
                            <span>•</span>
                            <span>${new Date(task.createdAt).toLocaleDateString()}</span>
                        </div>
                    </div>
                    <div class="task-actions">
                        <div class="timer-pill" id="timer-${task.id}">${formatTime(task.remaining)}</div>
                        <button class="icon-btn" onclick="event.stopPropagation(); window.startTaskTimer('${task.id}')">
                            ${isActive ?
                    '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="4" width="4" height="16"></rect><rect x="14" y="4" width="4" height="16"></rect></svg>' :
                    '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>'
                }
                        </button>
                        <button class="icon-btn delete-btn" onclick="event.stopPropagation(); window.deleteTask('${task.id}')">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                        </button>
                    </div>
                </div>
                ${task.desc ? `<div class="task-description">${escapeHtml(task.desc)}</div>` : ''}
            `;
            tasksList.appendChild(taskEl);
        });
    }

    function updateDisplays(id) {
        const task = tasks.find(t => t.id === id);
        if (!task) return;

        const timeStr = formatTime(task.remaining);

        // Update list display
        const display = document.getElementById(`timer-${id}`);
        if (display) display.textContent = timeStr;

        // Update focus display
        if (isFocusMode && focusedTaskId === id) {
            focusTimerDisplay.textContent = timeStr;
        }
    }

    function formatTime(seconds) {
        const m = Math.floor(seconds / 60);
        const s = seconds % 60;
        return `${m}:${s.toString().padStart(2, '0')}`;
    }

    function updateClock() {
        const now = new Date();
        currentTimerDisplay.textContent = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    function saveTasks() {
        localStorage.setItem('tasks', JSON.stringify(tasks));
    }

    function showToast(title, msg) {
        toastTitle.textContent = title;
        toastMsg.textContent = msg;
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('visible'), 100);
        setTimeout(hideToast, 10000);
    }

    function hideToast() {
        toast.classList.remove('visible');
        setTimeout(() => toast.classList.add('hidden'), 500);
    }

    function playBeep() {
        beep.currentTime = 0;
        beep.play().catch(e => console.log('Audio play blocked:', e));
    }

    function updateThemeIcon(theme) {
        const btn = document.getElementById('theme-toggle');
        if (!btn) return;
        if (theme === 'light') {
            btn.innerHTML = '<svg class="moon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>';
        } else {
            btn.innerHTML = '<svg class="sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>';
        }
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Expose functions to window
    window.toggleTask = toggleTask;
    window.deleteTask = deleteTask;
    window.startTaskTimer = startTaskTimer;
    window.enterFocusMode = enterFocusMode;

    const savedTheme = localStorage.getItem('theme') || 'dark';
    document.documentElement.setAttribute('data-theme', savedTheme);
    updateThemeIcon(savedTheme);
});
