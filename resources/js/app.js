import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', () => {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    const heroTimeline = gsap.timeline({ defaults: { ease: 'power3.out' } });
    const heroTitle = document.querySelector('[data-course-hero-title]');
    const heroCopy = document.querySelector('[data-course-hero-copy]');
    const heroActions = document.querySelector('[data-course-hero-actions]');

    if (heroTitle) {
        heroTimeline
            .from(heroTitle, { y: 20, duration: 0.65 })
            .from(heroCopy, { y: 12, duration: 0.5 }, '-=0.35')
            .from(heroActions, { y: 10, duration: 0.45 }, '-=0.3');
    }

    document.querySelectorAll('[data-word-reveal]').forEach((element) => {
        const text = element.textContent.trim();

        if (! text) {
            return;
        }

        element.setAttribute('aria-label', text);
        element.innerHTML = text
            .split(/\s+/)
            .map((word) => {
                const safeWord = word
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');

                return `<span aria-hidden="true" class="word-reveal-token">${safeWord}</span>`;
            })
            .join(' ');

        gsap.fromTo(
            element.querySelectorAll('.word-reveal-token'),
            { opacity: 0.18 },
            {
                opacity: 1,
                stagger: 0.035,
                ease: 'none',
                scrollTrigger: {
                    trigger: element,
                    start: 'top 88%',
                    end: 'bottom 52%',
                    scrub: true,
                },
            },
        );
    });

    document.querySelectorAll('[data-scale-reveal]').forEach((element) => {
        gsap.fromTo(
            element,
            { scale: 0.88, opacity: 0.35 },
            {
                scale: 1,
                opacity: 1,
                ease: 'none',
                scrollTrigger: {
                    trigger: element,
                    start: 'top 92%',
                    end: 'center 62%',
                    scrub: true,
                },
            },
        );
    });

    const media = gsap.matchMedia();

    media.add('(min-width: 992px)', () => {
        document.querySelectorAll('[data-pin-container]').forEach((container) => {
            const panel = container.querySelector('[data-pin-panel]');

            if (! panel || panel.offsetHeight > window.innerHeight - 150) {
                return;
            }

            ScrollTrigger.create({
                trigger: container,
                start: 'top 110px',
                end: 'bottom bottom-=70',
                pin: panel,
                pinSpacing: false,
            });
        });
    });
});
//
