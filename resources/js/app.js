import "./bootstrap";

const activeClasses = [
    "text-blue-700",
    "border-b-2",
    "border-blue-700",
    "py-2",
];

const inactiveClasses = ["text-slate-600"];

const setActiveLink = (activeId) => {
    const links = document.querySelectorAll(".js-nav-link");

    links.forEach((link) => {
        const targetId = link.getAttribute("href")?.replace("#", "");
        const isActive = targetId === activeId;

        link.classList.toggle("text-blue-700", isActive);
        link.classList.toggle("border-b-2", isActive);
        link.classList.toggle("border-blue-700", isActive);
        link.classList.toggle("py-2", isActive);
        link.classList.toggle("text-slate-600", !isActive);
    });
};

document.addEventListener("DOMContentLoaded", () => {
    const sections = Array.from(
        document.querySelectorAll("section[id], footer[id]"),
    );

    if (!sections.length) {
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            const visible = entries
                .filter((entry) => entry.isIntersecting)
                .sort((a, b) => b.intersectionRatio - a.intersectionRatio);

            if (visible.length > 0) {
                setActiveLink(visible[0].target.id);
                return;
            }

            const sortedByTop = entries
                .filter((entry) => entry.boundingClientRect.top <= 0)
                .sort(
                    (a, b) =>
                        b.boundingClientRect.top - a.boundingClientRect.top,
                );

            if (sortedByTop.length > 0) {
                setActiveLink(sortedByTop[0].target.id);
            }
        },
        {
            rootMargin: "-35% 0px -35% 0px",
            threshold: [0, 0.1, 0.25, 0.5],
        },
    );

    sections.forEach((section) => observer.observe(section));

    setActiveLink(sections[0].id);
});
