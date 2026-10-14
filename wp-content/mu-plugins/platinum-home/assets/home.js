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
})();
