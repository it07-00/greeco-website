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

    const pickerRoot = document.querySelector('[data-course-picker]');

    if (pickerRoot) {
        const pickerTimeline = gsap.timeline({ defaults: { ease: 'power3.out' } });

        pickerTimeline
            .from('[data-picker-kicker]', { opacity: 0, y: 16, duration: 0.45 })
            .from('[data-picker-title]', { opacity: 0, y: 34, duration: 0.75 }, '-=0.2')
            .from('[data-picker-copy]', { opacity: 0, y: 20, duration: 0.55 }, '-=0.38')
            .from('[data-picker-actions]', { opacity: 0, y: 16, duration: 0.5 }, '-=0.32')
            .from(
                '[data-picker-visual]',
                { opacity: 0.55, scale: 0.84, duration: 0.9 },
                '-=0.72',
            );

        const pickerVisual = pickerRoot.querySelector('[data-picker-visual]');

        if (pickerVisual) {
            gsap.to(pickerVisual, {
                filter: 'brightness(0.58)',
                opacity: 0.3,
                ease: 'none',
                scrollTrigger: {
                    trigger: '.picker-hero',
                    start: '55% top',
                    end: 'bottom top',
                    scrub: true,
                },
            });
        }

        gsap.fromTo(
            pickerRoot.querySelector('[data-picker-panel]'),
            { opacity: 0.72, y: 48 },
            {
                opacity: 1,
                y: 0,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: '[data-picker-panel]',
                    start: 'top 88%',
                    end: 'top 58%',
                    scrub: true,
                },
            },
        );

        gsap.fromTo(
            pickerRoot.querySelectorAll('[data-picker-step]'),
            { opacity: 0.68, y: 42 },
            {
                opacity: 1,
                y: 0,
                stagger: 0.12,
                duration: 0.7,
                ease: 'power3.out',
                scrollTrigger: {
                    trigger: '.picker-steps',
                    start: 'top 82%',
                },
            },
        );
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
