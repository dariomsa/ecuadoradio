(() => {
	const notice = document.querySelector('.platinum-notice');
	let noticeTimer;
	document.querySelectorAll('[data-placeholder]').forEach(link => {
		link.addEventListener('click', event => {
			event.preventDefault();
			if (!notice) return;
			notice.hidden = false;
			clearTimeout(noticeTimer);
			noticeTimer = setTimeout(() => { notice.hidden = true; }, 3500);
		});
	});
	const audio = document.querySelector('.platinum-player audio');
	if (audio) {
		audio.addEventListener('error', () => {
			const message = document.querySelector('.platinum-stream-message');
			if (message) message.hidden = false;
		});
	}
	const dropdowns = [...document.querySelectorAll('.platinum-dropdown')];
	dropdowns.forEach(dropdown => {
		dropdown.addEventListener('toggle', () => {
			if (dropdown.open) dropdowns.forEach(other => {
				if (other !== dropdown) other.open = false;
			});
		});
	});
	document.addEventListener('click', event => {
		dropdowns.forEach(dropdown => {
			if (!dropdown.contains(event.target)) dropdown.open = false;
		});
	});
	document.addEventListener('keydown', event => {
		if (event.key === 'Escape') dropdowns.forEach(dropdown => { dropdown.open = false; });
	});
	document.querySelectorAll('[data-schedule-widget]').forEach(widget => {
		const tabs = [...widget.querySelectorAll('[data-schedule-tab]')];
		const panels = [...widget.querySelectorAll('[data-schedule-panel]')];
		const select = key => {
			tabs.forEach(tab => {
				const active = tab.dataset.scheduleTab === key;
				tab.setAttribute('aria-selected', String(active));
				tab.tabIndex = active ? 0 : -1;
			});
			panels.forEach(panel => { panel.hidden = panel.dataset.schedulePanel !== key; });
			const tab = tabs.find(item => item.dataset.scheduleTab === key);
			const tablist = widget.querySelector('.platinum-schedule-tabs');
			if (tab && tablist) tablist.scrollLeft = Math.max(0, tab.offsetLeft - (tablist.clientWidth - tab.offsetWidth) / 2);
		};
		const clock = new Intl.DateTimeFormat('en-US', {
			timeZone: 'America/Guayaquil', weekday: 'long', hour: '2-digit', minute: '2-digit', hourCycle: 'h23'
		});
		const updateOnAir = () => {
			const parts = Object.fromEntries(clock.formatToParts(new Date()).map(part => [part.type, part.value]));
			const now = Number(parts.hour) * 60 + Number(parts.minute);
			panels.forEach(panel => {
				panel.querySelectorAll('[data-schedule-start]').forEach(item => {
					const minutes = value => { const [hour, minute] = value.split(':').map(Number); return hour * 60 + minute; };
					const start = minutes(item.dataset.scheduleStart);
					const end = minutes(item.dataset.scheduleEnd) || 1440;
					const active = panel.dataset.schedulePanel === parts.weekday && now >= start && now < end;
					item.classList.toggle('is-on-air', active);
					if (active) item.setAttribute('aria-current', 'time');
					else item.removeAttribute('aria-current');
					const badge = item.querySelector('.platinum-on-air');
					if (badge) badge.hidden = !active;
				});
			});
			return parts.weekday;
		};
		select(updateOnAir());
		setInterval(updateOnAir, 30000);
		tabs.forEach((tab, index) => {
			tab.addEventListener('click', () => select(tab.dataset.scheduleTab));
			tab.addEventListener('keydown', event => {
			if (!['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(event.key)) return;
			event.preventDefault();
			let next = index;
			if (event.key === 'ArrowLeft') next = (index - 1 + tabs.length) % tabs.length;
			if (event.key === 'ArrowRight') next = (index + 1) % tabs.length;
			if (event.key === 'Home') next = 0;
			if (event.key === 'End') next = tabs.length - 1;
			tabs[next].focus();
			select(tabs[next].dataset.scheduleTab);
		});
	});
	});
	document.querySelectorAll('[data-demo-audio]').forEach(audio => {
		audio.addEventListener('click', () => {
			if (!notice) return;
			notice.textContent = 'Audio de demostración. Próximamente estará disponible.';
			notice.hidden = false;
			clearTimeout(noticeTimer);
			noticeTimer = setTimeout(() => { notice.hidden = true; }, 3500);
		});
	});
})();
