const csrf = document.querySelector('meta[name="csrf-token"]').content;
const letter = (index) => 'ABCDEF'[index];

function element(tag, className, label) {
    const node = document.createElement(tag);
    if (className) node.className = className;
    if (label !== undefined) node.textContent = label;
    return node;
}

function button(label, action, kind = 'quiet') {
    const node = element('button', 'button ' + kind, label);
    node.type = 'button';
    node.dataset.action = action;
    return node;
}

async function request(url, data) {
    const response = await fetch(url, {
        credentials: 'same-origin',
        cache: 'no-store',
        signal: AbortSignal.timeout(10000),
        ...(data === undefined ? {} : {
            method: 'POST',
            body: JSON.stringify(data),
            keepalive: true,
        }),
        headers: {
            Accept: 'application/json',
            ...(data === undefined ? {} : {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf}),
        },
    });
    if (!response.ok || response.redirected) {
        const body = await response.json().catch(() => ({}));
        const error = new Error(response.status === 419
            ? 'Phiên trình duyệt hết hạn. Hãy tải lại trang.'
            : response.status === 403 || response.redirected
                ? 'Phiên truy cập đã hết hạn. Hãy mở lại trang từ liên kết của giảng viên.'
                : body.message || 'Không xử lý được yêu cầu. Vui lòng thử lại.');
        error.status = response.redirected ? 403 : response.status;
        throw error;
    }
    return response.status === 204 ? null : response.json();
}

function startArena(root) {
    const host = root.dataset.quiz === 'host';
    const board = root.querySelector('[data-board]');
    const actions = root.querySelector('[data-actions]');
    const feedback = root.querySelector('[data-feedback]');
    const meta = root.querySelector('[data-meta]');
    const timer = root.querySelector('[data-timer]');
    const count = root.querySelector('[data-count]');
    const phase = root.querySelector('[data-phase]');
    const chart = root.querySelector('[data-chart]');
    const leaders = root.querySelector('[data-leaders]');
    const phases = {
        lobby: 'Phòng chờ', open: 'Đang mở', paused: 'Tạm dừng',
        closed: 'Đã khóa câu hỏi', revealed: 'Đã hiện đáp án', finished: 'Đã kết thúc',
    };
    const pending = new Map();
    let state;
    let renderKey;
    let deadline;
    let saving = false;
    let busy = false;
    let serial = 0;
    let disconnected = false;
    let chartOpen = false;
    let leadersOpen = false;

    function message(text, error = false) {
        feedback.textContent = text;
        feedback.toggleAttribute('data-error', error);
    }

    function secondsLeft() {
        return deadline === null ? null : Math.max(0, Math.ceil((deadline - performance.now()) / 1000));
    }

    function clock() {
        if (!state) return;
        const seconds = state.phase === 'paused' ? state.pausedSeconds : secondsLeft();
        let time = '';
        if (seconds !== null && seconds !== undefined && ['open', 'paused'].includes(state.phase)) {
            time = 'Còn ' + Math.floor(seconds / 60) + ':' + String(seconds % 60).padStart(2, '0');
        }
        if (timer.textContent !== time) timer.textContent = time;
        if (state.phase === 'open' && !host) {
            board.querySelectorAll('[data-choice], [data-send]').forEach((node) => {
                node.disabled = seconds === 0 || busy || node.dataset.locked === 'true';
            });
        }
    }

    function placeholder(title, detail) {
        const panel = element('div', 'arena placeholder');
        panel.append(element('h2', '', title), element('p', '', detail));
        return panel;
    }

    function questionPanel(question) {
        const panel = element('section', 'arena');
        panel.append(
            element('span', 'question-count', 'Câu hỏi ' + (question.position + 1) + '/' + state.total),
            element('h2', '', question.prompt),
        );
        if (question.image) {
            const image = element('img', 'question-image');
            image.src = question.image;
            image.alt = 'Ảnh minh họa câu ' + (question.position + 1);
            panel.append(image);
        }
        const form = element('form');
        form.dataset.position = question.position;
        const options = element('div', 'options');
        const selected = pending.get(question.position) ?? question.choices ?? [];
        const locked = state.phase !== 'open' || (state.mode === 'live' && question.choices !== null);
        if (!host) {
            form.append(element('p', 'question-help', question.multiple ? 'Chọn nhiều đáp án.' : 'Chọn một đáp án.'));
        }
        question.options.forEach((option, index) => {
            const choice = element(host ? 'div' : 'label', 'option');
            if (!host) {
                const input = element('input');
                input.type = question.multiple ? 'checkbox' : 'radio';
                input.name = 'choice';
                input.value = index;
                input.checked = selected.includes(index);
                input.disabled = locked;
                input.dataset.choice = '';
                input.dataset.locked = locked;
                choice.append(input);
            }
            choice.append(element('span', '', letter(index) + '. ' + option));
            if (question.correct?.includes(index)) {
                choice.dataset.correct = '';
                choice.append(element('span', 'choice-status', 'Đáp án đúng'));
            }
            options.append(choice);
        });
        form.append(options);
        if (!host && !locked && state.mode === 'live') {
            const send = button('Gửi đáp án', 'send', 'primary');
            send.type = 'submit';
            send.dataset.send = '';
            form.append(send);
        } else if (!host && !locked && state.mode === 'self') {
            form.append(button('Xóa lựa chọn', 'clear'));
        }
        if (!host && state.mode === 'live' && question.choices !== null) {
            form.append(element('p', 'explanation', 'Đã ghi nhận đáp án của bạn.'));
        }
        panel.append(form);
        if (question.explanation) panel.append(element('p', 'explanation', question.explanation));
        return panel;
    }

    function render() {
        const signature = JSON.stringify([state.phase, state.version, state.attempt, state.questions, state.result]);
        if (signature === renderKey) return;
        renderKey = signature;
        board.replaceChildren();
        actions.replaceChildren();

        if (state.phase === 'finished') {
            const result = state.result;
            board.append(placeholder('Đã hoàn thành', result
                ? 'Bạn trả lời đúng ' + result.score + '/' + result.total + ' câu.'
                : 'Kết quả đã được ghi nhận.'));
            if (host && state.mode === 'live') actions.append(button('Bảng xếp hạng', 'leaders'));
            if (state.retry) actions.append(button('Làm lại (' + (state.attemptLimit - state.attempt) + ' lượt còn lại)', 'retry', 'primary'));
            return;
        }
        if (state.phase === 'lobby') {
            const self = state.mode === 'self';
            board.append(placeholder(self ? 'Phòng tự làm đang mở' : 'Phòng chờ',
                self ? 'Học viên có thể mở liên kết để làm bài ngay.'
                    : host ? 'Chia sẻ mã QR hoặc mã PIN để học viên tham gia.' : 'Bài thi sẽ xuất hiện khi giảng viên bắt đầu.'));
            if (host && !self) actions.append(button('Bắt đầu', 'start', 'primary'));
        } else {
            for (const question of state.questions) board.append(questionPanel(question));
        }

        if (host) {
            if (state.phase === 'open') actions.append(button('Tạm dừng', 'pause'), button('Kết thúc câu', 'close'));
            if (state.phase === 'paused') actions.append(button('Tiếp tục', 'resume', 'primary'), button('Kết thúc câu', 'close'));
            if (state.phase === 'closed') actions.append(button('Hiện đáp án', 'reveal', 'primary'));
            if (['closed', 'revealed'].includes(state.phase)) {
                actions.append(button('Hiện kết quả', 'chart'), button('Bảng xếp hạng', 'leaders'));
                if (state.questions[0].position + 1 < state.total) actions.append(button('Chuyển câu', 'next', 'primary'));
            }
            actions.append(button('Kết thúc phiên', 'finish', 'danger'));
        } else if (state.mode === 'self') {
            actions.append(button('Nộp bài', 'submit', 'primary'));
        }
    }

    function drawStats() {
        if (!host) return;
        const question = state.questions[0];
        const showChart = chartOpen && question && ['closed', 'revealed'].includes(state.phase);
        const showLeaders = leadersOpen && ['closed', 'revealed', 'finished'].includes(state.phase);
        chart.hidden = !showChart;
        leaders.hidden = !showLeaders;
        if (showChart) {
            chart.replaceChildren(element('h2', '', 'Tỷ lệ lựa chọn đáp án'));
            const list = element('div');
            question.options.forEach((option, index) => {
                const votes = state.counts[index] ?? 0;
                const percent = state.answered ? Math.round(100 * votes / state.answered) : 0;
                const row = element('div', 'chart-row');
                const track = element('div', 'chart-track');
                track.setAttribute('aria-hidden', 'true');
                const bar = element('div', 'chart-bar');
                bar.style.width = percent + '%';
                track.append(bar);
                row.append(element('span', '', letter(index) + '. ' + option + ' · ' + votes + ' lượt (' + percent + '%)'), track);
                list.append(row);
            });
            chart.append(list);
        }
        if (showLeaders) {
            const list = element('ol');
            state.leaders.forEach((row) => list.append(element('li', '', row.label + ' · ' + row.score + ' điểm')));
            leaders.replaceChildren(element('h2', '', 'Bảng xếp hạng'), list.childElementCount ? list : element('p', '', 'Chưa có điểm được ghi nhận.'));
        }
        actions.querySelector('[data-action="chart"]')?.setAttribute('aria-expanded', Boolean(showChart));
        actions.querySelector('[data-action="leaders"]')?.setAttribute('aria-expanded', Boolean(showLeaders));
    }

    async function refresh() {
        const id = ++serial;
        const started = performance.now();
        const incoming = await request(root.dataset.state);
        if (id !== serial) return;
        if (state?.questions[0]?.position !== incoming.questions[0]?.position) chartOpen = false;
        state = incoming;
        deadline = state.deadline ? (started + performance.now()) / 2 + Date.parse(state.deadline) - Date.parse(state.now) : null;
        phase.textContent = phases[state.phase] ?? state.phase;
        meta.hidden = false;
        count.textContent = state.members + ' học viên' + (host && state.questions.length
            ? ' · ' + state.answered + ' đã trả lời'
            : host && state.completed !== null ? ' · ' + state.completed + ' lượt đã nộp' : '');
        if (disconnected) message('Đã kết nối lại.');
        disconnected = false;

        // While editing a self-paced attempt, preserve focused controls and local selections.
        if (state.mode === 'self' && !host && state.phase === 'open' && board.querySelector('[data-choice]')) {
            clock();
            return;
        }
        render();
        drawStats();
        clock();
    }

    async function flush() {
        if (saving) return;
        saving = true;
        try {
            while (pending.size && state.phase === 'open') {
                const [position, choices] = pending.entries().next().value;
                await request(root.dataset.answer, {position, choices});
                if (pending.get(position) === choices) pending.delete(position);
            }
            if (!pending.size) message('Đã tự lưu lựa chọn.');
        } catch (error) {
            if (error.status === 409) {
                pending.clear();
                await refresh().catch(() => {});
            }
            message(error.status === 409 ? error.message : 'Chưa lưu được lựa chọn. Hệ thống sẽ thử lại khi kết nối ổn định.', true);
        } finally {
            saving = false;
        }
    }

    async function poll() {
        try {
            if (!busy) await refresh();
            if (pending.size && !busy && state.phase === 'open') void flush();
        } catch (error) {
            if (error.status === 404) {
                serial++;
                state = undefined;
                pending.clear();
                meta.hidden = true;
                phase.textContent = 'Đã đóng';
                board.replaceChildren(placeholder('Phòng thi không còn khả dụng', 'Mã PIN và liên kết của phòng này không còn sử dụng được.'));
                actions.replaceChildren();
                if (host) chart.hidden = leaders.hidden = true;
                message('');
                return;
            }
            disconnected = true;
            message(error.status === 403 ? error.message : 'Đang kết nối lại với phòng thi…', true);
            if (error.status === 403) return;
        }
        if (state?.phase !== 'finished' || state.retry) {
            window.setTimeout(poll, document.hidden ? 8000 : state?.mode === 'self' ? 4000 : 1800);
        }
    }

    function formChoices(form) {
        return [...form.querySelectorAll('input:checked')].map((node) => Number(node.value));
    }

    function save(form) {
        pending.set(Number(form.dataset.position), formChoices(form));
        message('Đang lưu lựa chọn…');
        void flush();
    }

    async function perform(action, data = {}) {
        if (busy) return;
        busy = true;
        actions.querySelectorAll('button').forEach((node) => { node.disabled = true; });
        clock();
        try {
            if (action === 'submit') {
                if (saving) {
                    message('Đang lưu đáp án. Hãy thử nộp lại sau khi lưu xong.');
                    return;
                }
                if (pending.size) await flush();
                if (pending.size) return;
            }
            const endpoint = host ? 'control' : action;
            await request(root.dataset[endpoint], host ? {action, version: state.version} : data);
            if (action === 'retry') pending.clear();
            message(action === 'answer' ? 'Đã gửi đáp án.' : '');
            if (action === 'retry') board.replaceChildren();
            await refresh();
        } catch (error) {
            await refresh().catch(() => {});
            message(error.message, true);
        } finally {
            busy = false;
            actions.querySelectorAll('button').forEach((node) => { node.disabled = false; });
            clock();
        }
    }

    root.addEventListener('change', (event) => {
        if (event.target.matches('[data-choice]') && state.mode === 'self') save(event.target.form);
    });
    root.addEventListener('submit', (event) => {
        event.preventDefault();
        if (host || state.mode !== 'live') return;
        const choices = formChoices(event.target);
        if (!choices.length) return message('Chọn đáp án trước khi gửi.', true);
        void perform('answer', {position: Number(event.target.dataset.position), choices});
    });
    root.addEventListener('click', (event) => {
        const target = event.target.closest('[data-action]');
        if (!target) return;
        const action = target.dataset.action;
        if (action === 'send') return;
        if (action === 'clear') {
            if (secondsLeft() === 0 || busy) return;
            const form = target.closest('form');
            form.querySelectorAll('input:checked').forEach((input) => { input.checked = false; });
            save(form);
            return;
        }
        if (action === 'chart' || action === 'leaders') {
            if (action === 'chart') chartOpen = !chartOpen;
            else leadersOpen = !leadersOpen;
            drawStats();
            return;
        }
        if (action === 'finish' && !confirm('Kết thúc phiên và chốt kết quả cho tất cả học viên?')) return;
        if (action === 'submit' && !confirm('Nộp và chấm điểm bài làm hiện tại?')) return;
        if (action === 'retry' && !confirm('Bắt đầu lượt làm bài mới ngay bây giờ?')) return;
        void perform(action);
    });
    window.setInterval(clock, 250);
    void poll();
}

function startBuilder(builder) {
    const picker = builder.querySelector('[data-question-picker]');
    const list = builder.querySelector('[data-question-list]');
    const feedback = builder.querySelector('[data-builder-feedback]');
    builder.addEventListener('click', (event) => {
        const target = event.target;
        if (target.matches('[data-add-question]')) {
            if (!picker.value) {
                feedback.textContent = 'Chọn câu hỏi cần thêm.';
                return;
            }
            if ([...list.querySelectorAll('input')].some((input) => input.value === picker.value)) {
                feedback.textContent = 'Câu hỏi đã có trong bộ đề.';
                return;
            }
            const item = element('li');
            const input = element('input');
            input.type = 'hidden';
            input.name = 'questions[]';
            input.value = picker.value;
            const controls = element('div', 'actions');
            for (const [label, action] of [['Lên', 'up'], ['Xuống', 'down'], ['Bỏ', 'remove']]) {
                const control = button(label, action);
                control.dataset.order = action;
                controls.append(control);
            }
            item.append(input, element('span', '', picker.selectedOptions[0].textContent), controls);
            list.append(item);
            picker.value = '';
            feedback.textContent = 'Đã thêm câu hỏi.';
        }
        if (!target.matches('[data-order]')) return;
        const item = target.closest('li');
        if (target.dataset.order === 'up' && item.previousElementSibling) {
            item.previousElementSibling.before(item);
            target.focus();
        } else if (target.dataset.order === 'down' && item.nextElementSibling) {
            item.nextElementSibling.after(item);
            target.focus();
        } else if (target.dataset.order === 'remove') {
            item.remove();
            picker.focus();
        }
        feedback.textContent = 'Đã cập nhật thứ tự câu hỏi.';
    });
}

document.querySelectorAll('[data-quiz]').forEach(startArena);
document.querySelectorAll('[data-quiz-builder]').forEach(startBuilder);
document.querySelectorAll('[data-room-form]').forEach((form) => {
    const options = form.querySelector('[data-self-settings]');
    const mode = form.elements.namedItem('mode');
    const sync = () => {
        options.hidden = mode.value !== 'self';
        options.querySelectorAll('input').forEach((input) => { input.disabled = options.hidden; });
    };
    mode.addEventListener('change', sync);
    sync();
});
document.querySelectorAll('form[data-confirm]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!confirm(form.dataset.confirm)) event.preventDefault();
    });
});
document.querySelectorAll('[data-copy]').forEach((target) => {
    target.addEventListener('click', async () => {
        try {
            await navigator.clipboard.writeText(target.dataset.copy);
            target.textContent = 'Đã sao chép';
        } catch {
            target.textContent = 'Hãy chọn và sao chép liên kết bên trên';
        }
    });
});

