(() => {
	'use strict';
	const initialize = () => {
		const widget = document.getElementById('accessibility-widget');
		if (!widget) return;
		const toggle = document.getElementById('accessibility-toggle');
		const panel = document.getElementById('accessibility-panel');
		const increase = document.getElementById('accessibility-text-increase');
		const decrease = document.getElementById('accessibility-text-decrease');
		const textStatus = document.getElementById('accessibility-text-status');
		const reset = document.getElementById('accessibility-reset');
		const keys = {
			text: 'ecuadoradio.accessibility.textLevel',
			grayscale: 'ecuadoradio.accessibility.grayscale',
			contrast: 'ecuadoradio.accessibility.highContrast',
			negative: 'ecuadoradio.accessibility.negative',
			light: 'ecuadoradio.accessibility.light',
			links: 'ecuadoradio.accessibility.links',
			font: 'ecuadoradio.accessibility.font'
		};
		const read = key => {
			try { return localStorage.getItem(key); } catch { return null; }
		};
		const save = (key, value) => {
			try { localStorage.setItem(key, String(value)); } catch { /* Controls also work without storage. */ }
		};
		const savedLevel = Number(read(keys.text));
		let textLevel = Number.isInteger(savedLevel) ? Math.max(-2, Math.min(5, savedLevel)) : 0;
		const options = [
			{ key: 'contrast', id: 'high-contrast', color: true },
			{ key: 'negative', id: 'negative', color: true },
			{ key: 'light', id: 'light', color: true },
			{ key: 'grayscale', id: 'grayscale', color: true },
			{ key: 'links', id: 'links', color: false },
			{ key: 'font', id: 'font', color: false }
		].map(option => ({
			...option,
			button: document.getElementById(`accessibility-${option.id}`),
			enabled: read(keys[option.key]) === 'true'
		}));
		// Only one color mode may be active; reading aids can be combined freely.
		let activeColorFound = false;
		options.forEach(option => {
			if (!option.color || !option.enabled) return;
			if (activeColorFound) option.enabled = false;
			else activeColorFound = true;
		});
		let textElements = [];

		// A direct body child keeps the controls outside all content color filters.
		document.body.appendChild(widget);
		const restoreText = () => {
			textElements.forEach(({ element, inlineSize, priority }) => {
				if (inlineSize) element.style.setProperty('font-size', inlineSize, priority);
				else element.style.removeProperty('font-size');
			});
		};
		const collectText = () => {
			textElements = [];
			document.body.querySelectorAll('*').forEach(element => {
				if (widget.contains(element) || element.closest('script, style, noscript, svg, .fa, [aria-hidden="true"], #wpadminbar')) return;
				const hasText = /^(INPUT|TEXTAREA|SELECT)$/.test(element.tagName)
					|| [...element.childNodes].some(node => node.nodeType === Node.TEXT_NODE && /\S/.test(node.nodeValue));
				if (!hasText) return;
				const size = parseFloat(getComputedStyle(element).fontSize);
				if (Number.isFinite(size) && size > 0) textElements.push({
					element, size,
					inlineSize: element.style.getPropertyValue('font-size'),
					priority: element.style.getPropertyPriority('font-size')
				});
			});
		};
		const applyText = () => {
			const percentage = 100 + textLevel * 10;
			if (textLevel === 0) restoreText();
			else textElements.forEach(({ element, size }) => {
				element.style.setProperty('font-size', `${size * percentage / 100}px`, 'important');
			});
			document.body.classList.toggle('accessibility-text-adjusted', textLevel !== 0);
			increase.disabled = textLevel >= 5;
			decrease.disabled = textLevel <= -2;
			textStatus.textContent = `${textStatus.dataset.label} ${percentage}%`;
		};
		const applyOptions = () => {
			options.forEach(option => {
				document.body.classList.toggle(`accessibility-${option.id}`, option.enabled);
				option.button.setAttribute('aria-pressed', String(option.enabled));
			});
		};
		const setOpen = open => {
			toggle.setAttribute('aria-expanded', String(open));
			toggle.setAttribute('aria-label', open ? toggle.dataset.closeLabel : toggle.dataset.openLabel);
			panel.hidden = !open;
		};
		increase.addEventListener('click', () => {
			textLevel = Math.min(5, textLevel + 1);
			applyText();
			save(keys.text, textLevel);
		});
		decrease.addEventListener('click', () => {
			textLevel = Math.max(-2, textLevel - 1);
			applyText();
			save(keys.text, textLevel);
		});
		options.forEach(option => {
			option.button.addEventListener('click', () => {
				option.enabled = !option.enabled;
				if (option.color && option.enabled) {
					options.forEach(other => {
						if (other.color && other !== option) other.enabled = false;
					});
				}
				applyOptions();
				options.forEach(other => save(keys[other.key], other.enabled));
			});
		});
		reset.addEventListener('click', () => {
			textLevel = 0;
			options.forEach(option => { option.enabled = false; });
			applyText();
			applyOptions();
			Object.values(keys).forEach(key => {
				try { localStorage.removeItem(key); } catch { /* Restore even when storage is blocked. */ }
			});
		});
		toggle.addEventListener('click', () => setOpen(panel.hidden));
		document.addEventListener('keydown', event => {
			if (event.key === 'Escape' && !panel.hidden) {
				setOpen(false);
				toggle.focus();
			}
		});
		document.addEventListener('click', event => {
			if (!widget.contains(event.target)) {
				if (panel.contains(document.activeElement)) toggle.focus();
				setOpen(false);
			}
		});
		let resizeTimer;
		window.addEventListener('resize', () => {
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(() => {
				restoreText();
				document.body.classList.remove('accessibility-text-adjusted');
				collectText();
				applyText();
			}, 150);
		});
		collectText();
		applyText();
		applyOptions();
		widget.hidden = false;
	};
	if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initialize, { once: true });
	else initialize();
})();
