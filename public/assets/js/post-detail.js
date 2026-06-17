document.addEventListener('DOMContentLoaded', function () {
    const postText = document.querySelector('.post-text, .service-content, .project-content');
    if (!postText) return;

    // Select h2, h3, h4 headings inside the content container
    const headings = Array.from(postText.querySelectorAll('h2, h3, h4'));
    if (headings.length < 2) return; // Only show TOC if there are at least 2 headings

    // Vietnamese slugify function for neat anchor IDs
    function slugify(text) {
        let slug = text.toString().toLowerCase().trim();
        const from = "àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđ";
        const to   = "aaaaaaaaaaaaaaaaaeeeeeeeeeeeiiiiiooooooooooooooooouuuuuuuuuuuyyyyyd";
        for (let i = 0, l = from.length; i < l; i++) {
            slug = slug.replace(new RegExp(from[i], 'g'), to[i]);
        }
        return slug
            .replace(/[^a-z0-9 -]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    // Create TOC container using Bootstrap classes
    const tocContainer = document.createElement('div');
    tocContainer.className = 'post-toc-container bg-light border rounded-3 p-3 mb-4 shadow-sm';
    tocContainer.id = 'post-toc';

    const tocHeader = document.createElement('div');
    tocHeader.className = 'post-toc-header d-flex justify-content-between align-items-center';

    const tocTitle = document.createElement('h5');
    tocTitle.className = 'h6 mb-0 fw-bold d-flex align-items-center text-dark';
    tocTitle.innerHTML = '<i class="fa-solid fa-list-ol me-2 post-toc-title-icon"></i>Mục lục bài viết';

    const toggleBtn = document.createElement('button');
    toggleBtn.className = 'btn btn-link btn-sm text-decoration-none fw-semibold p-1';
    toggleBtn.id = 'toc-toggle-btn';
    toggleBtn.textContent = '[Ẩn]';
    toggleBtn.setAttribute('type', 'button');
    toggleBtn.setAttribute('data-bs-toggle', 'collapse');
    toggleBtn.setAttribute('data-bs-target', '#post-toc-list');
    toggleBtn.setAttribute('aria-expanded', 'true');
    toggleBtn.setAttribute('aria-controls', 'post-toc-list');

    tocHeader.appendChild(tocTitle);
    tocHeader.appendChild(toggleBtn);
    tocContainer.appendChild(tocHeader);

    const tocList = document.createElement('ul');
    tocList.className = 'list-unstyled mb-0 collapse show post-toc-list';
    tocList.id = 'post-toc-list';

    // Get unique heading tags present in the document and sort them (e.g., h2, h3, h4)
    const uniqueTags = Array.from(new Set(headings.map(h => h.tagName.toLowerCase()))).sort();
    
    // Initialize counters for each level
    const counters = uniqueTags.map(() => 0);

    headings.forEach(function (heading) {
        const tagName = heading.tagName.toLowerCase();
        const tagIndex = uniqueTags.indexOf(tagName);
        let numbering = '';
        let itemClass = 'mb-2';

        // Increment counter at tagIndex
        counters[tagIndex]++;
        
        // Reset all sub-counters
        for (let i = tagIndex + 1; i < counters.length; i++) {
            counters[i] = 0;
        }

        // Construct numbering string (e.g. "1.", "1.1.", "1.1.1.")
        const activeNumbers = counters.slice(0, tagIndex + 1);
        numbering = activeNumbers.join('.') + '.';

        // Determine CSS classes for styling indentation based on level (tagIndex)
        if (tagIndex === 0) {
            itemClass += ' ps-0';
        } else if (tagIndex === 1) {
            itemClass += ' ps-3 fs-95';
        } else if (tagIndex === 2) {
            itemClass += ' ps-4 fs-90';
        } else {
            itemClass += ' ps-5 fs-85';
        }

        // Ensure the heading has an ID
        if (!heading.id) {
            const baseId = slugify(heading.textContent);
            heading.id = baseId || `section-${Math.random().toString(36).substr(2, 9)}`;
        }

        const li = document.createElement('li');
        li.className = itemClass;

        const a = document.createElement('a');
        a.className = 'text-decoration-none text-secondary d-inline-flex align-items-start post-toc-link';
        a.href = `#${heading.id}`;

        const numSpan = document.createElement('span');
        numSpan.className = 'fw-semibold me-2';
        numSpan.textContent = numbering;

        const textSpan = document.createElement('span');
        textSpan.className = 'toc-text';
        textSpan.textContent = heading.textContent.trim();

        a.appendChild(numSpan);
        a.appendChild(textSpan);
        li.appendChild(a);
        tocList.appendChild(li);
    });

    tocContainer.appendChild(tocList);

    // Prepend TOC inside content container
    postText.insertBefore(tocContainer, postText.firstChild);

    // Toggle functionality on Bootstrap collapse events
    tocList.addEventListener('show.bs.collapse', function () {
        toggleBtn.textContent = '[Ẩn]';
    });
    tocList.addEventListener('hide.bs.collapse', function () {
        toggleBtn.textContent = '[Hiện]';
    });

    // Smooth scrolling to headings with offset
    const tocLinks = Array.from(tocContainer.querySelectorAll('.post-toc-link'));
    
    tocLinks.forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);

            if (targetElement) {
                const header = document.querySelector('header');
                const headerOffset = header ? header.offsetHeight + 30 : 120;
                const elementPosition = targetElement.getBoundingClientRect().top + window.scrollY;
                const offsetPosition = elementPosition - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });

                // Update address bar hash without page jump
                history.pushState(null, null, `#${targetId}`);
            }
        });
    });

    // ScrollSpy function to highlight current active heading
    function scrollSpy() {
        const header = document.querySelector('header');
        const headerOffset = header ? header.offsetHeight + 40 : 130;
        const scrollPosition = window.scrollY + headerOffset + 10;
        let activeHeading = null;

        // Find heading currently visible in viewport
        for (let i = 0; i < headings.length; i++) {
            const heading = headings[i];
            const headingTop = heading.getBoundingClientRect().top + window.scrollY;

            if (scrollPosition >= headingTop) {
                activeHeading = heading;
            } else {
                break;
            }
        }

        // If at bottom of page, highlight the last heading
        if ((window.innerHeight + window.scrollY) >= document.documentElement.scrollHeight - 20) {
            if (headings.length > 0) {
                activeHeading = headings[headings.length - 1];
            }
        }

        // Apply active class
        tocLinks.forEach(function (link) {
            link.classList.remove('active');
            if (activeHeading && link.getAttribute('href') === `#${activeHeading.id}`) {
                link.classList.add('active');
            }
        });
    }

    // Bind ScrollSpy event listeners
    window.addEventListener('scroll', scrollSpy);
    // Run scrollSpy once on load to highlight if page loads with a hash
    scrollSpy();

    // Copy Share Link logic
    const copyBtn = document.getElementById('copy-share-link');
    if (copyBtn) {
        copyBtn.addEventListener('click', function () {
            const url = this.getAttribute('data-url');
            navigator.clipboard.writeText(url).then(function () {
                copyBtn.classList.add('show-tooltip');
                setTimeout(function () {
                    copyBtn.classList.remove('show-tooltip');
                }, 2000);
            }).catch(function (err) {
                console.error('Không thể sao chép liên kết: ', err);
            });
        });
    }
});
