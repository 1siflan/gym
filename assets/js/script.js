/**
 * main.js - Core JavaScript for PowerGym frontend
 * Handles dynamic content loading, UI interactions, and accessibility enhancements
 */

document.addEventListener('DOMContentLoaded', () => {
  initSkipNav();
  loadClassSchedule();
  animateCounters();
});

/**
 * Accessibility: Skip Navigation Link Focus Handling
 */
function initSkipNav() {
  const skipLink = document.querySelector('.skip-nav');
  if (!skipLink) return;

  skipLink.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' || e.key === ' ') {
      const targetId = skipLink.getAttribute('href').substring(1);
      const target = document.getElementById(targetId);
      if (target) {
        target.setAttribute('tabindex', '-1');
        target.focus();
        target.addEventListener('blur', () => {
          target.removeAttribute('tabindex');
        }, { once: true });
      }
    }
  });
}

/**
 * Fetch and render today's class schedule from API
 */
function loadClassSchedule() {
  const scheduleContainer = document.getElementById('schedule-container');
  if (!scheduleContainer) return;

  const day = new Date().toLocaleDateString('en-US', { weekday: 'long' });

  fetch(`/api/get_schedule.php?day=${day}`)
    .then(response => {
      if (!response.ok) throw new Error('Network response was not OK');
      return response.json();
    })
    .then(data => {
      if (!Array.isArray(data) || data.length === 0) {
        scheduleContainer.innerHTML = `<p class="text-center">No classes scheduled for today.</p>`;
        return;
      }
      scheduleContainer.innerHTML = data.map(item => `
        <article class="schedule-card" role="region" aria-label="Class: ${item.name}">
          <h3>${escapeHtml(item.name)}</h3>
          <p>${escapeHtml(item.time)} with ${escapeHtml(item.trainer)}</p>
        </article>
      `).join('');
    })
    .catch(error => {
      console.error('Error loading schedule:', error);
      scheduleContainer.innerHTML = `<p class="text-center text-danger">Failed to load schedule. Please try again later.</p>`;
    });
}

/**
 * Animate counters from 0 to target number
 */
function animateCounters() {
  const counters = document.querySelectorAll('.counter');
  if (counters.length === 0) return;

  const animationDuration = 2000; // ms
  counters.forEach(counter => {
    const target = +counter.getAttribute('data-target');
    if (isNaN(target)) return;

    let startTimestamp = null;
    const step = (timestamp) => {
      if (!startTimestamp) startTimestamp = timestamp;
      const progress = timestamp - startTimestamp;
      const value = Math.min(Math.ceil((progress / animationDuration) * target), target);
      counter.textContent = value;
      if (progress < animationDuration) {
        window.requestAnimationFrame(step);
      }
    };
    window.requestAnimationFrame(step);
  });
}

/**
 * Simple HTML escape utility to prevent XSS injection in dynamic content
 * @param {string} str - Text to escape
 * @returns {string}
 */
function escapeHtml(str) {
  if (typeof str !== 'string') return '';
  return str
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#39;");
}
